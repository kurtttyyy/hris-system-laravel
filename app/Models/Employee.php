<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'employement_date',
        'birthday',
        'account_number',
        'sex',
        'civil_status',
        'contact_number',
        'address',
        'department',
        'position',
        'classification',
        'job_type',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
    ];

    protected $casts = [
        'employement_date' => 'date',
        'birthday' => 'date',
    ];

    protected $appends = [
        'formatted_employement_date',
        'formatted_birthday',
    ];

    public function getFormattedEmployementDateAttribute()
    {
        return $this->employement_date ? $this->employement_date->format('F j, Y') : null;
    }

    public function getFormattedBirthdayAttribute()
    {
        return $this->birthday ? $this->birthday->format('F j, Y') : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
