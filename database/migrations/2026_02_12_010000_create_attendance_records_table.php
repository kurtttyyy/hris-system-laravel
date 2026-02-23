<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_upload_id')->constrained('attendance_uploads')->cascadeOnDelete();
            $table->string('employee_id');
            $table->date('attendance_date')->nullable();
            $table->string('morning_in')->nullable();
            $table->string('morning_out')->nullable();
            $table->string('afternoon_in')->nullable();
            $table->string('afternoon_out')->nullable();
            $table->unsignedInteger('late_minutes')->default(0);
            $table->json('missing_time_logs')->nullable();
            $table->boolean('is_absent')->default(false);
            $table->boolean('is_tardy')->default(false);
            $table->timestamps();

            $table->index(['attendance_upload_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
