<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadsRecord extends Model
{
    protected $table = 'loads_record';

    protected $fillable = [
        'employee_name',
        'class_cd',
        'section_cd',
        'code',
        'course_no',
        'subject_name',
        'schedule',
        'units',
        'lec_units',
        'lab_units',
        'hours',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];
}
