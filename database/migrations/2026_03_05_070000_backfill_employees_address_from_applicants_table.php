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

        DB::table('employees as e')
            ->join('applicants as a', 'a.user_id', '=', 'e.user_id')
            ->whereNotNull('a.address')
            ->whereRaw("TRIM(a.address) <> ''")
            ->update([
                'e.address' => DB::raw('a.address'),
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

