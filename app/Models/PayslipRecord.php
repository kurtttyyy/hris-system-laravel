<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipRecord extends Model
{
    protected $fillable = [
        'payslip_upload_id',
        'user_id',
        'employee_id',
        'employee_name',
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
        'pay_date_text',
        'pay_date',
        'total_salary',
        'total_deduction',
        'net_pay',
        'payload',
        'scanned_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'scanned_at' => 'datetime',
        'pay_date' => 'date',
        'row_no' => 'integer',
        'basic_salary' => 'decimal:2',
        'living_allowance' => 'decimal:2',
        'extra_load' => 'decimal:2',
        'other_income' => 'decimal:2',
        'absences_amount' => 'decimal:2',
        'withholding_tax' => 'decimal:2',
        'salary_vale' => 'decimal:2',
        'pag_ibig_loan' => 'decimal:2',
        'pag_ibig_premium' => 'decimal:2',
        'sss_loan' => 'decimal:2',
        'sss_premium' => 'decimal:2',
        'peraa_loan' => 'decimal:2',
        'peraa_premium' => 'decimal:2',
        'philhealth_premium' => 'decimal:2',
        'other_deduction' => 'decimal:2',
        'amount_due' => 'decimal:2',
        'total_salary' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function upload()
    {
        return $this->belongsTo(PayslipUpload::class, 'payslip_upload_id');
    }
}
