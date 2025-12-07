# AWS Deployment Quick Start Guide

## 🚀 Quick Deployment (Using Automated Script)

### Step 1: Connect to Your AWS Ubuntu Server
```bash
ssh -i "your-key.pem" ubuntu@your-ec2-public-ip
```

### Step 2: Download and Run Deployment Script
```bash
# Download the deployment script
wget https://raw.githubusercontent.com/Ei-Ayw/photo-gallery-test/main/deploy.sh

# Make it executable
chmod +x deploy.sh

# Run the script
./deploy.sh
```

The script will automatically:
- ✅ Install LAMP stack (Apache, MySQL, PHP 8.3)
- ✅ Install Composer and Node.js
- ✅ Clone the repository
- ✅ Configure database
- ✅ Set up environment
- ✅ Configure Apache
- ✅ Install SSL certificate (optional)
- ✅ Create admin user (optional)

---

## 📋 Manual Deployment (Step by Step)

If you prefer manual installation, follow these steps:

### 1. Update System
```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Install LAMP Stack
```bash
# Apache
sudo apt install apache2 -y

# MySQL
sudo apt install mysql-server -y

# PHP 8.3
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-xml \
  php8.3-mbstring php8.3-curl php8.3-zip php8.3-gd libapache2-mod-php8.3 -y
```

### 3. Install Composer
```bash
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

### 4. Create Database
```bash
sudo mysql
```
```sql
CREATE DATABASE photo_gallery;
CREATE USER 'photo_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON photo_gallery.* TO 'photo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Clone Repository
```bash
cd /var/www
sudo git clone https://github.com/Ei-Ayw/photo-gallery-test.git photo-gallery
cd photo-gallery
```

### 6. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 7. Configure Environment
```bash
cp .env.example .env
nano .env
```
Update:
- `APP_URL`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Set `APP_ENV=production` and `APP_DEBUG=false`

```bash
php artisan key:generate
```

### 8. Set Permissions
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 9. Run Migrations
```bash
php artisan migrate --force
php artisan storage:link
```

### 10. Configure Apache
```bash
sudo nano /etc/apache2/sites-available/photo-gallery.conf
```
Add:
```apache
<VirtualHost *:80>
    ServerName your-domain-or-ip
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

```bash
sudo a2enmod rewrite
sudo a2dissite 000-default.conf
sudo a2ensite photo-gallery.conf
sudo systemctl restart apache2
```

### 11. Install SSL (HTTPS)
```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d your-domain.com
```

Update `.env`:
```env
APP_URL=https://your-domain.com
```

---

## 🔧 Essential Commands

### View Logs
```bash
# Laravel logs
sudo tail -f /var/www/photo-gallery/storage/logs/laravel.log

# Apache logs
sudo tail -f /var/log/apache2/photo-gallery-error.log
```

### Restart Services
```bash
sudo systemctl restart apache2
sudo systemctl restart mysql
```

### Clear Cache
```bash
cd /var/www/photo-gallery
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Update Application
```bash
cd /var/www/photo-gallery
sudo git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan cache:clear
sudo systemctl restart apache2
```

---

## ✅ Deployment Checklist

Before submitting your demo:

- [ ] Application accessible via public IP or domain
- [ ] HTTPS enabled (green padlock in browser)
- [ ] User registration works
- [ ] User login works
- [ ] Photo upload works
- [ ] Photos display with watermark
- [ ] Photo download works (with/without watermark based on auth)
- [ ] Admin panel accessible (if admin user created)
- [ ] No errors in browser console
- [ ] No errors in Laravel logs

---

## 🆘 Troubleshooting

### 500 Internal Server Error
```bash
# Check permissions
sudo chown -R www-data:www-data /var/www/photo-gallery/storage
sudo chmod -R 775 /var/www/photo-gallery/storage

# Check logs
sudo tail -f /var/www/photo-gallery/storage/logs/laravel.log
```

### Database Connection Error
```bash
# Test database connection
mysql -u photo_user -p photo_gallery

# Verify .env settings
cat /var/www/photo-gallery/.env | grep DB_
```

### Image Upload Not Working
```bash
# Check storage permissions
sudo chown -R www-data:www-data /var/www/photo-gallery/storage/app/public
sudo chmod -R 775 /var/www/photo-gallery/storage/app/public

# Verify storage link
ls -la /var/www/photo-gallery/public/storage
```

### HTTPS Not Working
```bash
# Check SSL certificate
sudo certbot certificates

# Renew certificate
sudo certbot renew --dry-run
```

---

## 📞 Support

For detailed instructions, see: `AWS_DEPLOYMENT.md`

Repository: https://github.com/Ei-Ayw/photo-gallery-test

---

**Good luck with your deployment! 🎉**
