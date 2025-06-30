<?php

namespace App\Livewire\Pages\Admin\ContactUs;

use App\Models\ContactUs;
use Livewire\Component;
use Livewire\WithPagination;

class ContactUsList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMessage = null;
    public $showModal = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showMessage($id)
    {
        $this->selectedMessage = ContactUs::with('user')->findOrFail($id);
        $this->showModal = true;

        if (!$this->selectedMessage->is_read) {
            $this->selectedMessage->is_read = true;
            $this->selectedMessage->save();
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedMessage = null;
    }

    public function render()
    {
        $messages = ContactUs::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('message', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pages.admin.contact-us.contact-us-list', compact('messages'))->layout('layouts.app')->title('Contact Messages');
    }
}
