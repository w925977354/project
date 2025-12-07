<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            {{ __('Upload Photo') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        {{-- Title Field --}}
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Photo Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
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
                                placeholder="Tell us about this photo...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image Upload Field --}}
                        <div>
                            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                Photo Image <span class="text-red-500">*</span>
                            </label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="image" name="image" type="file"
                                                accept="image/jpeg,image/jpg,image/png" required class="sr-only"
                                                onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        JPG, JPEG, PNG up to 2MB
                                    </p>
                                    <p class="text-xs text-indigo-600 font-medium mt-2">
                                        ✨ Your username will be automatically added as a watermark
                                    </p>
                                </div>
                            </div>

                            {{-- Image Preview --}}
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="preview" src="" alt="Preview"
                                    class="max-w-full h-64 mx-auto rounded-lg shadow-md">
                            </div>

                            @error('image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="flex items-center justify-between pt-4">
                            <a href="{{ route('photos.index') }}"
                                class="text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                                &larr; Back to Gallery
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                Upload Photo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
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
                            <strong>Upload Guidelines:</strong> Please ensure your photo meets the requirements
                            (JPG/PNG, max 2MB).
                            Your username will be automatically added as a watermark to protect your work.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Image Preview --}}
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>