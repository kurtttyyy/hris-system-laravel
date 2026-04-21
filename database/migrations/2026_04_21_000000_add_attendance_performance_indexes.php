<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('attendance_records')) {
            Schema::table('attendance_records', function (Blueprint $table) {
                $table->index('attendance_date', 'attendance_records_attendance_date_idx');
                $table->index(['attendance_date', 'employee_id'], 'attendance_records_date_employee_idx');
                $table->index(['attendance_upload_id', 'attendance_date'], 'attendance_records_upload_date_idx');
            });
        }

        if (Schema::hasTable('attendance_uploads')) {
            Schema::table('attendance_uploads', function (Blueprint $table) {
                $table->index('uploaded_at', 'attendance_uploads_uploaded_at_idx');
                $table->index('status', 'attendance_uploads_status_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendance_records')) {
            Schema::table('attendance_records', function (Blueprint $table) {
                $table->dropIndex('attendance_records_attendance_date_idx');
                $table->dropIndex('attendance_records_date_employee_idx');
                $table->dropIndex('attendance_records_upload_date_idx');
            });
        }

        if (Schema::hasTable('attendance_uploads')) {
            Schema::table('attendance_uploads', function (Blueprint $table) {
                $table->dropIndex('attendance_uploads_uploaded_at_idx');
                $table->dropIndex('attendance_uploads_status_idx');
            });
        }
    }
};
