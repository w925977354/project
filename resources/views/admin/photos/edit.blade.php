<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Edit Photo: {{ $photo->title }}
            </h2>
            <a href="{{ route('admin.photos') }}"
                class="text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                &larr; Back to Photos
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Photo Preview --}}
                <div>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="{{ route('photos.watermarked', $photo) }}" alt="{{ $photo->title }}"
                            class="w-full h-auto">
                    </div>
                    <div class="mt-4 bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Photo Information</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Uploaded by:</span>
                                <span class="font-semibold text-gray-900">{{ $photo->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Uploaded:</span>
                                <span
                                    class="font-semibold text-gray-900">{{ $photo->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Last updated:</span>
                                <span
                                    class="font-semibold text-gray-900">{{ $photo->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Form --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <form action="{{ route('admin.photos.update', $photo) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            {{-- Title --}}
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Photo Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title', $photo->title) }}"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description (Optional)
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $photo->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('admin.photos') }}"
                                    class="text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                                    Update Photo
                                </button>
                            </div>
                        </form>

                        {{-- Delete Section --}}
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Danger Zone</h4>
                            <form action="{{ route('admin.photos.destroy', $photo) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this photo? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-200">
                                    Delete Photo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>