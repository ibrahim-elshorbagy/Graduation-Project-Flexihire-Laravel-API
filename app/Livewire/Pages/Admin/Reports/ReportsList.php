<?php

namespace App\Livewire\Pages\Admin\Reports;

use App\Models\Report\Report;
use Livewire\Component;
use Livewire\WithPagination;

class ReportsList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $reports = Report::with(['reporter', 'reportedUser', 'images'])
            ->when($this->search, function ($query) {
                $query->whereHas('reporter', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('reportedUser', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pages.admin.reports.reports-list', compact('reports'))->layout('layouts.app')->title('Reports Management');
    }
}
