<?php

namespace App\Support;

use App\Models\Applicant;
use App\Models\LeaveApplication;
use App\Models\Resignation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeAccountStatusManager
{
    public function syncAllEmployeeStatuses(?Carbon $targetDate = null): int
    {
        $synced = 0;
        $date = ($targetDate ?? Carbon::today())->copy()->startOfDay();

        User::query()
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->select(['id', 'role', 'account_status'])
            ->chunkById(100, function ($users) use (&$synced, $date) {
                foreach ($users as $user) {
                    $this->syncUserAccountStatus($user, $date);
                    $synced++;
                }
            });

        return $synced;
    }

    public function syncUserAccountStatus(User|int $user, ?Carbon $targetDate = null): string
    {
        $model = $user instanceof User
            ? $user
            : User::query()->find((int) $user);

        if (!$model) {
            return 'Active';
        }

        $resolvedStatus = $this->resolveAccountStatus($model, $targetDate);
        if (trim((string) ($model->account_status ?? '')) !== $resolvedStatus) {
            $model->forceFill([
                'account_status' => $resolvedStatus,
            ])->save();
        }

        return $resolvedStatus;
    }

    public function resolveAccountStatus(User|int $user, ?Carbon $targetDate = null): string
    {
        $model = $user instanceof User
            ? $user
            : User::query()->find((int) $user);

        if (!$model) {
            return 'Active';
        }

        $userId = (int) $model->id;
        if ($userId <= 0) {
            return 'Active';
        }

        $latestApprovedOrCompletedResignation = Resignation::query()
            ->where('user_id', $userId)
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) IN (?, ?)", ['approved', 'completed'])
            ->orderByDesc(DB::raw('COALESCE(effective_date, processed_at, submitted_at, created_at)'))
            ->orderByDesc('id')
            ->first();

        if ($latestApprovedOrCompletedResignation) {
            $latestResignationDate = $latestApprovedOrCompletedResignation->processed_at
                ?? $latestApprovedOrCompletedResignation->submitted_at
                ?? $latestApprovedOrCompletedResignation->created_at;
            $latestResignationEffectiveDate = $latestApprovedOrCompletedResignation->effective_date;

            $rehiredAfterResignation = Applicant::query()
                ->where('user_id', $userId)
                ->whereRaw("LOWER(TRIM(COALESCE(application_status, ''))) = ?", ['hired'])
                ->where(function ($query) use ($latestResignationDate, $latestResignationEffectiveDate) {
                    $query->where('created_at', '>', $latestResignationDate);

                    if ($latestResignationDate) {
                        $query->orWhere('date_hired', '>', Carbon::parse($latestResignationDate)->toDateString());
                    }

                    if ($latestResignationEffectiveDate) {
                        $query->orWhere('date_hired', '>', Carbon::parse($latestResignationEffectiveDate)->toDateString());
                    }
                })
                ->exists();

            if (!$rehiredAfterResignation) {
                return 'Inactive';
            }
        }

        if (strcasecmp(trim((string) ($model->role ?? '')), 'employee') !== 0) {
            return trim((string) ($model->account_status ?? '')) !== ''
                ? (string) $model->account_status
                : 'Active';
        }

        $comparisonDate = ($targetDate ?? Carbon::today())->copy()->startOfDay();
        $hasApprovedLeave = LeaveApplication::query()
            ->where('user_id', $userId)
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])
            ->get()
            ->contains(fn (LeaveApplication $leaveApplication) => $this->isLeaveApplicationActiveOnDate($leaveApplication, $comparisonDate));

        return $hasApprovedLeave ? 'On Leave' : 'Active';
    }

    public function isLeaveApplicationActiveOnDate(LeaveApplication $leaveApplication, ?Carbon $targetDate = null): bool
    {
        [$startDate, $endDate] = $this->resolveLeaveApplicationDateRange($leaveApplication);
        if (!$startDate || !$endDate) {
            return false;
        }

        $comparisonDate = ($targetDate ?? Carbon::today())->copy()->startOfDay();
        return $comparisonDate->betweenIncluded($startDate, $endDate);
    }

    public function resolveLeaveApplicationDateRange(LeaveApplication $leaveApplication): array
    {
        $parseDate = static function ($value): ?Carbon {
            $text = trim((string) ($value ?? ''));
            if ($text === '') {
                return null;
            }

            foreach (['Y-m-d', 'm/d/Y', 'n/j/Y'] as $format) {
                try {
                    return Carbon::createFromFormat($format, $text)->startOfDay();
                } catch (\Throwable $e) {
                }
            }

            try {
                return Carbon::parse($text)->startOfDay();
            } catch (\Throwable $e) {
                return null;
            }
        };

        $inclusiveDates = trim((string) ($leaveApplication->inclusive_dates ?? ''));
        $matchedDates = [];
        if ($inclusiveDates !== '') {
            preg_match_all('/\b\d{4}-\d{2}-\d{2}\b|\b\d{1,2}\/\d{1,2}\/\d{4}\b/', $inclusiveDates, $matches);
            $matchedDates = $matches[0] ?? [];
        }

        $startDate = isset($matchedDates[0]) ? $parseDate($matchedDates[0]) : null;
        $endDate = isset($matchedDates[1]) ? $parseDate($matchedDates[1]) : null;

        if (!$startDate) {
            $startDate = $parseDate($leaveApplication->filing_date) ?: $parseDate($leaveApplication->created_at);
        }

        $days = (float) ($leaveApplication->number_of_working_days ?? 0);
        if ($days <= 0) {
            $days = max(
                (float) ($leaveApplication->days_with_pay ?? 0),
                (float) ($leaveApplication->days_without_pay ?? 0),
                (float) ($leaveApplication->applied_total ?? 0)
            );
        }

        if (!$endDate && $startDate) {
            $rangeDays = max((int) ceil($days), 1);
            $endDate = $startDate->copy()->addDays($rangeDays - 1);
        }

        if ($startDate && $endDate && $endDate->lt($startDate)) {
            $endDate = $startDate->copy();
        }

        return [$startDate, $endDate];
    }
}
