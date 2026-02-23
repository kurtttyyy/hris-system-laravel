<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceUpload extends Model
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
        return $this->hasMany(AttendanceRecord::class);
    }
}
