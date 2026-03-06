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

        DB::table('employees as e')
            ->join('applicants as a', 'a.user_id', '=', 'e.user_id')
            ->whereNotNull('a.phone')
            ->whereRaw("TRIM(a.phone) <> ''")
            ->update([
                'e.contact_number' => DB::raw('a.phone'),
                'e.updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for data backfill.
    }
};

