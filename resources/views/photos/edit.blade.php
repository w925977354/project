<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            {{ __('Edit Photo') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('photos.update', $photo) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Current Photo Preview --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Current Photo</label>
                            <img src="{{ Storage::url($photo->image_path) }}" alt="{{ $photo->title }}"
                                class="max-w-md h-auto rounded-lg shadow-md mx-auto">
                            <p class="text-sm text-gray-500 text-center mt-2">
                                Note: You can only edit the title and description. The image itself cannot be changed.
                            </p>
                        </div>

                        {{-- Title Field --}}
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Photo Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $photo->title) }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('title') border-red-500 @enderror"
                                placeholder="Enter a catchy title for your photo">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description Field --}}
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Description (Optional)
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 @error('description') border-red-500 @enderror"
                                placeholder="Tell us about this photo...">{{ old('description', $photo->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="flex items-center justify-between pt-4">
                            <a href="{{ route('photos.show', $photo) }}"
                                class="text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                                &larr; Cancel
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>