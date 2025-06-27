<div class="flex flex-col p-3 border rounded-md md:p-6 group border-neutral-300 bg-neutral-50 text-neutral-600">
    <!-- Header with title and back button -->
    <div class="flex flex-col items-center justify-between mb-6 md:flex-row">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Report Details
        </h2>
        <div class="mt-4 md:mt-0">
            <x-info-link href="{{ route('admin.reports') }}">
                Back To Reports
            </x-info-link>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Report Details Card -->
    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        <!-- Reporter and Reported User Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Reporter -->
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Reporter</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-16 w-16 rounded-full" src="{{ $report->reporter->image_url ?? 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" alt="{{ $report->reporter->first_name }}">
                    </div>
                    <div>
                        <div class="text-lg font-medium text-gray-900">
                            {{ $report->reporter->first_name }} {{ $report->reporter->last_name }}
                        </div>
                        <div class="text-sm text-gray-500">{{ $report->reporter->email }}</div>
                        <div class="text-sm text-gray-500">
                            Role: {{ $report->reporter->roles->first()?->name ?? 'No role' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reported User -->
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Reported User</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-16 w-16 rounded-full" src="{{ $report->reportedUser->image_url ?? 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" alt="{{ $report->reportedUser->first_name }}">
                    </div>
                    <div>
                        <div class="text-lg font-medium text-gray-900">
                            {{ $report->reportedUser->first_name }} {{ $report->reportedUser->last_name }}
                        </div>
                        <div class="text-sm text-gray-500">{{ $report->reportedUser->email }}</div>
                        <div class="text-sm text-gray-500">
                            Role: {{ $report->reportedUser->roles->first()?->name ?? 'No role' }}
                        </div>
                        <div class="mt-2">
                            @if($report->reportedUser->blocked)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Blocked
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Reason -->
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Report Reason</h3>
            <div class="text-gray-700">
                {{ $report->reason ?: 'No reason provided' }}
            </div>
        </div>

        <!-- Report Images -->
        @if($report->images->count() > 0)
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Attached Images</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($report->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Report Image" class="w-full h-48 object-cover rounded-lg border">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Report Metadata -->
        <div class="border rounded-lg p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Report Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">Report ID:</span>
                    <span class="text-sm text-gray-900">{{ $report->id }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Created At:</span>
                    <span class="text-sm text-gray-900">{{ $report->created_at->format('M d, Y H:i:s') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <x-danger-button type="button" onclick="window.location='{{ route('admin.reports') }}'">
                Cancel
            </x-danger-button>
            <x-primary-button wire:click="toggleUserBlock" wire:confirm="Are you sure you want to {{ $report->reportedUser->blocked ? 'unblock' : 'block' }} this user?">
                {{ $report->reportedUser->blocked ? 'Unblock User' : 'Block User' }}
            </x-primary-button>
        </div>
    </div>
</div>
