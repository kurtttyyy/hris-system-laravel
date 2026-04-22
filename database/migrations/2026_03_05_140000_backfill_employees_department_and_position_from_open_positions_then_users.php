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
        if (!Schema::hasTable('users') || !Schema::hasTable('employees') || !Schema::hasTable('applicants') || !Schema::hasTable('open_positions')) {
            return;
        }

        $users = DB::table('users')
            ->whereRaw("LOWER(TRIM(COALESCE(role, ''))) = ?", ['employee'])
            ->select(['id', 'department', 'position', 'job_role'])
            ->get();

        $usesMysqlTriggers = in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);

        // Prevent employees->users trigger from writing back during this one-time backfill.
        if ($usesMysqlTriggers) {
            DB::statement("SET @sync_origin = 'users'");
        }

        foreach ($users as $user) {
            $openPosition = DB::table('applicants as a')
                ->join('open_positions as op', 'op.id', '=', 'a.open_position_id')
                ->where('a.user_id', $user->id)
                ->whereNull('a.deleted_at')
                ->whereNull('op.deleted_at')
                ->orderByDesc('a.id')
                ->select([
                    DB::raw("NULLIF(TRIM(op.department), '') as op_department"),
                    DB::raw("NULLIF(TRIM(op.title), '') as op_title"),
                ])
                ->first();

            $openDepartment = trim((string) ($openPosition->op_department ?? ''));
            $openTitle = trim((string) ($openPosition->op_title ?? ''));
            $userDepartment = trim((string) ($user->department ?? ''));
            $userPosition = trim((string) ($user->position ?? ''));
            $userJobRole = trim((string) ($user->job_role ?? ''));

            $resolvedDepartment = $openDepartment !== ''
                ? $openDepartment
                : ($userDepartment !== '' ? $userDepartment : null);

            $resolvedPosition = $openTitle !== ''
                ? $openTitle
                : ($userPosition !== ''
                    ? $userPosition
                    : ($userJobRole !== '' ? $userJobRole : null));

            DB::table('employees')
                ->where('user_id', $user->id)
                ->update([
                    'department' => $resolvedDepartment,
                    'position' => $resolvedPosition,
                    'updated_at' => now(),
                ]);
        }

        if ($usesMysqlTriggers) {
            DB::statement('SET @sync_origin = NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for backfill.
    }
};
