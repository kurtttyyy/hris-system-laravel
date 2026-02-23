<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('employee_id')->nullable();

            $table->string('office_department')->nullable();
            $table->string('employee_name')->nullable();
            $table->date('filing_date')->nullable();
            $table->string('position')->nullable();
            $table->string('salary')->nullable();

            $table->string('leave_type')->nullable();
            $table->decimal('number_of_working_days', 8, 1)->default(0);
            $table->string('inclusive_dates')->nullable();
            $table->string('as_of_label')->nullable();
            $table->string('earned_date_label')->nullable();

            $table->decimal('beginning_vacation', 8, 1)->default(0);
            $table->decimal('beginning_sick', 8, 1)->default(0);
            $table->decimal('beginning_total', 8, 1)->default(0);

            $table->decimal('earned_vacation', 8, 1)->default(0);
            $table->decimal('earned_sick', 8, 1)->default(0);
            $table->decimal('earned_total', 8, 1)->default(0);

            $table->decimal('applied_vacation', 8, 1)->default(0);
            $table->decimal('applied_sick', 8, 1)->default(0);
            $table->decimal('applied_total', 8, 1)->default(0);

            $table->decimal('ending_vacation', 8, 1)->default(0);
            $table->decimal('ending_sick', 8, 1)->default(0);
            $table->decimal('ending_total', 8, 1)->default(0);

            $table->decimal('days_with_pay', 8, 1)->default(0);
            $table->decimal('days_without_pay', 8, 1)->default(0);
            $table->string('commutation')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};

