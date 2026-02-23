<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('attendance_records', 'department')) {
            Schema::table('attendance_records', function (Blueprint $table) {
                $table->string('department')->nullable()->after('employee_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('attendance_records', 'department')) {
            Schema::table('attendance_records', function (Blueprint $table) {
                $table->dropColumn('department');
            });
        }
    }
};
