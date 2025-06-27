<div class="flex flex-col p-3 border rounded-md md:p-6 group border-neutral-300 bg-neutral-50 text-neutral-600">
    <!-- Header with title and back button -->
    <div class="flex flex-col items-center justify-between mb-6 md:flex-row">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            {{ $userId ? 'Edit User' : 'Create New User' }}
        </h2>
        <div class="mt-4 md:mt-0">
            <x-info-link href="{{ route('admin.users') }}">
                Back To Users
            </x-info-link>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow">
        <form wire:submit.prevent="saveUser" class="p-6 space-y-6">
            <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <!-- Email -->
                <div class="sm:col-span-2">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="email" type="email" class="block w-full mt-1" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- First Name -->
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input wire:model="first_name" id="first_name" type="text" class="block w-full mt-1" required />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <!-- Last Name -->
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input wire:model="last_name" id="last_name" type="text" class="block w-full mt-1" required />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input wire:model="password" id="password" type="password" class="block w-full mt-1" :required="!$userId" />
                    @if($userId)
                        <div class="mt-1 text-sm text-gray-500">Leave blank to keep current password</div>
                    @endif
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Password Confirmation -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input wire:model="password_confirmation" id="password_confirmation" type="password" class="block w-full mt-1" :required="!$userId" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Role Selection -->
                <div>
                    <x-input-label for="selectedRole" :value="__('Role')" />
                    <select wire:model="selectedRole" id="selectedRole" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('selectedRole')" class="mt-2" />
                </div>

                <!-- Blocked Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="blocked" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-600">Block this user</span>
                    </label>
                    <div class="mt-1 text-sm text-gray-500">Blocked users cannot access the system</div>
                </div>
            </div>


            <div class="flex justify-end space-x-3">
                <x-danger-button type="button" onclick="window.location='{{ route('admin.users') }}'">
                    Cancel
                </x-danger-button>
                <x-primary-button type="submit">
                    {{ $userId ? 'Update User' : 'Create User' }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
