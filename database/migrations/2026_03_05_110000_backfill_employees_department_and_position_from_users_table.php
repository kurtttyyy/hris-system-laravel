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

        if (!Schema::hasColumn('employees', 'department') || !Schema::hasColumn('employees', 'position')) {
            return;
        }

        $rows = DB::table('users')
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->select(['id', 'department', 'position', 'job_role'])
            ->get();

        foreach ($rows as $row) {
            $department = trim((string) ($row->department ?? ''));
            $position = trim((string) ($row->position ?? ''));
            $jobRole = trim((string) ($row->job_role ?? ''));

            $updates = ['updated_at' => now()];
            if ($department !== '') {
                $updates['department'] = $department;
            }

            if ($position !== '') {
                $updates['position'] = $position;
            } elseif ($jobRole !== '') {
                $updates['position'] = $jobRole;
            }

            if (count($updates) === 1) {
                continue;
            }

            DB::table('employees')
                ->where('user_id', $row->id)
                ->update($updates);
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
