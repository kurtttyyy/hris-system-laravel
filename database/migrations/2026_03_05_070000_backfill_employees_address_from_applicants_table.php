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
        if (!Schema::hasTable('employees') || !Schema::hasTable('applicants')) {
            return;
        }

        if (!Schema::hasColumn('employees', 'address') || !Schema::hasColumn('applicants', 'address')) {
            return;
        }

        $rows = DB::table('employees as e')
            ->join('applicants as a', 'a.user_id', '=', 'e.user_id')
            ->whereNotNull('a.address')
            ->whereRaw("TRIM(a.address) <> ''")
            ->select('e.id as employee_id', 'a.address as applicant_address')
            ->get();

        foreach ($rows as $row) {
            DB::table('employees')
                ->where('id', $row->employee_id)
                ->update([
                    'address' => $row->applicant_address,
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
