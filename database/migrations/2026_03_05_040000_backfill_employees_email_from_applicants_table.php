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

        if (!Schema::hasColumn('employees', 'email') || !Schema::hasColumn('applicants', 'email')) {
            return;
        }

        $rows = DB::table('employees as e')
            ->join('applicants as a', 'a.user_id', '=', 'e.user_id')
            ->where(function ($query) {
                $query->whereNull('e.email')
                    ->orWhereRaw("TRIM(e.email) = ''");
            })
            ->whereNotNull('a.email')
            ->whereRaw("TRIM(a.email) <> ''")
            ->select('e.id as employee_id', 'a.email as applicant_email')
            ->get();

        foreach ($rows as $row) {
            DB::table('employees')
                ->where('id', $row->employee_id)
                ->update([
                    'email' => $row->applicant_email,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback: this migration backfills existing records only.
    }
};
