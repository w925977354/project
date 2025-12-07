<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Admin Panel Controller
 * Handles all administrative functions including user management, photo management, and statistics
 * 
 * SECURITY: Only accessible to users with is_admin = true
 * All routes are protected by the 'admin' middleware defined in routes/web.php
 */
class AdminController extends Controller
{
    /**
     * Constructor - Admin middleware applied at route level
     * SECURITY: All routes in this controller are protected by the 'admin' middleware
     * defined in routes/web.php, which ensures only users with is_admin=true can access.
     * 
     * No additional checks needed here as the middleware handles all authorization.
     */
    public function __construct()
    {
        // Admin authorization is handled by the 'admin' middleware in routes/web.php
        // This ensures a single point of control for all admin routes
    }

    /**
     * Display admin dashboard with statistics
     */
    public function index()
    {
        // Gather statistics
        $stats = [
            'total_users' => User::count(),
            'total_photos' => Photo::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'photos_today' => Photo::whereDate('created_at', today())->count(),
            'users_today' => User::whereDate('created_at', today())->count(),
        ];

        // Get recent photos
        $recent_photos = Photo::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get recent users
        $recent_users = User::latest()
            ->take(5)
            ->get();

        // Top uploaders
        $top_uploaders = User::withCount('photos')
            ->orderBy('photos_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_photos', 'recent_users', 'top_uploaders'));
    }

    /**
     * Display list of all users
     */
    public function users()
    {
        $users = User::withCount('photos')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create a new user
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    /**
     * Show form to edit a user
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update a user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_admin' => $request->has('is_admin'),
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Delete a user and all their photos
     */
    public function destroyUser(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Delete all user's photos
        foreach ($user->photos as $photo) {
            $filePath = storage_path('app/public/' . $photo->image_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete user
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User and all their photos deleted successfully!');
    }

    /**
     * Display list of all photos
     */
    public function photos()
    {
        $photos = Photo::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.photos.index', compact('photos'));
    }

    /**
     * Show form to edit a photo
     */
    public function editPhoto(Photo $photo)
    {
        return view('admin.photos.edit', compact('photo'));
    }

    /**
     * Update a photo
     */
    public function updatePhoto(Request $request, Photo $photo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $photo->update($validated);

        return redirect()->route('admin.photos')
            ->with('success', 'Photo updated successfully!');
    }

    /**
     * Delete a photo (admin can delete any photo)
     */
    public function destroyPhoto(Photo $photo)
    {
        // Delete physical file
        $filePath = storage_path('app/public/' . $photo->image_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete database record
        $photo->delete();

        return redirect()->route('admin.photos')
            ->with('success', 'Photo deleted successfully!');
    }
}
