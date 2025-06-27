<?php

namespace App\Livewire\Pages\Admin\UserManagement;

use App\Models\User;
use App\Models\JobList;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Component
{
    public $userId;
    public $user;
    public $userJobs = [];
    public $companyJobs = [];
    
    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::with(['roles', 'jobApplications.job', 'JobList', 'writtenReviews', 'receivedReviews'])
            ->findOrFail($userId);
        
        // Load company jobs if user is a company
        if ($this->user->hasRole('company')) {
            $this->companyJobs = $this->user->JobList()->with('applies')->get();
        }
        
        // Load user job applications if user is a regular user
        if ($this->user->hasRole('user')) {
            $this->userJobs = $this->user->jobApplications()->with('job')->get();
        }
    }

    public function toggleBlockUser()
    {
        $this->user->update([
            'blocked' => !$this->user->blocked
        ]);
        
        $status = $this->user->blocked ? 'blocked' : 'unblocked';
        session()->flash('message', "User has been {$status} successfully.");
        
        // Refresh the user data
        $this->user = $this->user->fresh();
    }

    public function downloadCV()
    {
        if ($this->user->cv && Storage::exists($this->user->cv)) {
            return Storage::download($this->user->cv);
        }
        
        session()->flash('error', 'CV file not found.');
    }

    public function render()
    {
        return view('livewire.pages.admin.user-management.user-profile')->layout('layouts.app')->title('profile');;
    }
}
