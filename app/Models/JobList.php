<?php

namespace App\Models;

use App\Models\User\JobApply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobList extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'skills' => 'array',
        'salary_negotiable' => 'boolean',
        'hiring_multiple_candidates' => 'boolean',
    ];
    public function applies()
    {
        return $this->hasMany(JobApply::class, 'job_id');
    }
}
