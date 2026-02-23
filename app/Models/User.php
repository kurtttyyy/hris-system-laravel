<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'role',
        'job_role',
        'status',
        'account_status',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function applicant(){
        return $this->hasOne(Applicant::class, 'user_id', 'id')
                    ->whereRaw('LOWER(application_status) = ?', ['hired']);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', '!=', 'Employee');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('F j, Y') : null;
    }



    public function getInitialsAttribute()
    {
        return strtoupper(
            substr($this->first_name, 0, 1) .
            substr($this->last_name, 0, 1)
        );
    }

    public function employee(){
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

    public function education(){
        return $this->hasOne(Education::class, 'user_id', 'id');
    }

    public function government(){
        return $this->hasOne(Government::class, 'user_id', 'id');
    }

    public function license(){
        return $this->hasOne(License::class, 'user_id', 'id');
    }

    public function salary(){
        return $this->hasOne(Salary::class, 'user_id', 'id');
    }
}
