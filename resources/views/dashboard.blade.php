<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Message --}}
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h3>
                <p class="text-gray-600">
                    Here's an overview of your photo gallery activity.
                </p>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Total Photos --}}
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Photos</p>
                            <p class="text-3xl font-bold text-gray-900">{{ Auth::user()->photos()->count() }}</p>
                        </div>
                    </div>
                </div>

                {{-- Account Type --}}
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Account Type</p>
                            <p class="text-2xl font-bold text-gray-900">
                                @if(Auth::user()->is_admin)
                                    <span class="text-green-600">Administrator</span>
                                @else
                                    <span class="text-gray-700">Regular User</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Member Since --}}
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Member Since</p>
                            <p class="text-xl font-bold text-gray-900">{{ Auth::user()->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Photos --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Your Recent Photos</h3>
                    <a href="{{ route('photos.index') }}"
                        class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                        View All →
                    </a>
                </div>

                @php
                    $recentPhotos = Auth::user()->photos()->latest()->take(6)->get();
                @endphp

                @if($recentPhotos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach($recentPhotos as $photo)
                            <a href="{{ route('photos.show', $photo) }}" class="group">
                                <div
                                    class="aspect-square rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-200">
                                    <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-300">
                                </div>
                                <p class="mt-2 text-sm font-medium text-gray-700 truncate">{{ $photo->title }}</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No photos yet</h3>
                        <p class="text-gray-500 mb-4">Start building your gallery by uploading your first photo!</p>
                        <a href="{{ route('photos.create') }}"
                            class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                            Upload Your First Photo
                        </a>
                    </div>
                @endif
            </div>

            {{-- Admin Panel (only for administrators) --}}
            @if(Auth::user()->is_admin)
                <div
                    class="mt-8 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Administrator Privileges</h3>
                            <p class="text-gray-700 mb-4">
                                As an administrator, you have special permissions to moderate content and delete any photo
                                for content moderation purposes.
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('photos.index') }}"
                                    class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Moderate Gallery
                                </a>
                                <span
                                    class="inline-flex items-center bg-white text-gray-700 font-medium py-2 px-4 rounded-lg shadow">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Total Users: {{ \App\Models\User::count() }}
                                </span>
                                <span
                                    class="inline-flex items-center bg-white text-gray-700 font-medium py-2 px-4 rounded-lg shadow">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Total Photos: {{ \App\Models\Photo::count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('photos.create') }}"
                    class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition duration-200 group">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-indigo-100 rounded-full p-4 group-hover:bg-indigo-200 transition duration-200">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4
                                class="text-lg font-bold text-gray-800 group-hover:text-indigo-600 transition duration-200">
                                Upload New Photo</h4>
                            <p class="text-gray-600 text-sm">Share your amazing photos with the community</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('photos.index') }}"
                    class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition duration-200 group">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-purple-100 rounded-full p-4 group-hover:bg-purple-200 transition duration-200">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4
                                class="text-lg font-bold text-gray-800 group-hover:text-purple-600 transition duration-200">
                                Browse Gallery</h4>
                            <p class="text-gray-600 text-sm">Explore all photos from the community</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>