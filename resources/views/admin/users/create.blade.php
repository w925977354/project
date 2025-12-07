<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Create New User
            </h2>
            <a href="{{ route('admin.users') }}"
                class="text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                &larr; Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        {{-- Admin Checkbox --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="is_admin" id="is_admin" value="1"
                                {{ old('is_admin') ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_admin" class="ml-2 block text-sm text-gray-900">
                                Administrator (can manage all users and photos)
                            </label>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="flex items-center justify-between pt-4">
                            <a href="{{ route('admin.users') }}"
                                class="text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition duration-200 transform hover:scale-105">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
