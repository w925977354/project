#!/bin/bash

# Database Troubleshooting Script
# This script helps diagnose and fix database connection issues

echo "========================================"
echo "Database Connection Troubleshooting"
echo "========================================"
echo ""

cd /var/www/photo-gallery

# Step 1: Check .env file
echo "→ Checking .env database configuration..."
echo ""
grep "^DB_" .env
echo ""

# Step 2: Read database credentials from .env
DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d= -f2)
DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d= -f2)
DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d= -f2)

echo "Found credentials:"
echo "  Database: $DB_DATABASE"
echo "  Username: $DB_USERNAME"
echo "  Password: $DB_PASSWORD"
echo ""

# Step 3: Test MySQL connection
echo "→ Testing MySQL connection..."
if mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "SELECT 1;" &>/dev/null; then
    echo "✓ Database connection successful!"
    echo ""
else
    echo "✗ Database connection FAILED!"
    echo ""
    echo "Attempting to fix..."
    echo ""
    
    # Prompt for MySQL root password if needed
    echo "Please enter MySQL commands to recreate the user:"
    echo ""
    
    sudo mysql <<EOF
DROP USER IF EXISTS '${DB_USERNAME}'@'localhost';
CREATE USER '${DB_USERNAME}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON ${DB_DATABASE}.* TO '${DB_USERNAME}'@'localhost';
FLUSH PRIVILEGES;
SELECT User, Host FROM mysql.user WHERE User = '${DB_USERNAME}';
EOF
    
    echo ""
    echo "User recreated. Testing connection again..."
    
    if mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "SELECT 1;" &>/dev/null; then
        echo "✓ Database connection now working!"
    else
        echo "✗ Still having issues. Please check:"
        echo "  1. MySQL is running: sudo systemctl status mysql"
        echo "  2. Database exists: sudo mysql -e 'SHOW DATABASES;'"
        echo "  3. Password is correct in .env file"
    fi
fi

echo ""
echo "→ Fixing permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo mkdir -p storage/logs
sudo touch storage/logs/laravel.log
sudo chown -R www-data:www-data storage/logs
sudo chmod -R 775 storage/logs
echo "✓ Permissions fixed"

echo ""
echo "→ Clearing Laravel cache..."
php artisan config:clear
php artisan cache:clear 2>/dev/null || true
echo "✓ Cache cleared"

echo ""
echo "→ Running migrations..."
php artisan migrate --force
echo ""

echo "========================================"
echo "Troubleshooting complete!"
echo "========================================"
echo ""
echo "Try accessing your application now:"
echo "  http://$(grep "^APP_URL=" .env | cut -d= -f2 | sed 's|http://||')"
