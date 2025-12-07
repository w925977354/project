<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                {{ $photo->title }}
            </h2>
            <a href="{{ route('photos.index') }}"
                class="text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                &larr; Back to Gallery
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Photo Display (Main Area) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        {{-- Display photo with uploader's watermark --}}
                        <img src="{{ route('photos.watermarked', $photo) }}" alt="{{ $photo->title }}"
                            class="w-full h-auto object-contain max-h-[70vh]">
                    </div>

                    {{-- Download Button --}}
                    <div class="mt-4">
                        <a href="{{ route('photos.download', $photo) }}"
                            class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-200">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            @auth
                                Download Original (No Watermark)
                            @else
                                Download (With Watermark)
                            @endauth
                        </a>
                    </div>
                </div>

                {{-- Photo Details (Sidebar) --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Info Card --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Photo Details</h3>

                        {{-- Uploader Info --}}
                        <div class="flex items-center mb-4 pb-4 border-b border-gray-200">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($photo->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Uploaded by</p>
                                <p class="font-semibold text-gray-800">{{ $photo->user->name }}</p>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if($photo->description)
                            <div class="mb-4 pb-4 border-b border-gray-200">
                                <p class="text-sm text-gray-500 mb-2">Description</p>
                                <p class="text-gray-700">{{ $photo->description }}</p>
                            </div>
                        @endif

                        {{-- Metadata --}}
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Uploaded</span>
                                <span class="text-gray-800 font-medium">{{ $photo->created_at->diffForHumans() }}</span>
                            </div>
                            @if($photo->created_at != $photo->updated_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Last updated</span>
                                    <span class="text-gray-800 font-medium">{{ $photo->updated_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons (for owner/admin) --}}
                    @can('update', $photo)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>

                            <div class="space-y-3">
                                {{-- Edit Button --}}
                                <a href="{{ route('photos.edit', $photo) }}"
                                    class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg shadow transition duration-200">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit Details
                                </a>

                                {{-- Delete Button --}}
                                @can('delete', $photo)
                                    <form action="{{ route('photos.destroy', $photo) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this photo? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="block w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg shadow transition duration-200">
                                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Delete Photo
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @endcan

                    {{-- Watermark Notice --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Download Info:</strong><br>
                                    • Logged in users get the original image without watermark<br>
                                    • Guest users get a watermarked version with diagonal copyright protection
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>