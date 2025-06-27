<?php

namespace App\Livewire\Pages\Admin\UserManagement;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UsersList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;
    public $roleFilter = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'roleFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function setRoleFilter($role)
    {
        $this->roleFilter = $role;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->roleFilter = 'all';
        $this->resetPage();
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            session()->flash('message', 'User deleted successfully');
        }
    }

    public function render()
    {
        $users = User::where(function ($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->when($this->roleFilter !== 'all', function ($query) {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', $this->roleFilter);
            });
        })
        ->with('roles')
        ->paginate($this->perPage);

        return view('livewire.pages.admin.user-management.users-list', [
            'users' => $users
        ])->layout('layouts.app')->title('Users List');
    }
}
