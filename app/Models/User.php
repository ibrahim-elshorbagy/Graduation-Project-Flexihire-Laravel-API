<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Auth\OurJob;
use App\Models\Auth\Skill;
use App\Models\User\JobApply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPasswordNotification;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'image_url',
        'background_url',
        'cv',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token, $this->email));
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills');
    }

    // Define relationship with jobs
    public function jobs()
    {
        return $this->belongsToMany(OurJob::class, 'user_job_title', 'user_id', 'our_jobs_title_id');
    }

    // for company to show it's jobs
    public function JobList()
    {
        return $this->hasMany(JobList::class);
    }

    // for user to show his jobs
    public function JobApply()
    {
        return $this->hasMany(JobApply::class);
    }
}
