<?php

namespace App\Models\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the follower user
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Get the company being followed
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
