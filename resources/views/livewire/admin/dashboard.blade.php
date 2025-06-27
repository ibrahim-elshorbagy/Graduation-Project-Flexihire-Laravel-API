<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
            <p class="text-gray-600">Welcome back! Here's what's happening with your platform.</p>
        </div>

        <!-- Statistics Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Users</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalUsers) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Total Companies Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-sm font-medium">Companies</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalCompanies) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Total Jobs Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Job Posts</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalJobs) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Total Applications Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Applications</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalApplications) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Active Users Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 to-green-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Active Users</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($activeUsers) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Blocked Users Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-500 to-red-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Blocked Users</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($blockedUsers) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636A9 9 0 005.636 18.364"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Total Reviews Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-500 to-yellow-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Reviews</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalReviews) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Total Reports Card -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-pink-500 to-pink-600 p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm font-medium">Reports</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalReports) }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>
        </div>

        <!-- Data Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Recent Users -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Recent Users</h3>
                    <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <img class="h-10 w-10 rounded-full" 
                                 src="{{ $user->image_url ?? 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" 
                                 alt="{{ $user->first_name }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-800' : ($user->hasRole('company') ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($user->roles->first()?->name ?? 'User') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No users found</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Companies -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Top Companies</h3>
                    <a href="{{ route('admin.users') }}?roleFilter=company" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse($topCompanies as $company)
                        <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <img class="h-10 w-10 rounded-full" 
                                 src="{{ $company->image_url ?? 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" 
                                 alt="{{ $company->first_name }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $company->first_name }} {{ $company->last_name }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $company->job_list_count }} jobs posted</p>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-sm font-medium text-emerald-600">{{ $company->job_list_count }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No companies found</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Jobs -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Recent Jobs</h3>
                    <span class="text-purple-600 text-sm font-medium">Latest Posts</span>
                </div>
                <div class="space-y-4">
                    @forelse($recentJobs as $job)
                        <div class="p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $job->title }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        by {{ $job->user->first_name }} {{ $job->user->last_name }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $job->date_posted ? \Carbon\Carbon::parse($job->date_posted)->diffForHumans() : 'No date' }}
                                    </p>
                                </div>
                                @if($job->location)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                                        {{ $job->location }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No jobs found</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.users.create') }}" 
                   class="flex items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                    <div class="bg-blue-500 p-2 rounded-lg group-hover:bg-blue-600 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Create User</p>
                        <p class="text-xs text-gray-500">Add new user</p>
                    </div>
                </a>

                <a href="{{ route('admin.users') }}" 
                   class="flex items-center p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors group">
                    <div class="bg-emerald-500 p-2 rounded-lg group-hover:bg-emerald-600 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Manage Users</p>
                        <p class="text-xs text-gray-500">View all users</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports') }}" 
                   class="flex items-center p-4 bg-red-50 rounded-xl hover:bg-red-100 transition-colors group">
                    <div class="bg-red-500 p-2 rounded-lg group-hover:bg-red-600 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">View Reports</p>
                        <p class="text-xs text-gray-500">Check reports</p>
                    </div>
                </a>

                <button wire:click="loadStatistics" 
                        class="flex items-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors group">
                    <div class="bg-purple-500 p-2 rounded-lg group-hover:bg-purple-600 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Refresh Data</p>
                        <p class="text-xs text-gray-500">Update stats</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
