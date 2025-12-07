#!/bin/bash

# Create Initial Users Script
# This script creates an admin user and a regular user for testing

echo "========================================="
echo "Photo Gallery - Create Initial Users"
echo "========================================="
echo ""

cd /var/www/photo-gallery

echo "→ Creating users..."
echo ""

php artisan tinker --execute="
// Create Admin User
\$admin = App\Models\User::firstOrCreate(
    ['email' => 'admin@photogallery.com'],
    [
        'name' => 'Admin User',
        'password' => bcrypt('admin123'),
        'is_admin' => true,
        'email_verified_at' => now()
    ]
);

if (\$admin->wasRecentlyCreated) {
    echo '✓ Admin user created successfully\n';
} else {
    echo '→ Admin user already exists\n';
}

echo '  Email: admin@photogallery.com\n';
echo '  Password: admin123\n';
echo '  Role: Administrator\n';
echo '\n';

// Create Regular User
\$user = App\Models\User::firstOrCreate(
    ['email' => 'user@photogallery.com'],
    [
        'name' => 'Regular User',
        'password' => bcrypt('user123'),
        'is_admin' => false,
        'email_verified_at' => now()
    ]
);

if (\$user->wasRecentlyCreated) {
    echo '✓ Regular user created successfully\n';
} else {
    echo '→ Regular user already exists\n';
}

echo '  Email: user@photogallery.com\n';
echo '  Password: user123\n';
echo '  Role: Regular User\n';
"

echo ""
echo "========================================="
echo "✓ Users created successfully!"
echo "========================================="
echo ""
echo "You can now login with:"
echo ""
echo "Admin Account:"
echo "  Email: admin@photogallery.com"
echo "  Password: admin123"
echo "  Access: Full admin panel access"
echo ""
echo "Regular User Account:"
echo "  Email: user@photogallery.com"
echo "  Password: user123"
echo "  Access: Upload and manage own photos"
echo ""
