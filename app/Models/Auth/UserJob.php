<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJob extends Model
{
    protected $table = 'user_job_title';
    use HasFactory;
}
