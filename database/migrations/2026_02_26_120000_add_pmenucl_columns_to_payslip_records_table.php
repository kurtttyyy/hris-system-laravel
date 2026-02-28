<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payslip_records', function (Blueprint $table) {
            $table->unsignedInteger('row_no')->nullable()->after('employee_name');
            $table->decimal('basic_salary', 12, 2)->nullable()->after('row_no');
            $table->decimal('living_allowance', 12, 2)->nullable()->after('basic_salary');
            $table->decimal('extra_load', 12, 2)->nullable()->after('living_allowance');
            $table->decimal('other_income', 12, 2)->nullable()->after('extra_load');
            $table->string('absences_date')->nullable()->after('other_income');
            $table->decimal('absences_amount', 12, 2)->nullable()->after('absences_date');
            $table->decimal('withholding_tax', 12, 2)->nullable()->after('absences_amount');
            $table->decimal('salary_vale', 12, 2)->nullable()->after('withholding_tax');
            $table->decimal('pag_ibig_loan', 12, 2)->nullable()->after('salary_vale');
            $table->decimal('pag_ibig_premium', 12, 2)->nullable()->after('pag_ibig_loan');
            $table->decimal('sss_loan', 12, 2)->nullable()->after('pag_ibig_premium');
            $table->decimal('sss_premium', 12, 2)->nullable()->after('sss_loan');
            $table->decimal('peraa_loan', 12, 2)->nullable()->after('sss_premium');
            $table->decimal('peraa_premium', 12, 2)->nullable()->after('peraa_loan');
            $table->decimal('philhealth_premium', 12, 2)->nullable()->after('peraa_premium');
            $table->decimal('other_deduction', 12, 2)->nullable()->after('philhealth_premium');
            $table->decimal('amount_due', 12, 2)->nullable()->after('other_deduction');
            $table->string('account_credited')->nullable()->after('amount_due');
        });
    }

    public function down(): void
    {
        Schema::table('payslip_records', function (Blueprint $table) {
            $table->dropColumn([
                'row_no',
                'basic_salary',
                'living_allowance',
                'extra_load',
                'other_income',
                'absences_date',
                'absences_amount',
                'withholding_tax',
                'salary_vale',
                'pag_ibig_loan',
                'pag_ibig_premium',
                'sss_loan',
                'sss_premium',
                'peraa_loan',
                'peraa_premium',
                'philhealth_premium',
                'other_deduction',
                'amount_due',
                'account_credited',
            ]);
        });
    }
};

