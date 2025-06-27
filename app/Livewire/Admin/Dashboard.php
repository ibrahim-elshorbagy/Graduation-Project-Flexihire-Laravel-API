<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\JobList;
use App\Models\Review;
use App\Models\User\JobApply;
use App\Models\Report\Report;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Dashboard extends Component
{
    public $totalUsers = 0;
    public $totalCompanies = 0;
    public $totalAdmins = 0;
    public $totalJobs = 0;
    public $totalApplications = 0;
    public $totalReviews = 0;
    public $totalReports = 0;
    public $blockedUsers = 0;
    public $activeUsers = 0;
    public $recentUsers = [];
    public $topCompanies = [];
    public $recentJobs = [];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // User statistics
        $this->totalUsers = User::role('user')->count();
        $this->totalCompanies = User::role('company')->count();
        $this->totalAdmins = User::role('admin')->count();
        $this->blockedUsers = User::where('blocked', true)->count();
        $this->activeUsers = User::where('blocked', false)->count();

        // Job statistics
        $this->totalJobs = JobList::count();
        $this->totalApplications = JobApply::count();

        // Review and report statistics
        $this->totalReviews = Review::count();
        $this->totalReports = Report::count();

        // Recent data
        $this->recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        $this->topCompanies = User::role('company')
            ->withCount('JobList')
            ->orderBy('job_list_count', 'desc')
            ->take(5)
            ->get();

        $this->recentJobs = JobList::with('user')
            ->orderBy('date_posted', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.app')->title('Dashboard');
    }
}
