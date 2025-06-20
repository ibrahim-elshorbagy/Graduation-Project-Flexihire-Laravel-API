<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Review;

class NewReviewNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $review;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, Review $review)
    {
        $this->user = $user;
        $this->review = $review;
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
            'title' => 'New Company Review',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' has left a review for your company',
            'rating' => $this->review->rating,
            'review_id' => $this->review->id,
            'user' => $this->user,
            'type' => 'new_review',
        ];
    }
}
