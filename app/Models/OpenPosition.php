<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpenPosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'department',
        'employment',
        'collage_name',
        'work_mode',
        'job_description',
        'responsibilities',
        'requirements',
        // 'min_salary',
        // 'max_salary',
        'experience_level',
        'location',
        'skills',
        'benifits',
        'job_type',
        'one',
        'two',
        'passionate',
    ];

    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'open_position_id','id');
    }
}
