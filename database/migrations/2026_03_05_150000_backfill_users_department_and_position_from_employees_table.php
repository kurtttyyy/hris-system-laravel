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

        // Prevent users->employees trigger from bouncing writes during this one-time backfill.
        DB::statement("SET @sync_origin = 'employees'");

        DB::table('users as u')
            ->join('employees as e', 'e.user_id', '=', 'u.id')
            ->whereRaw("LOWER(TRIM(COALESCE(u.role, ''))) = ?", ['employee'])
            ->where(function ($query) {
                $query->whereNotNull('e.department')
                    ->whereRaw("TRIM(e.department) <> ''")
                    ->orWhereNotNull('e.position')
                    ->whereRaw("TRIM(e.position) <> ''");
            })
            ->update([
                'u.department' => DB::raw("COALESCE(NULLIF(TRIM(e.department), ''), NULL)"),
                'u.position' => DB::raw("COALESCE(NULLIF(TRIM(e.position), ''), NULL)"),
            ]);

        DB::statement('SET @sync_origin = NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for data backfill.
    }
};

