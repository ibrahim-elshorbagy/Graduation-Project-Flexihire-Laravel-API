<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ChatBellNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $sender;
    protected $chatId;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, User $sender, $chatId)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->chatId = $chatId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Message',
            'message' => 'You received a new message from ' . $this->sender->first_name . ' ' . $this->sender->last_name,
            'message_preview' => substr($this->message, 0, 50) . (strlen($this->message) > 50 ? '...' : ''),
            'sender' => $this->sender,
            'type' => 'chat_message',
        ];
    }
}
