# AWS Ubuntu LAMP Deployment Guide
## Photo Gallery Laravel Application

This guide provides step-by-step instructions for deploying the Photo Gallery application on AWS Ubuntu with LAMP stack and HTTPS security.

---

## Prerequisites

1. **AWS Account** with EC2 access
2. **Domain name** (optional but recommended for HTTPS)
3. **SSH Key Pair** for EC2 instance access
4. **GitHub Repository**: `git@github.com:Ei-Ayw/photo-gallery-test.git`

---

## Part 1: AWS EC2 Instance Setup

### 1.1 Launch EC2 Instance

1. **Log in to AWS Console** → Navigate to EC2
2. **Click "Launch Instance"**
3. **Configure Instance**:
   - **Name**: `photo-gallery-server`
   - **AMI**: Ubuntu Server 22.04 LTS (64-bit x86)
   - **Instance Type**: `t2.micro` (Free tier eligible) or `t2.small` (recommended)
   - **Key Pair**: Create new or select existing SSH key pair
   - **Network Settings**:
     - Allow SSH (port 22) from your IP
     - Allow HTTP (port 80) from anywhere (0.0.0.0/0)
     - Allow HTTPS (port 443) from anywhere (0.0.0.0/0)
   - **Storage**: 20 GB gp3 (minimum)
4. **Launch Instance**

### 1.2 Connect to Instance

```bash
# Replace with your key file and instance public IP
ssh -i "your-key.pem" ubuntu@your-instance-public-ip
```

### 1.3 Update System

```bash
sudo apt update && sudo apt upgrade -y
```

---

## Part 2: Install LAMP Stack

### 2.1 Install Apache

```bash
# Install Apache2
sudo apt install apache2 -y

# Enable Apache to start on boot
sudo systemctl enable apache2

# Start Apache
sudo systemctl start apache2

# Check status
sudo systemctl status apache2
```

### 2.2 Install MySQL

```bash
# Install MySQL Server
sudo apt install mysql-server -y

# Secure MySQL installation
sudo mysql_secure_installation
```

**MySQL Secure Installation Prompts**:
- Validate Password Component: `Y`
- Password Validation Policy: `2` (STRONG)
- Set root password: Choose a strong password
- Remove anonymous users: `Y`
- Disallow root login remotely: `Y`
- Remove test database: `Y`
- Reload privilege tables: `Y`

**Create Database and User**:
```bash
sudo mysql

# In MySQL prompt:
CREATE DATABASE photo_gallery;
CREATE USER 'photo_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON photo_gallery.* TO 'photo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2.3 Install PHP 8.3

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.3 and required extensions
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl -y

# Install Apache PHP module
sudo apt install libapache2-mod-php8.3 -y

# Verify PHP installation
php -v
```

---

## Part 3: Install Composer

```bash
# Download Composer installer
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Install Composer globally
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Verify installation
composer --version

# Clean up
rm composer-setup.php
```

---

## Part 4: Install GD Library (for Image Processing)

```bash
# Install GD library and dependencies
sudo apt install php8.3-gd -y

# Restart Apache
sudo systemctl restart apache2

# Verify GD is enabled
php -m | grep gd
```

---

## Part 5: Deploy Laravel Application

### 5.1 Clone Repository

```bash
# Navigate to web root
cd /var/www

# Clone repository (you may need to set up SSH key for GitHub)
sudo git clone git@github.com:Ei-Ayw/photo-gallery-test.git photo-gallery

# If SSH doesn't work, use HTTPS:
# sudo git clone https://github.com/Ei-Ayw/photo-gallery-test.git photo-gallery

# Set ownership
sudo chown -R $USER:www-data /var/www/photo-gallery
```

### 5.2 Set Up GitHub SSH Key (if needed)

```bash
# Generate SSH key
ssh-keygen -t ed25519 -C "your_email@example.com"

# Display public key
cat ~/.ssh/id_ed25519.pub

# Copy the output and add it to GitHub:
# GitHub → Settings → SSH and GPG keys → New SSH key
```

### 5.3 Install Dependencies

```bash
cd /var/www/photo-gallery

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies (optional, for frontend assets)
# First install Node.js and NPM:
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y

# Then install NPM packages:
npm install
npm run build
```

### 5.4 Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit .env file
nano .env
```

**Update `.env` with your settings**:
```env
APP_NAME="Photo Gallery"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://your-domain-or-ip

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=photo_gallery
DB_USERNAME=photo_user
DB_PASSWORD=your_strong_password

SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 5.5 Generate Application Key

```bash
php artisan key:generate
```

### 5.6 Set Permissions

```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/photo-gallery/storage
sudo chown -R www-data:www-data /var/www/photo-gallery/bootstrap/cache

sudo chmod -R 775 /var/www/photo-gallery/storage
sudo chmod -R 775 /var/www/photo-gallery/bootstrap/cache
```

### 5.7 Run Migrations

```bash
php artisan migrate --force

# Create storage link
php artisan storage:link
```

### 5.8 Create Admin User (Optional)

```bash
php artisan tinker

# In Tinker prompt:
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@example.com';
$user->password = bcrypt('your_admin_password');
$user->is_admin = true;
$user->email_verified_at = now();
$user->save();
exit
```

---

## Part 6: Configure Apache

### 6.1 Create Virtual Host

```bash
sudo nano /etc/apache2/sites-available/photo-gallery.conf
```

**Add the following configuration**:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    ServerAdmin admin@your-domain.com
    
    DocumentRoot /var/www/photo-gallery/public
    
    <Directory /var/www/photo-gallery/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/photo-gallery-error.log
    CustomLog ${APACHE_LOG_DIR}/photo-gallery-access.log combined
</VirtualHost>
```

**If you don't have a domain, use IP address**:
```apache
<VirtualHost *:80>
    ServerName your-ec2-public-ip
    DocumentRoot /var/www/photo-gallery/public
    
    <Directory /var/www/photo-gallery/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/photo-gallery-error.log
    CustomLog ${APACHE_LOG_DIR}/photo-gallery-access.log combined
</VirtualHost>
```

### 6.2 Enable Site and Modules

```bash
# Enable rewrite module
sudo a2enmod rewrite

# Disable default site
sudo a2dissite 000-default.conf

# Enable photo gallery site
sudo a2ensite photo-gallery.conf

# Test Apache configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

---

## Part 7: Configure HTTPS with Certbot

### 7.1 Install Certbot

```bash
# Install Certbot and Apache plugin
sudo apt install certbot python3-certbot-apache -y
```

### 7.2 Obtain SSL Certificate

**If you have a domain**:
```bash
# Obtain and install certificate
sudo certbot --apache -d your-domain.com -d www.your-domain.com

# Follow the prompts:
# - Enter email address
# - Agree to Terms of Service
# - Choose whether to redirect HTTP to HTTPS (recommended: Yes)
```

**If you DON'T have a domain** (Self-Signed Certificate):
```bash
# Generate self-signed certificate
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/ssl/private/photo-gallery-selfsigned.key \
  -out /etc/ssl/certs/photo-gallery-selfsigned.crt

# Update Apache config
sudo nano /etc/apache2/sites-available/photo-gallery-ssl.conf
```

**Add SSL configuration**:
```apache
<VirtualHost *:443>
    ServerName your-ec2-public-ip
    DocumentRoot /var/www/photo-gallery/public
    
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/photo-gallery-selfsigned.crt
    SSLCertificateKeyFile /etc/ssl/private/photo-gallery-selfsigned.key
    
    <Directory /var/www/photo-gallery/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/photo-gallery-ssl-error.log
    CustomLog ${APACHE_LOG_DIR}/photo-gallery-ssl-access.log combined
</VirtualHost>
```

```bash
# Enable SSL module and site
sudo a2enmod ssl
sudo a2ensite photo-gallery-ssl.conf
sudo systemctl restart apache2
```

### 7.3 Auto-Renewal (for Let's Encrypt)

```bash
# Test renewal
sudo certbot renew --dry-run

# Certbot automatically sets up a cron job for renewal
```

### 7.4 Update .env for HTTPS

```bash
nano /var/www/photo-gallery/.env
```

Update:
```env
APP_URL=https://your-domain.com
# or
APP_URL=https://your-ec2-public-ip
```

---

## Part 8: Security Hardening

### 8.1 Configure Firewall

```bash
# Enable UFW
sudo ufw enable

# Allow SSH
sudo ufw allow OpenSSH

# Allow HTTP and HTTPS
sudo ufw allow 'Apache Full'

# Check status
sudo ufw status
```

### 8.2 Disable Directory Listing

Already configured in Virtual Host with `Options -Indexes`

### 8.3 Hide PHP Version

```bash
sudo nano /etc/php/8.3/apache2/php.ini
```

Find and set:
```ini
expose_php = Off
```

```bash
sudo systemctl restart apache2
```

---

## Part 9: Testing and Verification

### 9.1 Test Application

1. **HTTP Access**: `http://your-domain-or-ip`
2. **HTTPS Access**: `https://your-domain-or-ip`
3. **Test Features**:
   - User registration
   - Login
   - Photo upload
   - Photo viewing with watermark
   - Photo download
   - Admin panel (if admin user created)

### 9.2 Check Logs

```bash
# Apache error logs
sudo tail -f /var/log/apache2/photo-gallery-error.log

# Laravel logs
sudo tail -f /var/www/photo-gallery/storage/logs/laravel.log
```

---

## Part 10: Maintenance

### 10.1 Update Application

```bash
cd /var/www/photo-gallery

# Pull latest changes
sudo git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Restart Apache
sudo systemctl restart apache2
```

### 10.2 Backup Database

```bash
# Create backup
mysqldump -u photo_user -p photo_gallery > backup_$(date +%Y%m%d).sql

# Restore from backup
mysql -u photo_user -p photo_gallery < backup_20231207.sql
```

---

## Troubleshooting

### Issue: 500 Internal Server Error

**Solution**:
```bash
# Check Laravel logs
sudo tail -f /var/www/photo-gallery/storage/logs/laravel.log

# Check Apache logs
sudo tail -f /var/log/apache2/photo-gallery-error.log

# Ensure proper permissions
sudo chown -R www-data:www-data /var/www/photo-gallery/storage
sudo chmod -R 775 /var/www/photo-gallery/storage
```

### Issue: Database Connection Error

**Solution**:
```bash
# Verify MySQL is running
sudo systemctl status mysql

# Test database connection
mysql -u photo_user -p photo_gallery

# Check .env configuration
nano /var/www/photo-gallery/.env
```

### Issue: Image Upload Not Working

**Solution**:
```bash
# Check storage permissions
sudo chown -R www-data:www-data /var/www/photo-gallery/storage/app/public
sudo chmod -R 775 /var/www/photo-gallery/storage/app/public

# Verify storage link
php artisan storage:link

# Check PHP upload limits
sudo nano /etc/php/8.3/apache2/php.ini
# Ensure: upload_max_filesize = 10M
#         post_max_size = 10M

sudo systemctl restart apache2
```

### Issue: HTTPS Not Working

**Solution**:
```bash
# Check SSL module
sudo a2enmod ssl

# Verify certificate files exist
ls -la /etc/letsencrypt/live/your-domain.com/

# Check Apache SSL config
sudo apache2ctl -S

# Restart Apache
sudo systemctl restart apache2
```

---

## Quick Reference Commands

```bash
# Restart Apache
sudo systemctl restart apache2

# View Laravel logs
sudo tail -f /var/www/photo-gallery/storage/logs/laravel.log

# View Apache logs
sudo tail -f /var/log/apache2/photo-gallery-error.log

# Clear Laravel cache
cd /var/www/photo-gallery
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Check disk space
df -h

# Check memory usage
free -h

# Monitor Apache processes
sudo systemctl status apache2
```

---

## Security Checklist

- [x] HTTPS enabled (Certbot or self-signed)
- [x] Firewall configured (UFW)
- [x] Database secured (strong password, no remote root)
- [x] Directory listing disabled
- [x] PHP version hidden
- [x] APP_DEBUG=false in production
- [x] Proper file permissions (775 for storage)
- [x] Regular backups configured
- [x] SSL certificate auto-renewal (if using Let's Encrypt)

---

## Support

For issues or questions:
- Check Laravel logs: `/var/www/photo-gallery/storage/logs/laravel.log`
- Check Apache logs: `/var/log/apache2/photo-gallery-error.log`
- Review this guide's troubleshooting section

---

**Deployment Completed! 🎉**

Your Photo Gallery application is now live on AWS Ubuntu with LAMP stack and HTTPS security.
