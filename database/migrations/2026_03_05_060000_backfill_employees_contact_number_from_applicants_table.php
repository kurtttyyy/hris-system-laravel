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

        if (!Schema::hasColumn('employees', 'contact_number') || !Schema::hasColumn('applicants', 'phone')) {
            return;
        }

        $rows = DB::table('employees as e')
            ->join('applicants as a', 'a.user_id', '=', 'e.user_id')
            ->whereNotNull('a.phone')
            ->whereRaw("TRIM(a.phone) <> ''")
            ->select('e.id as employee_id', 'a.phone as applicant_phone')
            ->get();

        foreach ($rows as $row) {
            DB::table('employees')
                ->where('id', $row->employee_id)
                ->update([
                    'contact_number' => $row->applicant_phone,
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
