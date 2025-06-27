<?php

namespace App\Livewire\Pages\Admin\Reports;

use App\Models\Report\Report;
use App\Models\User;
use Livewire\Component;

class ReportShow extends Component
{
    public $reportId;
    public $report;

    public function mount($id)
    {
        $this->reportId = $id;
        $this->report = Report::with(['reporter', 'reportedUser', 'images'])->findOrFail($id);
    }

    public function toggleUserBlock()
    {
        $user = $this->report->reportedUser;
        $user->blocked = !$user->blocked;
        $user->save();

        $this->report->refresh();
        
        session()->flash('message', $user->blocked ? 'User has been blocked successfully.' : 'User has been unblocked successfully.');
    }

    public function render()
    {
        return view('livewire.pages.admin.reports.report-show')->layout('layouts.app')->title('Report');
    }
}
