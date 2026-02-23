<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'attendance_upload_id',
        'employee_id',
        'employee_name',
        'department',
        'job_type',
        'main_gate',
        'attendance_date',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'late_minutes',
        'missing_time_logs',
        'is_absent',
        'is_tardy',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'late_minutes' => 'integer',
        'missing_time_logs' => 'array',
        'is_absent' => 'boolean',
        'is_tardy' => 'boolean',
    ];
}
