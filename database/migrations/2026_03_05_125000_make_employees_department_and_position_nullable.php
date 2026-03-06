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
        if (!Schema::hasTable('employees')) {
            return;
        }

        $driver = DB::connection()->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement('ALTER TABLE employees MODIFY department VARCHAR(255) NULL');
        DB::statement('ALTER TABLE employees MODIFY position VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('employees')) {
            return;
        }

        $driver = DB::connection()->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement("UPDATE employees SET department = 'Unassigned' WHERE department IS NULL OR TRIM(department) = ''");
        DB::statement("UPDATE employees SET position = 'Employee' WHERE position IS NULL OR TRIM(position) = ''");
        DB::statement('ALTER TABLE employees MODIFY department VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE employees MODIFY position VARCHAR(255) NOT NULL');
    }
};

