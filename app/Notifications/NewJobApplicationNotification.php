<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\JobList;

class NewJobApplicationNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $job;
    protected $proposal;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, JobList $job, $proposal)
    {
        $this->user = $user;
        $this->job = $job;
        $this->proposal = $proposal;
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
            'title' => 'New Job Application',
            'message' => 'A new application has been submitted for your job: ' . $this->job->title,
            'user' => $this->user,
            'job' => $this->job,
            'proposal_preview' => substr($this->proposal, 0, 100) . (strlen($this->proposal) > 100 ? '...' : ''),
            'type' => 'new_application',
        ];
    }
}
