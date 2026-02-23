<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'education_attainment',
        'field_study',
        'skills_n_expertise',
        'open_position_id',
        'application_status',
        'user_id',
        'university_name',
        'university_address',
        'year_complete',
        'work_position',
        'work_employer',
        'work_location',
        'work_duration',
        'starRatings',
        'experience_years',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if your column name differs
    }

    public function position(){
        return $this->belongsTo(OpenPosition::class, 'open_position_id', 'id');
    }

    public function documents(){
        return $this->hasMany(ApplicantDocument::class, 'applicant_id', 'id');
    }

    protected $casts = [
        'date_hired' => 'date',
        'starRatings' => 'integer',
    ];

    protected $appends = [
        'formatted_date_hired',
    ];

    public function getFormattedDateHiredAttribute()
    {
        return $this->date_hired
            ? $this->date_hired->format('F j, Y')
            : '';
    }
}
