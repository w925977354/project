#!/bin/bash

# Quick fix script for deployment issues
# Run this if you encounter permission or database errors

echo "================================"
echo "Photo Gallery - Quick Fix"
echo "================================"
echo ""

cd /var/www/photo-gallery

# Fix 1: Update .env to use MySQL
echo "→ Fixing database configuration..."
sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=mysql|g" .env
sed -i "s|^DB_HOST=.*|DB_HOST=127.0.0.1|g" .env
sed -i "s|^DB_PORT=.*|DB_PORT=3306|g" .env

# Verify the changes
echo "Current database configuration:"
cat .env | grep "^DB_"
echo ""

# Fix 2: Fix permissions
echo "→ Fixing permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Create and fix log directory
sudo mkdir -p storage/logs
sudo touch storage/logs/laravel.log
sudo chown -R www-data:www-data storage/logs
sudo chmod -R 775 storage/logs

# Add current user to www-data group
sudo usermod -a -G www-data ubuntu

echo "✓ Permissions fixed"
echo ""

# Fix 3: Clear cache
echo "→ Clearing cache..."
php artisan config:clear
php artisan cache:clear 2>/dev/null || true
echo "✓ Cache cleared"
echo ""

# Fix 4: Run migrations
echo "→ Running migrations..."
php artisan migrate --force

# Create storage link
echo "→ Creating storage link..."
php artisan storage:link

echo ""
echo "================================"
echo "✓ Fix completed!"
echo "================================"
echo ""
echo "Now try accessing your application:"
echo "  http://your-ip-address"
echo ""
echo "If you still have issues, check the logs:"
echo "  sudo tail -f storage/logs/laravel.log"
