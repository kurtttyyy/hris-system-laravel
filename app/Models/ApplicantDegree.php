<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantDegree extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'degree_level',
        'degree_name',
        'school_name',
        'year_finished',
        'sort_order',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'id');
    }
}
