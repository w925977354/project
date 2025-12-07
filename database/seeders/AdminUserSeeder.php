<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder to create default admin and regular users for testing
 * This helps quickly set up the application with test accounts
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates one admin user and one regular user for testing purposes.
     */
    public function run(): void
    {
        // Create an administrator account
        // Admin can delete ANY photo for content moderation
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        echo "✅ Admin user created:\n";
        echo "   Email: admin@example.com\n";
        echo "   Password: password\n\n";

        // Create a regular user account
        // Regular users can only delete their own photos
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        echo "✅ Regular user created:\n";
        echo "   Email: user@example.com\n";
        echo "   Password: password\n\n";

        echo "👉 You can now login and test the watermark feature!\n";
    }
}
