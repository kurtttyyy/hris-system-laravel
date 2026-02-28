<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipUpload extends Model
{
    protected $fillable = [
        'original_name',
        'file_path',
        'file_size',
        'status',
        'processed_rows',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function records()
    {
        return $this->hasMany(PayslipRecord::class, 'payslip_upload_id');
    }
}
