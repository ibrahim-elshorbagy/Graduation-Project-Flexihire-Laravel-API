<div class="flex flex-col p-3 border rounded-md md:p-6 group border-neutral-300 bg-neutral-50 text-neutral-600">
    <!-- Header with title and back button -->
    <div class="flex flex-col items-center justify-between mb-6 md:flex-row">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            User Profile - {{ $user->first_name }} {{ $user->last_name }}
        </h2>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <x-info-link href="{{ route('admin.users') }}">
                Back To Users
            </x-info-link>
            <!-- Block/Unblock Button -->
            <button wire:click="toggleBlockUser" 
                    wire:confirm="Are you sure you want to {{ $user->blocked ? 'unblock' : 'block' }} this user?"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border {{ $user->blocked ? 'border-green-300 text-green-700 bg-green-50 hover:bg-green-100' : 'border-red-300 text-red-700 bg-red-50 hover:bg-red-100' }}">
                {{ $user->blocked ? 'Unblock User' : 'Block User' }}
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- User Status Banner -->
    @if($user->blocked)
        <div class="mb-6 p-4 text-sm text-red-700 bg-red-100 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <strong>This user is currently blocked</strong>
            </div>
        </div>
    @endif

    <!-- User Information Card -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="flex items-start space-x-6">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <img class="h-24 w-24 rounded-full object-cover" 
                         src="{{ $user->image_url ?? 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" 
                         alt="{{ $user->first_name }} {{ $user->last_name }}">
                </div>
                
                <!-- User Details -->
                <div class="flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-800' : ($user->hasRole('company') ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ ucfirst($user->roles->first()?->name ?? 'No role') }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Status</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Verified
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Not Verified
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $user->blocked ? 'Blocked' : 'Active' }}
                                        </span>
                                    </dd>
                                </div>
                                @if($user->location)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->location }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($user->description)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Description</h4>
                            <p class="text-sm text-gray-900">{{ $user->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Role-specific Content -->
    @if($user->hasRole('company'))
        <!-- Company Jobs Section -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Company Jobs ({{ $companyJobs->count() }})</h3>
                
                @if($companyJobs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-primary/10">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Job Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Applications
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Salary
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($companyJobs as $job)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $job->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($job->description, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $job->location }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $job->applies->count() }} applications
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($job->salary_negotiable)
                                                Negotiable
                                            @else
                                                ${{ number_format($job->min_salary) }} - ${{ number_format($job->max_salary) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No jobs posted yet.</p>
                @endif
            </div>
        </div>

    @elseif($user->hasRole('user'))
        <!-- User CV and Job Applications Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- CV Section -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">CV/Resume</h3>
                        @if($user->cv)
                            <button wire:click="downloadCV" 
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download CV
                            </button>
                        @endif
                    </div>
                    
                    @if($user->cv)
                        <div class="flex items-center p-4 bg-green-50 rounded-lg">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-green-900">CV Available</p>
                                <p class="text-sm text-green-700">Click download to view the user's CV</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">No CV Available</p>
                                <p class="text-sm text-gray-700">User hasn't uploaded a CV yet</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Job Applications Section -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Job Applications ({{ $userJobs->count() }})</h3>
                    
                    @if($userJobs->count() > 0)
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($userJobs as $application)
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $application?->job?->title }}
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Applied on {{ $application?->created_at }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Status: 
                                                <span class="font-medium {{ $application->status === 'accepted' ? 'text-green-600' : ($application->status === 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                                    {{ ucfirst($application?->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No job applications yet.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Reviews Section (if applicable) -->
    @if($user->hasRole('company') && $user->receivedReviews->count() > 0)
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Company Reviews ({{ $user->receivedReviews->count() }})</h3>
                
                <div class="space-y-4 max-h-64 overflow-y-auto">
                    @foreach($user->receivedReviews->take(5) as $review)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0">
                            <div class="flex items-start space-x-3">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-900 mt-1">{{ $review->review }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
