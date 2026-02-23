<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantDocument;
use App\Models\AttendanceRecord;
use App\Models\User;
use App\Models\LeaveApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeePageController extends Controller
{
    public function display_home(){  // Employee Home page
        $user = Auth::user();
        $selectedMonth = now()->format('Y-m');
        $leaveMetrics = $this->buildEmployeeLeaveMetrics($user, $selectedMonth);
        $attendanceMetrics = $this->buildEmployeeAttendanceMetrics($user, $selectedMonth);
        $weeklyAttendance = $this->buildWeeklyAttendanceData($user);

        return view('employee.employeeHome', array_merge(
            ['user' => $user],
            $leaveMetrics,
            $attendanceMetrics,
            $weeklyAttendance
        ));
    }

    public function display_leave(){
        $user = Auth::user();
        $selectedMonth = trim((string) request()->query('month', now()->format('Y-m')));
        try {
            $monthCursor = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $e) {
            $monthCursor = now()->startOfMonth();
            $selectedMonth = $monthCursor->format('Y-m');
        }

        $employeeDisplayName = $this->formatEmployeeDisplayName(
            $user?->first_name,
            $user?->middle_name,
            $user?->last_name
        );

        $isTeaching = strcasecmp((string) ($user?->employee?->job_type ?? ''), 'Teaching') === 0;
        $joinDate = null;
        if ($isTeaching && !empty($user?->applicant?->date_hired)) {
            $joinDate = Carbon::parse($user->applicant->date_hired);
        } elseif (!empty($user?->employee?->employement_date)) {
            $joinDate = Carbon::parse($user->employee->employement_date);
        } elseif (!empty($user?->applicant?->date_hired)) {
            $joinDate = Carbon::parse($user->applicant->date_hired);
        }
        $resetCycleMonths = $isTeaching ? 10 : 12;

        $defaultLeaveAllowances = [
            'Study Leave' => 5,
            'Emergency Leave' => 3,
            'Maternity Leave' => 105,
            'Paternity Leave' => 7,
            'Bereavement Leave' => 5,
            'Service Incentive Leave' => 5,
        ];

        $beginningVacationBalance = 0.0;
        $beginningSickBalance = 0.0;
        $totalEarnedDays = $this->calculateMonthlyEarnedLeaveDays(
            $joinDate,
            $monthCursor,
            $resetCycleMonths
        );
        $earnedRangeLabel = $this->buildEarnedRangeLabel($joinDate, $monthCursor, $resetCycleMonths);
        $monthStart = $monthCursor->copy()->startOfMonth();
        $monthEnd = $monthCursor->copy()->endOfMonth();
        $monthApplications = LeaveApplication::query()
            ->where('user_id', $user?->id)
            ->where(function ($query) use ($monthCursor) {
                $query
                    ->where(function ($filingDateQuery) use ($monthCursor) {
                        $filingDateQuery
                            ->whereNotNull('filing_date')
                            ->whereYear('filing_date', $monthCursor->year)
                            ->whereMonth('filing_date', $monthCursor->month);
                    })
                    ->orWhere(function ($createdAtQuery) use ($monthCursor) {
                        $createdAtQuery
                            ->whereNull('filing_date')
                            ->whereYear('created_at', $monthCursor->year)
                            ->whereMonth('created_at', $monthCursor->month);
                    });
            })
            ->orderByDesc('created_at')
            ->get();

        $approvedMonthApplications = $monthApplications
            ->filter(function ($application) {
                return strcasecmp((string) ($application->status ?? ''), 'Approved') === 0;
            })
            ->values();
        $pendingMonthApplications = $monthApplications
            ->filter(function ($application) {
                $status = trim((string) ($application->status ?? ''));
                return $status === '' || strcasecmp($status, 'Pending') === 0;
            })
            ->values();

        $mapApplicationToRecord = function ($application) use ($employeeDisplayName) {
            $leaveType = (string) ($application->leave_type ?: 'Leave');
            $leaveTypeNormalized = strtolower(trim($leaveType));
            $baseDate = $application->filing_date
                ? Carbon::parse($application->filing_date)->startOfDay()
                : Carbon::parse($application->created_at)->startOfDay();
            $days = (float) ($application->number_of_working_days ?? 0);
            if ($days <= 0) {
                $days = max(
                    (float) ($application->days_with_pay ?? 0),
                    (float) ($application->applied_total ?? 0)
                );
            }
            $rangeDays = max((int) ceil($days), 1);

            $reasonText = $application->inclusive_dates ?: '-';
            if (str_contains($leaveTypeNormalized, 'official business')) {
                $reasonText = 'Business Trip';
            } elseif (str_contains($leaveTypeNormalized, 'annual leave')) {
                $reasonText = 'Personal vacation';
            } elseif (str_contains($leaveTypeNormalized, 'sick leave')) {
                $reasonText = 'Not fit for work due to health reasons';
            }

            $statusText = trim((string) ($application->status ?? ''));
            if ($statusText === '') {
                $statusText = 'Pending';
            }

            return [
                'employee_name' => $application->employee_name ?: $employeeDisplayName,
                'leave_type' => $leaveType,
                'start_date_carbon' => $baseDate->copy(),
                'end_date_carbon' => $baseDate->copy()->addDays($rangeDays - 1),
                'days' => $days,
                'reason' => $reasonText,
                'status' => $statusText,
            ];
        };

        $monthRequestRecords = $monthApplications
            ->map($mapApplicationToRecord)
            ->values();

        $employeeMonthRecords = $approvedMonthApplications
            ->map($mapApplicationToRecord)
            ->values();

        $latestLeaveApplication = LeaveApplication::query()
            ->where('user_id', $user?->id)
            ->whereDate('created_at', '<=', $monthEnd->toDateString())
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])
            ->orderByDesc('created_at')
            ->first();

        if ($latestLeaveApplication) {
            $beginningVacationBalance = (float) ($latestLeaveApplication->ending_vacation ?? 0);
            $beginningSickBalance = (float) ($latestLeaveApplication->ending_sick ?? 0);
        }

        $hasExistingMonthApplication = LeaveApplication::query()
            ->where('user_id', $user?->id)
            ->whereBetween('created_at', [$monthStart->toDateTimeString(), $monthEnd->toDateTimeString()])
            ->exists();

        $equalHalfEarnedDays = round($totalEarnedDays / 2, 1);
        $formEarnedVacation = $hasExistingMonthApplication ? 0.0 : $equalHalfEarnedDays;
        $formEarnedSick = $hasExistingMonthApplication ? 0.0 : $equalHalfEarnedDays;
        $formEarnedTotal = round($formEarnedVacation + $formEarnedSick, 1);

        $monthlyLeaveAllowances = collect($defaultLeaveAllowances)
            ->mapWithKeys(fn ($value, $leaveType) => [$leaveType => max(0, (int) $value)])
            ->all();
        $monthlyLeaveAllowances['Annual Leave'] = $equalHalfEarnedDays;
        $monthlyLeaveAllowances['Sick Leave'] = $equalHalfEarnedDays;

        $employeeLeaveUsageByType = $employeeMonthRecords
            ->groupBy(fn ($record) => (string) ($record['leave_type'] ?? 'Leave'))
            ->map(fn ($records) => (int) $records->sum('days'));

        $annualLimit = (float) ($monthlyLeaveAllowances['Annual Leave'] ?? 0);
        $annualUsed = (float) ($employeeLeaveUsageByType->get('Annual Leave', 0));
        $sickLimit = (float) ($monthlyLeaveAllowances['Sick Leave'] ?? 0);
        $sickUsed = (float) ($employeeLeaveUsageByType->get('Sick Leave', 0));
        $personalLimit = (int) ($monthlyLeaveAllowances['Personal Leave'] ?? 0);
        $personalUsed = (int) ($employeeLeaveUsageByType->get('Personal Leave', 0));
        $totalDaysUsed = (float) $employeeMonthRecords->sum('days');

        $vacationCardAvailable = max($annualLimit - $annualUsed, 0);
        $sickCardAvailable = max($sickLimit - $sickUsed, 0);
        $totalDaysUsedCard = $totalDaysUsed;

        $monthAppliedTotal = round((float) $approvedMonthApplications->sum('applied_total'), 1);
        $monthOfficialWithPayTotal = round((float) $approvedMonthApplications
            ->filter(function ($application) {
                $leaveType = strtolower(trim((string) ($application->leave_type ?? '')));

                return str_contains($leaveType, 'official business')
                    || str_contains($leaveType, 'official time')
                    || str_starts_with($leaveType, 'others');
            })
            ->sum('days_with_pay'), 1);
        $monthUsageTotal = round($monthAppliedTotal + $monthOfficialWithPayTotal, 1);
        $pendingLeaveDays = round((float) $pendingMonthApplications->sum(function ($application) {
            return (float) ($application->number_of_working_days ?? 0);
        }), 1);

        if ($latestLeaveApplication) {
            $annualLimit = round((float) ($latestLeaveApplication->beginning_vacation ?? 0) + (float) ($latestLeaveApplication->earned_vacation ?? 0), 1);
            $annualUsed = round((float) ($latestLeaveApplication->applied_vacation ?? 0), 1);
            $sickLimit = round((float) ($latestLeaveApplication->beginning_sick ?? 0) + (float) ($latestLeaveApplication->earned_sick ?? 0), 1);
            $sickUsed = round((float) ($latestLeaveApplication->applied_sick ?? 0), 1);
            $vacationCardAvailable = round((float) ($latestLeaveApplication->ending_vacation ?? 0), 1);
            $sickCardAvailable = round((float) ($latestLeaveApplication->ending_sick ?? 0), 1);
            $fallbackUsedDays = (float) ($latestLeaveApplication->applied_total ?? $totalDaysUsed);
            $totalDaysUsedCard = round($monthUsageTotal > 0 ? $monthUsageTotal : $fallbackUsedDays, 1);
        }

        return view('employee.employeeLeave', compact(
            'selectedMonth',
            'employeeDisplayName',
            'monthRequestRecords',
            'employeeMonthRecords',
            'pendingMonthApplications',
            'pendingLeaveDays',
            'annualLimit',
            'annualUsed',
            'sickLimit',
            'sickUsed',
            'personalLimit',
            'personalUsed',
            'totalDaysUsed',
            'vacationCardAvailable',
            'sickCardAvailable',
            'totalDaysUsedCard',
            'beginningVacationBalance',
            'beginningSickBalance',
            'earnedRangeLabel',
            'totalEarnedDays',
            'formEarnedVacation',
            'formEarnedSick',
            'formEarnedTotal'
        ));
    }

    public function display_profile(){
        $user = Auth::user();
        $emp = User::with([
            'employee',
            'applicant',
            'education',
            'license',
            'salary',
            'government',
        ])->where('id', $user->id)->first();

        $serviceDurationText = '0Y 0M 0D';
        $joinDate = null;
        try {
            if (!empty($emp?->employee?->employement_date)) {
                $joinDate = Carbon::parse($emp->employee->employement_date)->startOfDay();
            } elseif (!empty($emp?->applicant?->date_hired)) {
                $joinDate = Carbon::parse($emp->applicant->date_hired)->startOfDay();
            }
        } catch (\Throwable $e) {
            $joinDate = null;
        }

        if ($joinDate) {
            $today = now()->startOfDay();
            if ($joinDate->gt($today)) {
                $joinDate = $today->copy();
            }
            $diff = $joinDate->diff($today);
            $serviceDurationText = $diff->y.'Y '.$diff->m.'M '.$diff->d.'D';
        }

        $attendanceMetrics = $this->buildEmployeeAttendanceMetrics($user, now()->format('Y-m'));
        $attendanceRatePercent = (float) ($attendanceMetrics['attendanceRatePercent'] ?? 0);
        $leaveMetrics = $this->buildEmployeeLeaveMetrics($user, now()->format('Y-m'));
        $leaveDaysUsed = (float) ($leaveMetrics['totalDaysUsedCard'] ?? 0);

        return view('employee.employeeProfile', compact(
            'emp',
            'serviceDurationText',
            'attendanceRatePercent',
            'leaveDaysUsed'
        ));
    }

    public function display_payslip(){
        return view('employee.employeePayslip');
    }

    public function display_document(){
        $user_id = Auth::id();
        $applicant = Applicant::where('user_id', $user_id)
                                ->where('application_status','Hired')
                                ->first();

        $documents = collect();
        $latestDocument = null;
        $requiredDocuments = [];
        $missingDocuments = [];
        $documentNotice = '';
        if ($applicant) {
            $requiredPrefix = '__REQUIRED__::';
            $noticeType = '__NOTICE__';
            $documents = ApplicantDocument::where('applicant_id', $applicant->id)
                ->where('type', 'not like', $requiredPrefix.'%')
                ->where('type', '!=', $noticeType)
                ->latest('created_at')
                ->get();
            $latestDocument = $documents->first();

            $requiredConfig = $this->getRequiredDocumentConfigForApplicant((int) $applicant->id);
            $requiredDocuments = collect($requiredConfig['required_documents'] ?? [])
                ->map(fn ($item) => trim((string) $item))
                ->filter()
                ->values()
                ->all();
            $documentNotice = (string) ($requiredConfig['document_notice'] ?? '');

            $uploadedDocumentTypesNormalized = $documents
                ->map(function ($doc) {
                    return $this->normalizeDocumentLabel((string) ($doc->type ?: $doc->filename));
                })
                ->filter()
                ->unique()
                ->values();

            $missingDocuments = collect($requiredDocuments)
                ->filter(function ($required) use ($uploadedDocumentTypesNormalized) {
                    return !$uploadedDocumentTypesNormalized->contains(
                        $this->normalizeDocumentLabel((string) $required)
                    );
                })
                ->values()
                ->all();
        }

        return view('employee.employeeDocument', compact(
            'documents',
            'latestDocument',
            'requiredDocuments',
            'missingDocuments',
            'documentNotice'
        ));
    }

    public function view_document($id)
    {
        $userId = Auth::id();
        $applicant = Applicant::where('user_id', $userId)
            ->where('application_status', 'Hired')
            ->first();

        if (!$applicant) {
            abort(403);
        }

        $document = ApplicantDocument::where('id', $id)
            ->where('applicant_id', $applicant->id)
            ->firstOrFail();

        $relativePath = ltrim((string) ($document->filepath ?? ''), '/');
        if ($relativePath === '') {
            abort(404);
        }

        $disk = Storage::disk('public');
        if (!$disk->exists($relativePath)) {
            abort(404);
        }

        $absolutePath = $disk->path($relativePath);
        $fileName = (string) ($document->filename ?: basename($relativePath));
        $extension = strtolower((string) pathinfo($fileName, PATHINFO_EXTENSION));
        $mimeType = (string) ($document->mime_type ?: $disk->mimeType($relativePath) ?: '');
        if ($mimeType === '' || $mimeType === 'application/octet-stream') {
            $mimeType = match ($extension) {
                'pdf' => 'application/pdf',
                'png' => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'txt' => 'text/plain',
                default => 'application/octet-stream',
            };
        }
        return Response::file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Content-Security-Policy' => "frame-ancestors 'self'",
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function display_document_preview($id)
    {
        $userId = Auth::id();
        $applicant = Applicant::where('user_id', $userId)
            ->where('application_status', 'Hired')
            ->first();

        if (!$applicant) {
            abort(403);
        }

        $document = ApplicantDocument::where('id', $id)
            ->where('applicant_id', $applicant->id)
            ->firstOrFail();

        $fileName = (string) ($document->filename ?: 'Document');
        $extension = strtolower((string) pathinfo($fileName, PATHINFO_EXTENSION));
        $previewUrl = route('employee.employeeDocument.view', ['id' => $document->id]);
        $isPdf = $extension === 'pdf';
        $isImage = in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp'], true);

        return view('employee.employeeDocumentPreview', compact(
            'document',
            'previewUrl',
            'isPdf',
            'isImage',
            'extension'
        ));
    }

    public function display_communication(){
        $admins = User::query()
            ->whereIn('role', ['admin', 'Admin'])
            ->orderBy('first_name')
            ->get();

        return view('employee.employeeCommunication', compact('admins'));
    }

    private function formatEmployeeDisplayName($firstName, $middleName, $lastName): ?string
    {
        $first = trim((string) ($firstName ?? ''));
        $middle = trim((string) ($middleName ?? ''));
        $last = trim((string) ($lastName ?? ''));

        $firstMiddle = trim(implode(' ', array_filter([$first, $middle], fn ($part) => $part !== '')));

        if ($last !== '' && $firstMiddle !== '') {
            return "{$last}, {$firstMiddle}";
        }
        if ($last !== '') {
            return $last;
        }
        if ($firstMiddle !== '') {
            return $firstMiddle;
        }

        return null;
    }

    private function getSharedLeaveRecords()
    {
        return collect([
            [
                'employee_name' => 'Santos, Maria L.',
                'department' => 'Faculty',
                'leave_type' => 'Sick Leave',
                'start_date' => '2026-02-12',
                'end_date' => '2026-02-12',
                'status' => 'Approved',
                'reason' => 'Flu and medical check-up',
            ],
            [
                'employee_name' => 'Reyes, John Paulo A.',
                'department' => 'Admin',
                'leave_type' => 'Annual Leave',
                'start_date' => '2026-02-10',
                'end_date' => '2026-02-14',
                'status' => 'Approved',
                'reason' => 'Family vacation',
            ],
            [
                'employee_name' => 'Dela Cruz, Anna P.',
                'department' => 'Faculty',
                'leave_type' => 'Study Leave',
                'start_date' => '2026-02-17',
                'end_date' => '2026-02-18',
                'status' => 'Pending',
                'reason' => 'Graduate exam preparation',
            ],
            [
                'employee_name' => 'Garcia, Miguel R.',
                'department' => 'Registrar',
                'leave_type' => 'Emergency Leave',
                'start_date' => '2026-02-08',
                'end_date' => '2026-02-08',
                'status' => 'Approved',
                'reason' => 'Immediate family concern',
            ],
            [
                'employee_name' => 'Lopez, Carla M.',
                'department' => 'Faculty',
                'leave_type' => 'Maternity Leave',
                'start_date' => '2026-01-20',
                'end_date' => '2026-02-20',
                'status' => 'Approved',
                'reason' => 'Maternity recovery',
            ],
            [
                'employee_name' => 'Torres, Noel B.',
                'department' => 'Guidance',
                'leave_type' => 'Paternity Leave',
                'start_date' => '2026-02-05',
                'end_date' => '2026-02-11',
                'status' => 'Approved',
                'reason' => 'Child birth support',
            ],
            [
                'employee_name' => 'Nolasco, Irene T.',
                'department' => 'HR',
                'leave_type' => 'Personal Leave',
                'start_date' => '2026-02-22',
                'end_date' => '2026-02-22',
                'status' => 'Declined',
                'reason' => 'Personal errand',
            ],
        ])->map(function ($record) {
            $start = Carbon::parse($record['start_date'])->startOfDay();
            $end = Carbon::parse($record['end_date'])->startOfDay();
            $days = $end->gte($start) ? ($start->diffInDays($end) + 1) : 1;
            $record['days'] = $days;
            $record['start_date_carbon'] = $start;
            $record['end_date_carbon'] = $end;
            return $record;
        });
    }

    private function calculateMonthlyEarnedLeaveDays(?Carbon $joinDate, Carbon $monthCursor, ?int $resetCycleMonths = null): int
    {
        if (!$joinDate) {
            return 0;
        }

        $accrualStartDate = $joinDate->copy()->addYear()->startOfDay();
        $accrualStartMonth = $accrualStartDate->copy()->startOfMonth();
        $selectedMonthEnd = $monthCursor->copy()->endOfMonth();
        $todayEnd = now()->endOfDay();
        $accrualCutoff = $selectedMonthEnd->lte($todayEnd) ? $selectedMonthEnd : $todayEnd;

        if ($accrualCutoff->lt($accrualStartDate)) {
            return 0;
        }

        $months = $accrualStartMonth->diffInMonths($accrualCutoff->copy()->startOfMonth()) + 1;

        $months = max(0, $months);

        if (!is_null($resetCycleMonths) && $resetCycleMonths > 0 && $months > 0) {
            $months = (($months - 1) % $resetCycleMonths) + 1;
        }

        return $months;
    }

    private function calculateCompletedMonthsUntilCutoff(?Carbon $joinDate, Carbon $monthCursor): int
    {
        if (!$joinDate) {
            return 0;
        }

        $accrualStartDate = $joinDate->copy()->addYear()->startOfDay();
        $accrualStartMonth = $accrualStartDate->copy()->startOfMonth();
        $selectedMonthEnd = $monthCursor->copy()->endOfMonth();
        $todayEnd = now()->endOfDay();
        $accrualCutoff = $selectedMonthEnd->lte($todayEnd) ? $selectedMonthEnd : $todayEnd;

        if ($accrualCutoff->lt($accrualStartDate)) {
            return 0;
        }

        $months = $accrualStartMonth->diffInMonths($accrualCutoff->copy()->startOfMonth()) + 1;

        return max(0, $months);
    }

    private function buildEarnedRangeLabel(?Carbon $joinDate, Carbon $monthCursor, int $resetCycleMonths): string
    {
        if (!$joinDate || $resetCycleMonths <= 0) {
            return '-';
        }

        $completedMonths = $this->calculateCompletedMonthsUntilCutoff($joinDate, $monthCursor);
        if ($completedMonths <= 0) {
            return '-';
        }

        $accrualStartMonth = $joinDate->copy()->addYear()->startOfMonth();
        $monthsInCurrentCycle = (($completedMonths - 1) % $resetCycleMonths) + 1;
        $completedCycleMonths = $completedMonths - $monthsInCurrentCycle;

        $rangeStart = $accrualStartMonth->copy()->addMonths($completedCycleMonths)->startOfMonth();
        $rangeEnd = $rangeStart->copy()->addMonths($monthsInCurrentCycle - 1)->startOfMonth();

        if ($rangeStart->year === $rangeEnd->year) {
            if ($rangeStart->format('M') === $rangeEnd->format('M')) {
                return $rangeStart->format('M').', '.$rangeStart->year;
            }

            return $rangeStart->format('M').'-'.$rangeEnd->format('M').', '.$rangeStart->year;
        }

        return $rangeStart->format('M Y').' - '.$rangeEnd->format('M Y');
    }

    private function buildEmployeeLeaveMetrics($user, string $selectedMonth): array
    {
        try {
            $monthCursor = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $e) {
            $monthCursor = now()->startOfMonth();
            $selectedMonth = $monthCursor->format('Y-m');
        }

        $isTeaching = strcasecmp((string) ($user?->employee?->job_type ?? ''), 'Teaching') === 0;
        $joinDate = null;
        if ($isTeaching && !empty($user?->applicant?->date_hired)) {
            $joinDate = Carbon::parse($user->applicant->date_hired);
        } elseif (!empty($user?->employee?->employement_date)) {
            $joinDate = Carbon::parse($user->employee->employement_date);
        } elseif (!empty($user?->applicant?->date_hired)) {
            $joinDate = Carbon::parse($user->applicant->date_hired);
        }

        $resetCycleMonths = $isTeaching ? 10 : 12;
        $monthEnd = $monthCursor->copy()->endOfMonth();
        $equalHalfEarnedDays = round(
            $this->calculateMonthlyEarnedLeaveDays($joinDate, $monthCursor, $resetCycleMonths) / 2,
            1
        );

        $annualLimit = $equalHalfEarnedDays;
        $sickLimit = $equalHalfEarnedDays;
        $annualUsed = 0.0;
        $sickUsed = 0.0;
        $vacationCardAvailable = max($annualLimit - $annualUsed, 0);
        $sickCardAvailable = max($sickLimit - $sickUsed, 0);

        $latestLeaveApplication = LeaveApplication::query()
            ->where('user_id', $user?->id)
            ->whereDate('created_at', '<=', $monthEnd->toDateString())
            ->whereRaw("LOWER(TRIM(COALESCE(status, ''))) = ?", ['approved'])
            ->orderByDesc('created_at')
            ->first();

        if ($latestLeaveApplication) {
            $annualLimit = round((float) ($latestLeaveApplication->beginning_vacation ?? 0) + (float) ($latestLeaveApplication->earned_vacation ?? 0), 1);
            $annualUsed = round((float) ($latestLeaveApplication->applied_vacation ?? 0), 1);
            $sickLimit = round((float) ($latestLeaveApplication->beginning_sick ?? 0) + (float) ($latestLeaveApplication->earned_sick ?? 0), 1);
            $sickUsed = round((float) ($latestLeaveApplication->applied_sick ?? 0), 1);
            $vacationCardAvailable = round((float) ($latestLeaveApplication->ending_vacation ?? 0), 1);
            $sickCardAvailable = round((float) ($latestLeaveApplication->ending_sick ?? 0), 1);
        }

        // Keep Days Used consistent with employeeLeave summary logic.
        $monthApplications = LeaveApplication::query()
            ->where('user_id', $user?->id)
            ->where(function ($query) use ($monthCursor) {
                $query
                    ->where(function ($filingDateQuery) use ($monthCursor) {
                        $filingDateQuery
                            ->whereNotNull('filing_date')
                            ->whereYear('filing_date', $monthCursor->year)
                            ->whereMonth('filing_date', $monthCursor->month);
                    })
                    ->orWhere(function ($createdAtQuery) use ($monthCursor) {
                        $createdAtQuery
                            ->whereNull('filing_date')
                            ->whereYear('created_at', $monthCursor->year)
                            ->whereMonth('created_at', $monthCursor->month);
                    });
            })
            ->orderByDesc('created_at')
            ->get();

        $approvedMonthApplications = $monthApplications
            ->filter(function ($application) {
                return strcasecmp((string) ($application->status ?? ''), 'Approved') === 0;
            })
            ->values();

        $monthAppliedTotal = round((float) $approvedMonthApplications->sum('applied_total'), 1);
        $monthOfficialWithPayTotal = round((float) $approvedMonthApplications
            ->filter(function ($application) {
                $leaveType = strtolower(trim((string) ($application->leave_type ?? '')));

                return str_contains($leaveType, 'official business')
                    || str_contains($leaveType, 'official time')
                    || str_starts_with($leaveType, 'others');
            })
            ->sum('days_with_pay'), 1);
        $monthUsageTotal = round($monthAppliedTotal + $monthOfficialWithPayTotal, 1);
        $totalDaysUsedCard = $monthUsageTotal;

        if ($latestLeaveApplication) {
            $fallbackUsedDays = (float) ($latestLeaveApplication->applied_total ?? 0);
            $totalDaysUsedCard = round($monthUsageTotal > 0 ? $monthUsageTotal : $fallbackUsedDays, 1);
        }

        $combinedAvailable = round($vacationCardAvailable + $sickCardAvailable, 1);
        $combinedLimit = max(round($annualLimit + $sickLimit, 1), 0.0);
        $combinedUsed = round($annualUsed + $sickUsed, 1);
        $combinedUsagePercent = $combinedLimit > 0
            ? (int) round(min(100, max(0, ($combinedUsed / $combinedLimit) * 100)))
            : 0;

        return [
            'vacationCardAvailable' => $vacationCardAvailable,
            'sickCardAvailable' => $sickCardAvailable,
            'annualLimit' => $annualLimit,
            'annualUsed' => $annualUsed,
            'sickLimit' => $sickLimit,
            'sickUsed' => $sickUsed,
            'combinedLeaveAvailable' => $combinedAvailable,
            'combinedLeavePercentUsed' => $combinedUsagePercent,
            'totalDaysUsedCard' => $totalDaysUsedCard,
            'selectedMonth' => $selectedMonth,
        ];
    }

    private function buildEmployeeAttendanceMetrics($user, string $selectedMonth): array
    {
        try {
            $monthCursor = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $e) {
            $monthCursor = now()->startOfMonth();
        }

        $rangeStart = $monthCursor->copy()->startOfMonth();
        $rangeEnd = $monthCursor->copy()->endOfMonth();
        $holidayNoClassDates = $this->getHolidayNoClassDatesForMonth($monthCursor);

        $eligibleDays = 0;
        $cursor = $rangeStart->copy();
        while ($cursor->lte($rangeEnd)) {
            if (!$cursor->isSunday()) {
                $eligibleDays++;
            }
            $cursor->addDay();
        }

        $employeeIdCandidates = $this->buildEmployeeIdCandidatesForAttendance($user);
        if (empty($employeeIdCandidates)) {
            return [
                'attendanceRatePercent' => 0.0,
                'attendancePresentDays' => 0,
                'attendanceTotalDays' => $eligibleDays,
                'attendanceTardyDays' => 0,
                'attendanceStatusLabel' => 'No Data',
                'attendanceMonthLabel' => $monthCursor->format('F Y'),
            ];
        }

        $recordsByDate = AttendanceRecord::query()
            ->whereIn('employee_id', $employeeIdCandidates)
            ->whereDate('attendance_date', '>=', $rangeStart->toDateString())
            ->whereDate('attendance_date', '<=', $rangeEnd->toDateString())
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->groupBy(function ($row) {
                try {
                    return $row->attendance_date
                        ? Carbon::parse($row->attendance_date)->toDateString()
                        : null;
                } catch (\Throwable $e) {
                    return null;
                }
            });

        $presentDays = 0;
        $tardyDays = 0;
        $cursor = $rangeStart->copy();

        while ($cursor->lte($rangeEnd)) {
            if ($cursor->isSunday()) {
                $cursor->addDay();
                continue;
            }

            $dateKey = $cursor->toDateString();
            if (in_array($dateKey, $holidayNoClassDates, true)) {
                $presentDays++;
                $cursor->addDay();
                continue;
            }
            $dateRows = $recordsByDate->get($dateKey, collect());
            $isPresent = false;
            $isTardy = false;

            foreach ($dateRows as $row) {
                if (!$this->isAttendanceRowPresent($row)) {
                    continue;
                }

                $isPresent = true;

                $isHolidayPresent = (bool) ($row->is_holiday_present ?? false)
                    || trim((string) ($row->main_gate ?? '')) === 'Holiday - No Class';
                if (!$isHolidayPresent && $this->calculateAttendanceLateMinutes($row) > 0) {
                    $isTardy = true;
                }
            }

            if ($isPresent) {
                $presentDays++;
            }
            if ($isTardy) {
                $tardyDays++;
            }

            $cursor->addDay();
        }

        $attendanceRate = $eligibleDays > 0
            ? round(($presentDays / $eligibleDays) * 100, 1)
            : 0.0;

        $statusLabel = $tardyDays > 0
            ? $tardyDays.' Late Day'.($tardyDays > 1 ? 's' : '')
            : 'On Time';

        return [
            'attendanceRatePercent' => $attendanceRate,
            'attendancePresentDays' => $presentDays,
            'attendanceTotalDays' => $eligibleDays,
            'attendanceTardyDays' => $tardyDays,
            'attendanceStatusLabel' => $statusLabel,
            'attendanceMonthLabel' => $monthCursor->format('F Y'),
        ];
    }

    private function buildWeeklyAttendanceData($user): array
    {
        $weekStart = now()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $weekEnd = $weekStart->copy()->addDays(5)->endOfDay(); // Monday-Saturday

        $employeeIdCandidates = $this->buildEmployeeIdCandidatesForAttendance($user);
        $recordsByDate = collect();

        if (!empty($employeeIdCandidates)) {
            $recordsByDate = AttendanceRecord::query()
                ->whereIn('employee_id', $employeeIdCandidates)
                ->whereDate('attendance_date', '>=', $weekStart->toDateString())
                ->whereDate('attendance_date', '<=', $weekEnd->toDateString())
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->get()
                ->groupBy(function ($row) {
                    try {
                        return $row->attendance_date
                            ? Carbon::parse($row->attendance_date)->toDateString()
                            : null;
                    } catch (\Throwable $e) {
                        return null;
                    }
                });
        }

        $weeklyRows = collect();
        $cursor = $weekStart->copy();
        while ($cursor->lte($weekEnd)) {
            $dateKey = $cursor->toDateString();
            $rows = $recordsByDate->get($dateKey, collect());
            $selectedRow = null;

            foreach ($rows as $row) {
                if ($this->isAttendanceRowPresent($row)) {
                    $selectedRow = $row;
                    break;
                }
            }
            if (!$selectedRow && $rows->isNotEmpty()) {
                $selectedRow = $rows->first();
            }

            $morningRange = $this->formatTimeRangeForDisplay(
                $selectedRow->morning_in ?? null,
                $selectedRow->morning_out ?? null
            );
            $afternoonRange = $this->formatTimeRangeForDisplay(
                $selectedRow->afternoon_in ?? null,
                $selectedRow->afternoon_out ?? null
            );
            $morningHours = $this->calculateWorkedHoursForDisplay(
                $selectedRow->morning_in ?? null,
                $selectedRow->morning_out ?? null
            );
            $afternoonHours = $this->calculateWorkedHoursForDisplay(
                $selectedRow->afternoon_in ?? null,
                $selectedRow->afternoon_out ?? null
            );

            $status = 'Absent';
            $statusClass = 'bg-rose-100 text-rose-600';
            if ($selectedRow) {
                $isHolidayPresent = (bool) ($selectedRow->is_holiday_present ?? false)
                    || trim((string) ($selectedRow->main_gate ?? '')) === 'Holiday - No Class';
                if ($isHolidayPresent) {
                    $status = 'No Class (Holiday)';
                    $statusClass = 'bg-indigo-100 text-indigo-600';
                } elseif ($this->isAttendanceRowPresent($selectedRow)) {
                    $late = $this->calculateAttendanceLateMinutes($selectedRow);
                    if ($late > 0) {
                        $status = 'Late ('.$late.' mins)';
                        $statusClass = 'bg-amber-100 text-amber-700';
                    } else {
                        $status = 'Present';
                        $statusClass = 'bg-green-100 text-green-600';
                    }
                }
            } else {
                $morningRange = 'No Log';
                $afternoonRange = 'No Log';
                $morningHours = '0 hrs worked';
                $afternoonHours = '0 hrs worked';
            }

            $weeklyRows->push([
                'day_short' => $cursor->format('D'),
                'day_number' => $cursor->format('d'),
                'date_label' => $cursor->format('M d, Y'),
                'morning_range' => $morningRange,
                'afternoon_range' => $afternoonRange,
                'morning_hours' => $morningHours,
                'afternoon_hours' => $afternoonHours,
                'status' => $status,
                'status_class' => $statusClass,
            ]);

            $cursor->addDay();
        }

        return [
            'weeklyAttendanceRows' => $weeklyRows->all(),
            'weeklyAttendanceRangeLabel' => $weekStart->format('M d').' - '.$weekEnd->format('M d'),
        ];
    }

    private function buildEmployeeIdCandidatesForAttendance($user): array
    {
        $raw = trim((string) ($user?->employee?->employee_id ?? ''));
        $normalized = $this->normalizeEmployeeIdForAttendance($raw);

        $candidates = collect();
        if ($normalized !== '') {
            $candidates->push($normalized);
            if (preg_match('/^\d+$/', $normalized)) {
                $candidates->push($normalized.'.0');
            }
        }

        // Fallback: resolve employee IDs from attendance employee_name for accounts
        // where employee_id mapping is missing or inconsistent.
        $firstName = trim((string) ($user?->first_name ?? ''));
        $lastName = trim((string) ($user?->last_name ?? ''));
        if ($firstName !== '' || $lastName !== '') {
            $matchedIds = AttendanceRecord::query()
                ->whereNotNull('employee_name')
                ->where(function ($query) use ($firstName, $lastName) {
                    if ($lastName !== '') {
                        $query->where('employee_name', 'like', '%'.$lastName.'%');
                    }
                    if ($firstName !== '') {
                        $query->where('employee_name', 'like', '%'.$firstName.'%');
                    }
                })
                ->distinct()
                ->pluck('employee_id')
                ->map(fn ($id) => $this->normalizeEmployeeIdForAttendance($id))
                ->filter()
                ->values();

            $candidates = $candidates->merge($matchedIds);
        }

        return $candidates
            ->flatMap(function ($id) {
                $idText = (string) $id;
                if (preg_match('/^\d+$/', $idText)) {
                    return [$idText, $idText.'.0'];
                }
                return [$idText];
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function getHolidayNoClassDatesForMonth(Carbon $monthCursor): array
    {
        $monthStart = $monthCursor->copy()->startOfMonth();
        $monthEnd = $monthCursor->copy()->endOfMonth();
        $dates = collect();

        // Admin calendar-synced holiday config (custom + recurring holidays).
        try {
            if (Storage::disk('local')->exists('calendar_holiday_config.json')) {
                $payload = json_decode((string) Storage::disk('local')->get('calendar_holiday_config.json'), true);
                if (is_array($payload)) {
                    $customHolidays = is_array($payload['custom_holidays'] ?? null) ? $payload['custom_holidays'] : [];
                    foreach (array_keys($customHolidays) as $dateText) {
                        try {
                            $date = Carbon::parse((string) $dateText)->toDateString();
                            if ($date >= $monthStart->toDateString() && $date <= $monthEnd->toDateString()) {
                                $dates->push($date);
                            }
                        } catch (\Throwable $e) {
                        }
                    }

                    $recurringHolidays = is_array($payload['recurring_holidays'] ?? null) ? $payload['recurring_holidays'] : [];
                    foreach (array_keys($recurringHolidays) as $monthDay) {
                        if (!preg_match('/^\d{2}-\d{2}$/', (string) $monthDay)) {
                            continue;
                        }
                        [$month, $day] = array_map('intval', explode('-', (string) $monthDay));
                        if ($month !== (int) $monthCursor->month) {
                            continue;
                        }
                        if (!checkdate($month, $day, (int) $monthCursor->year)) {
                            continue;
                        }
                        $dates->push(Carbon::create((int) $monthCursor->year, $month, $day)->toDateString());
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        // Also include attendance rows tagged as holiday no-class.
        try {
            $dates = $dates->merge(
                AttendanceRecord::query()
                    ->whereDate('attendance_date', '>=', $monthStart->toDateString())
                    ->whereDate('attendance_date', '<=', $monthEnd->toDateString())
                    ->where('main_gate', 'Holiday - No Class')
                    ->pluck('attendance_date')
                    ->map(function ($date) {
                        try {
                            return Carbon::parse($date)->toDateString();
                        } catch (\Throwable $e) {
                            return null;
                        }
                    })
                    ->filter()
                    ->values()
            );
        } catch (\Throwable $e) {
        }

        return $dates->unique()->values()->all();
    }

    private function normalizeEmployeeIdForAttendance($value): string
    {
        $normalized = trim((string) $value);
        if ($normalized === '') {
            return '';
        }

        if (preg_match('/^(\d+)\.0+$/', $normalized, $matches)) {
            return $matches[1];
        }

        return $normalized;
    }

    private function isAttendanceRowPresent($row): bool
    {
        $isHolidayPresent = (bool) ($row->is_holiday_present ?? false)
            || trim((string) ($row->main_gate ?? '')) === 'Holiday - No Class';
        if ($isHolidayPresent) {
            return true;
        }

        $hasAnyTimeLog = !empty($row->morning_in)
            || !empty($row->morning_out)
            || !empty($row->afternoon_in)
            || !empty($row->afternoon_out);
        if (!$hasAnyTimeLog) {
            return false;
        }

        return !(bool) ($row->is_absent ?? false);
    }

    private function calculateAttendanceLateMinutes($row): int
    {
        $late = 0;

        $morningIn = $this->normalizeTimeValue($row->morning_in ?? null);
        if ($morningIn) {
            try {
                $morningActual = Carbon::createFromFormat('H:i:s', $morningIn);
                $morningExpected = Carbon::createFromFormat('H:i:s', '08:00:00');
                $morningGraceEnd = Carbon::createFromFormat('H:i:s', '08:15:00');
                if ($morningActual->greaterThan($morningGraceEnd)) {
                    $late += $morningExpected->diffInMinutes($morningActual);
                }
            } catch (\Throwable $e) {
            }
        }

        $afternoonIn = $this->normalizeTimeValue($row->afternoon_in ?? null);
        if ($afternoonIn) {
            try {
                $afternoonActual = Carbon::createFromFormat('H:i:s', $afternoonIn);
                $afternoonExpected = Carbon::createFromFormat('H:i:s', '13:00:00');
                $afternoonGraceEnd = Carbon::createFromFormat('H:i:s', '13:15:00');
                if ($afternoonActual->greaterThan($afternoonGraceEnd)) {
                    $late += $afternoonExpected->diffInMinutes($afternoonActual);
                }
            } catch (\Throwable $e) {
            }
        }

        return $late;
    }

    private function normalizeTimeValue(?string $time): ?string
    {
        if (!$time) {
            return null;
        }

        $value = trim((string) $time);
        if ($value === '') {
            return null;
        }

        foreach (['H:i:s', 'H:i'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('H:i:s');
            } catch (\Throwable $e) {
            }
        }

        try {
            return Carbon::parse($value)->format('H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function formatTimeRangeForDisplay(?string $start, ?string $end): string
    {
        $startNorm = $this->normalizeTimeValue($start);
        $endNorm = $this->normalizeTimeValue($end);

        if (!$startNorm && !$endNorm) {
            return 'No Log';
        }

        $startText = $startNorm ? Carbon::createFromFormat('H:i:s', $startNorm)->format('h:i A') : '--';
        $endText = $endNorm ? Carbon::createFromFormat('H:i:s', $endNorm)->format('h:i A') : '--';

        return $startText.' - '.$endText;
    }

    private function calculateWorkedHoursForDisplay(?string $start, ?string $end): string
    {
        $startNorm = $this->normalizeTimeValue($start);
        $endNorm = $this->normalizeTimeValue($end);
        if (!$startNorm || !$endNorm) {
            return '0 hrs worked';
        }

        try {
            $startTime = Carbon::createFromFormat('H:i:s', $startNorm);
            $endTime = Carbon::createFromFormat('H:i:s', $endNorm);
            if ($endTime->lt($startTime)) {
                return '0 hrs worked';
            }
            $minutes = $startTime->diffInMinutes($endTime);
            $hours = round($minutes / 60, 1);
            return rtrim(rtrim(number_format($hours, 1, '.', ''), '0'), '.').' hrs worked';
        } catch (\Throwable $e) {
            return '0 hrs worked';
        }
    }

    private function getRequiredDocumentConfigForApplicant(int $applicantId): array
    {
        if ($applicantId <= 0) {
            return [];
        }

        $requiredPrefix = '__REQUIRED__::';
        $noticeType = '__NOTICE__';
        $metaDocuments = ApplicantDocument::query()
            ->where('applicant_id', $applicantId)
            ->where(function ($query) use ($requiredPrefix, $noticeType) {
                $query
                    ->where('type', 'like', $requiredPrefix.'%')
                    ->orWhere('type', $noticeType);
            })
            ->orderByDesc('id')
            ->get();

        if ($metaDocuments->isNotEmpty()) {
            $requiredDocuments = $metaDocuments
                ->filter(fn ($doc) => str_starts_with((string) ($doc->type ?? ''), $requiredPrefix))
                ->map(function ($doc) use ($requiredPrefix) {
                    return trim((string) substr((string) $doc->type, strlen($requiredPrefix)));
                })
                ->filter()
                ->unique(function ($value) {
                    return strtolower($value);
                })
                ->values()
                ->all();

            $notice = (string) optional($metaDocuments->firstWhere('type', $noticeType))->filename;

            return [
                'required_documents' => $requiredDocuments,
                'document_notice' => $notice,
            ];
        }

        $disk = Storage::disk('local');
        $path = 'required_employee_documents.json';
        if (!$disk->exists($path)) {
            return [];
        }

        $payload = json_decode((string) $disk->get($path), true);
        if (!is_array($payload)) {
            return [];
        }

        $applicants = is_array($payload['applicants'] ?? null) ? $payload['applicants'] : [];
        $entry = $applicants[(string) $applicantId] ?? null;
        if (!is_array($entry)) {
            return [];
        }

        return $entry;
    }

    private function normalizeDocumentLabel(string $value): string
    {
        $normalized = strtolower(trim($value));
        if ($normalized === '') {
            return '';
        }

        return preg_replace('/\s+/', ' ', $normalized);
    }

}
