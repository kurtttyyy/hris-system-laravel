<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePositionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'old_position',
        'new_position',
        'old_classification',
        'new_classification',
        'changed_by',
        'changed_at',
        'note',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by', 'id');
    }
}
