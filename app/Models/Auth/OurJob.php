<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurJob extends Model
{
    protected $table = 'our_jobs_title';
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_job_title', 'our_jobs_title_id', 'user_id');
    }
}
