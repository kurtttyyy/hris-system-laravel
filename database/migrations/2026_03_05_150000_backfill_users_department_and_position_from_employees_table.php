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
        if (!Schema::hasTable('users') || !Schema::hasTable('employees')) {
            return;
        }

        $usesMysqlTriggers = in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);

        // Prevent users->employees trigger from bouncing writes during this one-time backfill.
        if ($usesMysqlTriggers) {
            DB::statement("SET @sync_origin = 'employees'");
        }

        $rows = DB::table('users as u')
            ->join('employees as e', 'e.user_id', '=', 'u.id')
            ->whereRaw("LOWER(TRIM(COALESCE(u.role, ''))) = ?", ['employee'])
            ->where(function ($query) {
                $query->whereNotNull('e.department')
                    ->whereRaw("TRIM(e.department) <> ''")
                    ->orWhereNotNull('e.position')
                    ->whereRaw("TRIM(e.position) <> ''");
            })
            ->select('u.id as user_id', 'e.department as employee_department', 'e.position as employee_position')
            ->get();

        foreach ($rows as $row) {
            DB::table('users')
                ->where('id', $row->user_id)
                ->update([
                    'department' => trim((string) ($row->employee_department ?? '')) !== '' ? $row->employee_department : null,
                    'position' => trim((string) ($row->employee_position ?? '')) !== '' ? $row->employee_position : null,
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
        // No rollback for data backfill.
    }
};
