<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>
            <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                Administrator
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                {{-- Total Users --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Total Photos --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Photos</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_photos'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Total Admins --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Administrators</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_admins'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Photos Today --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Photos Today</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['photos_today'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Users Today --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Users Today</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['users_today'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('admin.users') }}"
                    class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Manage Users</h3>
                            <p class="text-sm text-gray-500 mt-1">View, create, edit, and delete users</p>
                        </div>
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('admin.photos') }}"
                    class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Manage Photos</h3>
                            <p class="text-sm text-gray-500 mt-1">View, edit, and delete all photos</p>
                        </div>
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Recent Photos --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Photos</h3>
                    <div class="space-y-4">
                        @forelse($recent_photos as $photo)
                            <div class="flex items-center space-x-4">
                                <img src="{{ route('photos.watermarked', $photo) }}" alt="{{ $photo->title }}"
                                    class="h-16 w-16 rounded-lg object-cover">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $photo->title }}</p>
                                    <p class="text-sm text-gray-500">by {{ $photo->user->name }}</p>
                                </div>
                                <a href="{{ route('admin.photos.edit', $photo) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No photos yet</p>
                        @endforelse
                    </div>
                </div>

                {{-- Top Uploaders --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Uploaders</h3>
                    <div class="space-y-4">
                        @forelse($top_uploaders as $uploader)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($uploader->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $uploader->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $uploader->email }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-indigo-600">
                                    {{ $uploader->photos_count }} photos
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No users yet</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>