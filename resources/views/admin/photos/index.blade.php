<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Photo Management
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Photos Grid --}}
            @if ($photos->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($photos as $photo)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition duration-300">
                            {{-- Photo Image --}}
                            <div class="relative aspect-square overflow-hidden">
                                <img src="{{ route('photos.watermarked', $photo) }}" alt="{{ $photo->title }}"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute top-2 right-2 bg-indigo-600 bg-opacity-90 text-white text-xs px-3 py-1 rounded-full">
                                    © {{ $photo->user->name }}
                                </div>
                            </div>

                            {{-- Photo Info --}}
                            <div class="p-4">
                                <h3 class="font-bold text-lg text-gray-800 mb-1 truncate">
                                    {{ $photo->title }}
                                </h3>

                                @if ($photo->description)
                                    <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                                        {{ $photo->description }}
                                    </p>
                                @endif

                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $photo->user->name }}
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                    <a href="{{ route('admin.photos.edit', $photo) }}"
                                        class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition duration-200">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.photos.destroy', $photo) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this photo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-medium text-sm transition duration-200">
                                            Delete
                                        </button>
                                    </form>
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
                    <p class="text-gray-500">Photos uploaded by users will appear here</p>
                </div>
            @endif

        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>