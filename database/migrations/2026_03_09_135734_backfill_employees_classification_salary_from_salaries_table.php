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
        if (!Schema::hasTable('employees') || !Schema::hasTable('salaries')) {
            return;
        }

        if (!Schema::hasColumn('employees', 'classification_salary')
            || !Schema::hasColumn('employees', 'user_id')
            || !Schema::hasColumn('salaries', 'user_id')
            || !Schema::hasColumn('salaries', 'salary')) {
            return;
        }

        DB::table('salaries')
            ->select(['id', 'user_id', 'salary'])
            ->whereNotNull('user_id')
            ->whereNotNull('salary')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    $salary = trim((string) ($row->salary ?? ''));
                    if ($salary === '') {
                        continue;
                    }

                    DB::table('employees')
                        ->where('user_id', (int) $row->user_id)
                        ->update(['classification_salary' => $salary]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: backfill data migration.
    }
};
