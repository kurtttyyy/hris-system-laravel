<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_position_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_position_histories', 'old_department')) {
                $table->string('old_department')->nullable()->after('new_classification');
            }
            if (!Schema::hasColumn('employee_position_histories', 'new_department')) {
                $table->string('new_department')->nullable()->after('old_department');
            }
            if (!Schema::hasColumn('employee_position_histories', 'old_salary')) {
                $table->string('old_salary')->nullable()->after('new_department');
            }
            if (!Schema::hasColumn('employee_position_histories', 'new_salary')) {
                $table->string('new_salary')->nullable()->after('old_salary');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employee_position_histories', function (Blueprint $table) {
            if (Schema::hasColumn('employee_position_histories', 'new_salary')) {
                $table->dropColumn('new_salary');
            }
            if (Schema::hasColumn('employee_position_histories', 'old_salary')) {
                $table->dropColumn('old_salary');
            }
            if (Schema::hasColumn('employee_position_histories', 'new_department')) {
                $table->dropColumn('new_department');
            }
            if (Schema::hasColumn('employee_position_histories', 'old_department')) {
                $table->dropColumn('old_department');
            }
        });
    }
};
