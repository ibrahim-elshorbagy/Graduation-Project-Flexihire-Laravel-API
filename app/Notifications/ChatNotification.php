<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ChatNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;


    public function toArray($notifiable): array
    {
        return $this->broadcastWith();
    }

    public $message;
    public $fromUser;
    public $toUserId;

    public function __construct($message, $fromUser, $toUserId)
    {
        $this->message = $message;
        $this->fromUser = $fromUser;
        $this->toUserId = $toUserId;
    }

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function broadcastOn()
    {
        // Sort the user IDs to make the channel name consistent
        $user1 = min($this->fromUser->id, $this->toUserId);
        $user2 = max($this->fromUser->id, $this->toUserId);
        return new Channel("chat.{$user1}-{$user2}");
    }


    public function broadcastWith(): array
    {
        $timestamp = now();
        return [
            'message' => $this->message,
            'first_name' => $this->fromUser->first_name,
            'last_name' => $this->fromUser->last_name,
            'image' => $this->fromUser->image_url,
            'from_id' => $this->fromUser->id,
            'timestamp' => $timestamp->toIso8601String(), // Use ISO 8601 format for better timestamp handling
        ];
    }


    public function broadcastAs()
    {
        return 'new.message';
    }
}
