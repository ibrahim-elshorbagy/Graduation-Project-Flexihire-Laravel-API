<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use App\Models\JobList;

class JobStatusNotification extends Notification
{
    use Queueable;

    protected $jobTitle;
    protected $status;
    protected $jobId;
    protected $job;

    public function __construct($jobTitle, $status, $jobId)
    {
        $this->jobTitle = $jobTitle;
        $this->status = $status;
        $this->jobId = $jobId;
        $this->job = JobList::findOrFail($jobId);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusMessage = $this->status === 'approved'
            ? 'Congratulations! Your application has been approved'
            : 'Your application has been ' . $this->status;

        return [
            'title' => 'Job Application Update',
            'message' => $statusMessage . ' for job: ' . $this->jobTitle,
            'job' => $this->job,
            'type' => 'job_status',
            'status' => $this->status
        ];
    }
}
