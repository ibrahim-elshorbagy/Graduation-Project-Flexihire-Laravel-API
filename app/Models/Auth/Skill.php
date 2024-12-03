<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $gurded = ['id'];
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills');
    }
}
