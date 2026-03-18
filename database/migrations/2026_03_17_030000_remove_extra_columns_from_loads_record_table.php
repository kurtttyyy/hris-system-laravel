<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loads_record', function (Blueprint $table) {
            $table->dropColumn([
                'department',
                'position',
                'subject_load',
                'status_of_employment',
                'contact_hours_per_week',
                'rate_of_salary',
                'other_benefits',
                'relevant_experience',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('loads_record', function (Blueprint $table) {
            $table->string('department')->nullable()->after('employee_name');
            $table->string('position')->nullable()->after('department');
            $table->text('subject_load')->nullable()->after('position');
            $table->string('status_of_employment')->nullable()->after('subject_load');
            $table->string('contact_hours_per_week')->nullable()->after('status_of_employment');
            $table->string('rate_of_salary')->nullable()->after('contact_hours_per_week');
            $table->text('other_benefits')->nullable()->after('rate_of_salary');
            $table->text('relevant_experience')->nullable()->after('other_benefits');
        });
    }
};
