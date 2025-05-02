<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Sender relationship (a message belongs to a sender)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Receiver relationship (a message belongs to a receiver)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
