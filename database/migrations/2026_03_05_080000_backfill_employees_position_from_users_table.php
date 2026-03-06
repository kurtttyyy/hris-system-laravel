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
        if (!Schema::hasTable('employees') || !Schema::hasTable('users')) {
            return;
        }

        if (!Schema::hasColumn('employees', 'position') || !Schema::hasColumn('users', 'position')) {
            return;
        }

        $rows = DB::table('employees as e')
            ->join('users as u', 'u.id', '=', 'e.user_id')
            ->select([
                'e.id as employee_id',
                'e.position as employee_position',
                'u.position as user_position',
                'u.job_role as user_job_role',
            ])
            ->get();

        foreach ($rows as $row) {
            $employeePosition = trim((string) ($row->employee_position ?? ''));
            $userPosition = trim((string) ($row->user_position ?? ''));
            $userJobRole = trim((string) ($row->user_job_role ?? ''));
            $resolvedPosition = $userPosition !== '' ? $userPosition : $userJobRole;

            if ($resolvedPosition === '') {
                continue;
            }

            $shouldUpdate = $employeePosition === ''
                || strcasecmp($employeePosition, 'Employee') === 0;

            if (!$shouldUpdate) {
                continue;
            }

            DB::table('employees')
                ->where('id', $row->employee_id)
                ->update([
                    'position' => $resolvedPosition,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for data backfill.
    }
};

