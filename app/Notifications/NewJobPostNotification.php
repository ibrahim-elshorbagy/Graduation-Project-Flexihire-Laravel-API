<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\JobList;
use App\Models\User;

class NewJobPostNotification extends Notification
{
    use Queueable;

    protected $job;
    protected $company;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobList $job, User $company)
    {
        $this->job = $job;
        $this->company = $company;
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
            'title' => 'New Job Opportunity',
            'message' => $this->company->first_name . ' ' . $this->company->last_name . ' posted a new job: ' . $this->job->title,
            'job' => $this->job,
            'company' => [
                'id' => $this->company->id,
                'name' => $this->company->first_name . ' ' . $this->company->last_name,
                'image' => $this->company->image_url,
            ],
            'type' => 'new_job_post',
        ];
    }
}
