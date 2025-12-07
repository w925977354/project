<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                {{ __('Photo Gallery') }}
            </h2>

            {{-- Upload button (visible only for authenticated users) --}}
            @auth
                <a href="{{ route('photos.create') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Upload Photo
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div
                    class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Photo Grid --}}
            @if($photos->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($photos as $photo)
                        <div
                            class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2 group">
                            {{-- Photo Image --}}
                            <div class="relative overflow-hidden aspect-square">
                                <a href="{{ route('photos.show', $photo) }}">
                                    {{-- Display photo with uploader's watermark --}}
                                    <img src="{{ route('photos.watermarked', $photo) }}" alt="{{ $photo->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                                        loading="lazy">
                                </a>

                                {{-- Uploader badge in top-right corner --}}
                                <div
                                    class="absolute top-2 right-2 bg-indigo-600 bg-opacity-90 text-white text-xs px-3 py-1 rounded-full">
                                    © {{ $photo->user->name }}
                                </div>

                                {{-- Admin/Owner indicator --}}
                                @auth
                                    @can('delete', $photo)
                                        <div
                                            class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded-full">
                                            @if(auth()->user()->is_admin)
                                                Admin
                                            @else
                                                Your Photo
                                            @endif
                                        </div>
                                    @endcan
                                @endauth
                            </div>

                            {{-- Photo Info --}}
                            <div class="p-4">
                                <h3 class="font-bold text-lg text-gray-800 mb-1 line-clamp-1">
                                    {{ $photo->title }}
                                </h3>

                                @if($photo->description)
                                    <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                                        {{ $photo->description }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                                    {{-- Download button --}}
                                    <a href="{{ route('photos.download', $photo) }}"
                                        class="flex items-center text-indigo-600 hover:text-indigo-800 font-medium text-sm transition duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        @auth
                                            Original
                                        @else
                                            Download
                                        @endauth
                                    </a>

                                    {{-- Delete button (visible only for owner or admin) --}}
                                    @can('delete', $photo)
                                        <form action="{{ route('photos.destroy', $photo) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this photo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium text-sm transition duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $photos->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No photos yet</h3>
                    <p class="text-gray-500 mb-6">Be the first to share your amazing photos!</p>
                    @auth
                        <a href="{{ route('photos.create') }}"
                            class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                            Upload First Photo
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                            Login to Upload
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>

    {{-- Custom CSS for line-clamp and animations --}}
    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-in-out;
        }
    </style>
</x-app-layout>