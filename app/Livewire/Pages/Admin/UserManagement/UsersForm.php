<?php

namespace App\Livewire\Pages\Admin\UserManagement;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersForm extends Component
{
    public $userId = null;
    public $email;
    public $first_name;
    public $last_name;
    public $password;
    public $password_confirmation;
    public $selectedRole = '';
    public $permissions = [];

    public function mount($userId = null)
    {
        if ($userId) {
            $this->userId = $userId;
            $user = User::findOrFail($userId);
            $this->email = $user->email;
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
            $this->selectedRole = $user->roles->first()?->name ?? '';
            $this->permissions = $user->permissions->pluck('name')->toArray();
        } else {
            // Set default role if exists
            $defaultRole = Role::where('name', 'user')->first();
            if ($defaultRole) {
                $this->selectedRole = $defaultRole->name;
            }
        }
    }

    public function rules()
    {
        $rules = [
            'email' => ['required', 'email'],
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'selectedRole' => 'required',
            'permissions' => 'array'
        ];

        if ($this->userId) {
            // Edit mode
            $rules['email'][] = Rule::unique('users')->ignore($this->userId);
            $rules['password'] = 'nullable|min:8|confirmed';
        } else {
            // Create mode
            $rules['email'][] = 'unique:users';
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }

    public function saveUser()
    {
        $this->validate();

        $userData = [
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            // Update existing user
            $user = User::findOrFail($this->userId);
            $user->update($userData);

            // Update role
            $oldRole = $user->roles->first()?->name;
            if ($oldRole != $this->selectedRole) {
                $user->syncRoles([$this->selectedRole]);
            }

            // Update permissions
            $user->syncPermissions($this->permissions);

            session()->flash('message', 'User updated successfully.');
        } else {
            // Create new user
            $userData['image_url'] = 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
            $userData['email_verified_at'] = now();

            $user = User::create($userData);
            $user->assignRole($this->selectedRole);

            // Assign selected permissions
            if (!empty($this->permissions)) {
                $user->syncPermissions($this->permissions);
            }

            session()->flash('message', 'User created successfully.');
        }

        return redirect()->route('admin.users');
    }

    public function render()
    {
        return view('livewire.pages.admin.user-management.users-form', [
            'roles' => Role::all(),
            'allPermissions' => Permission::all()
        ])->layout('layouts.app')->title('Edit');
    }
}
