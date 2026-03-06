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

        if (!Schema::hasColumn('employees', 'email') || !Schema::hasColumn('users', 'email')) {
            return;
        }

        $rows = DB::table('employees as e')
            ->join('users as u', 'u.id', '=', 'e.user_id')
            ->whereNotNull('u.email')
            ->whereRaw("TRIM(u.email) <> ''")
            ->select('e.id as employee_id', 'u.email as user_email')
            ->get();

        foreach ($rows as $row) {
            DB::table('employees')
                ->where('id', $row->employee_id)
                ->update([
                    'email' => $row->user_email,
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

