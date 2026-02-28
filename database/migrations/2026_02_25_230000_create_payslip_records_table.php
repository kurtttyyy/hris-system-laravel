<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payslip_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payslip_upload_id')->constrained('payslip_uploads')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_id');
            $table->string('employee_name')->nullable();
            $table->string('pay_date_text')->nullable();
            $table->decimal('total_salary', 12, 2)->nullable();
            $table->decimal('total_deduction', 12, 2)->nullable();
            $table->decimal('net_pay', 12, 2)->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();

            $table->index(['payslip_upload_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslip_records');
    }
};

