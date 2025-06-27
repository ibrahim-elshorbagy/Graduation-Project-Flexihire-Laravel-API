<div class="flex flex-col p-3 border rounded-md md:p-6 group border-neutral-300 bg-neutral-50 text-neutral-600">
    <!-- Header with title and create button -->
    <div class="flex flex-col justify-between items-center mb-6 md:flex-row">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            User Management
        </h2>
        <div class="mt-4 md:mt-0">
            <x-info-link href="{{ route('admin.users.create') }}">
                Create New User
            </x-info-link>
        </div>
    </div>

    <!-- Search Box and Clear Filters -->
    <div class="mb-4 flex gap-3">
        <div class="flex-1">
            <x-text-input type="text" wire:model.live="search" placeholder="Search users..." class="w-full"/>
        </div>
        @if($search || $roleFilter !== 'all')
            <button wire:click="clearFilters" 
                    class="px-4 py-2 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 hover:text-gray-800 transition-colors">
                Clear Filters
            </button>
        @endif
    </div>

    <!-- Role Filter Tabs -->
    <div class="mb-6">
        <div class="flex gap-2 overflow-x-auto border-b border-neutral-300" role="tablist" aria-label="role filter options">
            <button wire:click="setRoleFilter('all')" 
                    class="inline-flex items-center h-min px-4 py-2 text-sm {{ $roleFilter === 'all' ? 'font-bold text-black border-b-2 border-black' : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900' }}" 
                    type="button" role="tab">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                All Users
            </button>
            <button wire:click="setRoleFilter('user')" 
                    class="inline-flex items-center h-min px-4 py-2 text-sm {{ $roleFilter === 'user' ? 'font-bold text-black border-b-2 border-black' : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900' }}" 
                    type="button" role="tab">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Users
            </button>
            <button wire:click="setRoleFilter('company')" 
                    class="inline-flex items-center h-min px-4 py-2 text-sm {{ $roleFilter === 'company' ? 'font-bold text-black border-b-2 border-black' : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900' }}" 
                    type="button" role="tab">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Companies
            </button>
            <button wire:click="setRoleFilter('admin')" 
                    class="inline-flex items-center h-min px-4 py-2 text-sm {{ $roleFilter === 'admin' ? 'font-bold text-black border-b-2 border-black' : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900' }}" 
                    type="button" role="tab">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Admins
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Results Summary -->
    <div class="mb-4 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Showing {{ $users->count() }} of {{ $users->total() }} 
            @if($roleFilter !== 'all')
                {{ $roleFilter === 'user' ? 'users' : ($roleFilter === 'company' ? 'companies' : 'admins') }}
            @else
                users
            @endif
            @if($search)
                matching "{{ $search }}"
            @endif
        </div>
        <div class="text-sm text-gray-500">
            Total: {{ $users->total() }} {{ $users->total() === 1 ? 'user' : 'users' }}
        </div>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary/10">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $user->image_url ?? 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" alt="{{ $user->first_name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                {{ $user->roles->first()?->name ?? 'No role' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $user->blocked ? 'Blocked' : 'Active' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.users.profile', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                View Profile
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-primary hover:text-primary/70 mr-3">
                                Edit
                            </a>
                            <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" class="text-red-600 hover:text-red-900">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
