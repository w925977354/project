#!/bin/bash

# Photo Gallery - AWS Ubuntu LAMP Deployment Script
# This script automates the deployment process on AWS Ubuntu

set -e  # Exit on error

echo "========================================="
echo "Photo Gallery Deployment Script"
echo "========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}➜ $1${NC}"
}

# Check if running as root
if [ "$EUID" -eq 0 ]; then 
    print_error "Please do not run this script as root"
    exit 1
fi

# Step 1: Update system
print_info "Updating system packages..."
sudo apt update && sudo apt upgrade -y
print_success "System updated"

# Step 2: Install Apache
print_info "Installing Apache..."
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2
print_success "Apache installed"

# Step 3: Install MySQL
print_info "Installing MySQL..."
sudo apt install mysql-server -y
print_success "MySQL installed"

# Prompt for database configuration
read -p "Enter database name [photo_gallery]: " DB_NAME
DB_NAME=${DB_NAME:-photo_gallery}

read -p "Enter database username [photo_user]: " DB_USER
DB_USER=${DB_USER:-photo_user}

# 填的是：123456
read -sp "Enter database password: " DB_PASS
echo ""

# Create database and user
print_info "Creating database and user..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"
sudo mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
print_success "Database configured"

# Step 4: Install PHP 8.3
print_info "Installing PHP 8.3..."
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring \
    php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl libapache2-mod-php8.3 -y
print_success "PHP 8.3 installed"

# Step 5: Install Composer
print_info "Installing Composer..."
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
print_success "Composer installed"

# Step 6: Install Node.js (for frontend assets)
print_info "Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y
print_success "Node.js installed"

# Step 7: Clone repository
print_info "Cloning repository..."
cd /var/www

# Check if directory exists
if [ -d "photo-gallery" ]; then
    print_info "Directory exists, pulling latest changes..."
    cd photo-gallery
    sudo git pull origin main
else
    # Try SSH first, fallback to HTTPS
    if sudo git clone git@github.com:Ei-Ayw/photo-gallery-test.git photo-gallery 2>/dev/null; then
        print_success "Repository cloned via SSH"
    else
        print_info "SSH failed, trying HTTPS..."
        sudo git clone https://github.com/Ei-Ayw/photo-gallery-test.git photo-gallery
        print_success "Repository cloned via HTTPS"
    fi
    cd photo-gallery
fi

# Set ownership
sudo chown -R $USER:www-data /var/www/photo-gallery
print_success "Repository ready"

# Step 8: Install dependencies
print_info "Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

print_info "Installing NPM dependencies..."
npm install
npm run build
print_success "Dependencies installed"

# Step 9: Configure environment
print_info "Configuring environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    print_success ".env file created"
fi

# Update .env file
read -p "Enter your domain or IP address: " APP_URL
sed -i "s|APP_URL=.*|APP_URL=http://${APP_URL}|g" .env
sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|g" .env
sed -i "s|DB_HOST=.*|DB_HOST=127.0.0.1|g" .env
sed -i "s|DB_PORT=.*|DB_PORT=3306|g" .env
sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|g" .env
sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|g" .env
sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|g" .env
sed -i "s|APP_ENV=.*|APP_ENV=production|g" .env
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|g" .env

# Generate application key
php artisan key:generate --force
print_success "Environment configured"

# Step 10: Set permissions (BEFORE migrations)
print_info "Setting permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Also give current user access
sudo usermod -a -G www-data $USER

# Create log directory if it doesn't exist
sudo mkdir -p storage/logs
sudo touch storage/logs/laravel.log
sudo chown -R www-data:www-data storage/logs
sudo chmod -R 775 storage/logs

print_success "Permissions set"

# Step 11: Run migrations
print_info "Running database migrations..."
php artisan migrate --force
php artisan storage:link
print_success "Database migrated"

# Step 12: Configure Apache
print_info "Configuring Apache..."

# Create virtual host configuration
sudo tee /etc/apache2/sites-available/photo-gallery.conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerName ${APP_URL}
    DocumentRoot /var/www/photo-gallery/public
    
    <Directory /var/www/photo-gallery/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/photo-gallery-error.log
    CustomLog \${APACHE_LOG_DIR}/photo-gallery-access.log combined
</VirtualHost>
EOF

# Enable modules and site
sudo a2enmod rewrite
sudo a2dissite 000-default.conf 2>/dev/null || true
sudo a2ensite photo-gallery.conf

# Test configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
print_success "Apache configured"

# Step 13: Configure firewall
print_info "Configuring firewall..."
sudo ufw --force enable
sudo ufw allow OpenSSH
sudo ufw allow 'Apache Full'
print_success "Firewall configured"

# Step 14: Install Certbot (optional)
read -p "Do you want to install SSL certificate with Certbot? (y/n): " INSTALL_SSL
if [ "$INSTALL_SSL" = "y" ] || [ "$INSTALL_SSL" = "Y" ]; then
    print_info "Installing Certbot..."
    sudo apt install certbot python3-certbot-apache -y
    
    read -p "Enter your email for SSL certificate: " SSL_EMAIL
    read -p "Enter your domain name (without http://): " SSL_DOMAIN
    
    sudo certbot --apache -d ${SSL_DOMAIN} --non-interactive --agree-tos -m ${SSL_EMAIL} --redirect
    
    # Update .env for HTTPS
    sed -i "s|APP_URL=.*|APP_URL=https://${SSL_DOMAIN}|g" .env
    
    print_success "SSL certificate installed"
else
    print_info "Skipping SSL installation"
fi

# Step 15: Create admin user (optional)
read -p "Do you want to create an admin user? (y/n): " CREATE_ADMIN
if [ "$CREATE_ADMIN" = "y" ] || [ "$CREATE_ADMIN" = "Y" ]; then
    read -p "Enter admin name: " ADMIN_NAME
    read -p "Enter admin email: " ADMIN_EMAIL
    read -sp "Enter admin password: " ADMIN_PASS
    echo ""
    
    php artisan tinker --execute="
    \$user = new App\Models\User();
    \$user->name = '${ADMIN_NAME}';
    \$user->email = '${ADMIN_EMAIL}';
    \$user->password = bcrypt('${ADMIN_PASS}');
    \$user->is_admin = true;
    \$user->email_verified_at = now();
    \$user->save();
    echo 'Admin user created successfully';
    "
    
    print_success "Admin user created"
fi

# Final steps
print_info "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
print_success "Cache cleared"

echo ""
echo "========================================="
echo -e "${GREEN}Deployment Complete!${NC}"
echo "========================================="
echo ""
echo "Your application is now accessible at:"
echo "  HTTP:  http://${APP_URL}"
if [ "$INSTALL_SSL" = "y" ] || [ "$INSTALL_SSL" = "Y" ]; then
    echo "  HTTPS: https://${SSL_DOMAIN}"
fi
echo ""
echo "Next steps:"
echo "  1. Test the application in your browser"
echo "  2. Register a new user or login with admin credentials"
echo "  3. Upload a test photo"
echo "  4. Check logs if you encounter any issues:"
echo "     - Laravel: /var/www/photo-gallery/storage/logs/laravel.log"
echo "     - Apache: /var/log/apache2/photo-gallery-error.log"
echo ""
print_success "Deployment script completed successfully!"
