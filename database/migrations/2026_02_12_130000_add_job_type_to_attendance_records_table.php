<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('attendance_records', 'job_type')) {
            Schema::table('attendance_records', function (Blueprint $table) {
                $table->string('job_type')->nullable()->after('employee_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('attendance_records', 'job_type')) {
            Schema::table('attendance_records', function (Blueprint $table) {
                $table->dropColumn('job_type');
            });
        }
    }
};
