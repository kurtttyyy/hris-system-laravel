<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasTable('applicants') || !Schema::hasTable('open_positions')) {
            return;
        }

        if (!Schema::hasColumn('users', 'position') || !Schema::hasColumn('users', 'department')) {
            return;
        }

        $users = DB::table('users')
            ->select(['id', 'position', 'department'])
            ->get();

        foreach ($users as $user) {
            $applicant = DB::table('applicants as a')
                ->leftJoin('open_positions as op', 'op.id', '=', 'a.open_position_id')
                ->where('a.user_id', (int) $user->id)
                ->whereNull('a.deleted_at')
                ->orderByRaw("CASE WHEN LOWER(COALESCE(a.application_status, '')) = 'hired' THEN 0 ELSE 1 END")
                ->orderByDesc('a.date_hired')
                ->orderByDesc('a.created_at')
                ->select([
                    'a.work_position',
                    'op.title as open_position_title',
                    'op.department as open_position_department',
                ])
                ->first();

            if (!$applicant) {
                continue;
            }

            $currentPosition = trim((string) ($user->position ?? ''));
            $currentDepartment = trim((string) ($user->department ?? ''));

            $resolvedPosition = $currentPosition !== ''
                ? $currentPosition
                : trim((string) (($applicant->open_position_title ?? '') ?: ($applicant->work_position ?? '')));

            $resolvedDepartment = $currentDepartment !== ''
                ? $currentDepartment
                : trim((string) ($applicant->open_position_department ?? ''));

            $payload = [];
            if ($currentPosition === '' && $resolvedPosition !== '') {
                $payload['position'] = $resolvedPosition;
            }
            if ($currentDepartment === '' && $resolvedDepartment !== '') {
                $payload['department'] = $resolvedDepartment;
            }

            if (!empty($payload)) {
                DB::table('users')
                    ->where('id', (int) $user->id)
                    ->update($payload);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left blank: backfilled values should not be removed automatically.
    }
};

