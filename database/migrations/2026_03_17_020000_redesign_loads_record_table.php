<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loads_record', function (Blueprint $table) {
            $table->dropForeign(['loads_upload_id']);
            $table->dropIndex(['loads_upload_id', 'row_no']);
            $table->dropColumn(['loads_upload_id', 'row_no', 'payload']);

            $table->string('source_file_name')->nullable()->after('id');
            $table->string('source_file_path')->nullable()->after('source_file_name');
            $table->string('employee_id')->nullable()->after('source_file_path');
            $table->string('employee_name')->nullable()->after('employee_id');
            $table->string('department')->nullable()->after('employee_name');
            $table->string('position')->nullable()->after('department');
            $table->text('subject_load')->nullable()->after('position');
            $table->string('status_of_employment')->nullable()->after('subject_load');
            $table->string('contact_hours_per_week')->nullable()->after('status_of_employment');
            $table->string('rate_of_salary')->nullable()->after('contact_hours_per_week');
            $table->text('other_benefits')->nullable()->after('rate_of_salary');
            $table->text('relevant_experience')->nullable()->after('other_benefits');

            $table->index(['source_file_path']);
        });
    }

    public function down(): void
    {
        Schema::table('loads_record', function (Blueprint $table) {
            $table->dropIndex(['source_file_path']);
            $table->dropColumn([
                'source_file_name',
                'source_file_path',
                'employee_id',
                'employee_name',
                'department',
                'position',
                'subject_load',
                'status_of_employment',
                'contact_hours_per_week',
                'rate_of_salary',
                'other_benefits',
                'relevant_experience',
            ]);

            $table->foreignId('loads_upload_id')->constrained('loads_upload')->cascadeOnDelete();
            $table->unsignedInteger('row_no');
            $table->json('payload')->nullable();
            $table->index(['loads_upload_id', 'row_no']);
        });
    }
};
