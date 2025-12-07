#!/bin/bash

# phpMyAdmin Installation Script
# This script installs and configures phpMyAdmin

echo "========================================="
echo "phpMyAdmin Installation"
echo "========================================="
echo ""

# Check if running as non-root
if [ "$EUID" -eq 0 ]; then 
    echo "✗ Please do not run this script as root"
    exit 1
fi

# Update package list
echo "→ Updating package list..."
sudo apt update

# Install phpMyAdmin
echo "→ Installing phpMyAdmin..."
echo ""
echo "⚠️  During installation, you will be asked:"
echo "   1. Select 'apache2' (press SPACE to select, then ENTER)"
echo "   2. Configure database: Select 'Yes'"
echo "   3. Enter a password for phpMyAdmin"
echo ""
read -p "Press Enter to continue..."

sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl -y

# Enable required PHP extensions
echo "→ Enabling PHP extensions..."
sudo phpenmod mbstring

# Create symbolic link to Apache document root
echo "→ Creating symbolic link..."
if [ ! -L /var/www/html/phpmyadmin ]; then
    sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin
fi

# Restart Apache
echo "→ Restarting Apache..."
sudo systemctl restart apache2

# Get server IP
SERVER_IP=$(curl -s ifconfig.me)

echo ""
echo "========================================="
echo "✓ phpMyAdmin Installation Complete!"
echo "========================================="
echo ""
echo "phpMyAdmin is now accessible at:"
echo "  HTTP:  http://${SERVER_IP}/phpmyadmin"
echo "  HTTPS: https://${SERVER_IP}/phpmyadmin"
echo ""
echo "Database Login Credentials:"
echo "  Username: photo_user"
echo "  Password: 123456"
echo "  Database: photo_gallery"
echo ""
echo "OR use root account:"
echo "  Username: root"
echo "  Password: (your MySQL root password)"
echo ""
echo "Security Note:"
echo "  For production, consider restricting access to phpMyAdmin"
echo "  by IP address or using .htaccess authentication."
echo ""
