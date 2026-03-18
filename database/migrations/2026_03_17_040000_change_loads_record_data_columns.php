<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loads_record', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'employee_name']);

            $table->string('class_cd')->nullable()->after('source_file_path');
            $table->string('section_cd')->nullable()->after('class_cd');
            $table->string('code')->nullable()->after('section_cd');
            $table->string('course_no')->nullable()->after('code');
            $table->string('subject_name')->nullable()->after('course_no');
            $table->string('schedule')->nullable()->after('subject_name');
            $table->string('units')->nullable()->after('schedule');
            $table->string('lec_units')->nullable()->after('units');
            $table->string('lab_units')->nullable()->after('lec_units');
            $table->string('hours')->nullable()->after('lab_units');
        });
    }

    public function down(): void
    {
        Schema::table('loads_record', function (Blueprint $table) {
            $table->dropColumn([
                'class_cd',
                'section_cd',
                'code',
                'course_no',
                'subject_name',
                'schedule',
                'units',
                'lec_units',
                'lab_units',
                'hours',
            ]);

            $table->string('employee_id')->nullable()->after('source_file_path');
            $table->string('employee_name')->nullable()->after('employee_id');
        });
    }
};
