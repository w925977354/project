<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/**
 * Homepage route - Display the photo gallery
 * Public access for all visitors (guests and authenticated users)
 */
Route::get('/', [PhotoController::class, 'index'])->name('home');

/**
 * Additional photo routes for advanced features
 * IMPORTANT: These must be defined BEFORE the resource route to ensure proper matching
 */
// Download photo - guests get watermarked version, authenticated users get original
Route::get('/photos/{photo}/download', [PhotoController::class, 'download'])->name('photos.download');

// Display photo with watermark showing uploader's name
Route::get('/photos/{photo}/watermarked', [PhotoController::class, 'displayWithWatermark'])->name('photos.watermarked');

/**
 * Photo resource routes with RESTful routing
 * - index: Display all photos (public)
 * - create: Show upload form (auth required)
 * - store: Process upload (auth required)
 * - show: Display single photo (public)
 * - edit: Show edit form (auth + owner/admin)
 * - update: Process update (auth + owner/admin)
 * - destroy: Delete photo (auth + owner/admin)
 */
Route::resource('photos', PhotoController::class);

/**
 * Dashboard route for authenticated users
 * Shows user's own photos and statistics
 */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * Profile management routes (auth required)
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Admin Panel Routes (admin only)
 * Accessible only to users with is_admin = true
 * SECURITY: Protected by 'auth' and 'admin' middleware
 */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Photo Management
    Route::get('/photos', [AdminController::class, 'photos'])->name('photos');
    Route::get('/photos/{photo}/edit', [AdminController::class, 'editPhoto'])->name('photos.edit');
    Route::put('/photos/{photo}', [AdminController::class, 'updatePhoto'])->name('photos.update');
    Route::delete('/photos/{photo}', [AdminController::class, 'destroyPhoto'])->name('photos.destroy');
});

// Include authentication routes (login, register, etc.)
require __DIR__ . '/auth.php';

