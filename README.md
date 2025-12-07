# 📸 Minimalist Photo Gallery

A beautiful and secure Laravel-based photo gallery application with advanced watermarking features and admin panel.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## ✨ Features

### 🎨 Core Features
- **User Authentication** - Secure registration and login with Laravel Breeze
- **Photo Upload** - Support for JPG, JPEG, PNG (max 2MB)
- **Smart Watermarking** 
  - Display watermark: Small corner watermark with uploader's name
  - Download watermark: Diagonal pattern for guest users
  - Original download: No watermark for authenticated users
- **Responsive Gallery** - Beautiful grid layout that works on all devices
- **Photo Management** - Edit, delete your own photos
- **Authorization** - Policy-based access control

### 👨‍💼 Admin Features
- **User Management** - Full CRUD operations for users
- **Photo Management** - Manage all uploaded photos
- **Dashboard Statistics** - Total users, photos, uploads today, top uploaders
- **Content Moderation** - Admins can delete any photo

### 🔒 Security Features
- CSRF protection
- SQL injection prevention
- XSS protection
- File upload validation
- Policy-based authorization
- HTTPS support

## 🚀 Quick Start

### Local Development (Windows)

```powershell
# Clone the repository
git clone https://github.com/Ei-Ayw/photo-gallery-test.git
cd photo-gallery-test

# Run the setup script
.\quick-start.ps1
```

The script will:
- ✅ Install Composer dependencies
- ✅ Configure environment
- ✅ Set up database
- ✅ Run migrations
- ✅ Create admin user
- ✅ Start development server

Access the application at: `http://localhost:8000`

**Default Admin Credentials:**
- Email: `admin@photogallery.com`
- Password: `admin123`

### Manual Setup

See [SETUP.md](SETUP.md) for detailed manual installation instructions.

## 🌐 AWS Deployment

### Automated Deployment (Recommended)

```bash
# Connect to your AWS Ubuntu server
ssh -i "your-key.pem" ubuntu@your-ec2-ip

# Download and run deployment script
wget https://raw.githubusercontent.com/Ei-Ayw/photo-gallery-test/main/deploy.sh
chmod +x deploy.sh
./deploy.sh
```

### Manual Deployment

See [AWS_DEPLOYMENT.md](AWS_DEPLOYMENT.md) for complete deployment guide.

**Quick Reference:** [DEPLOYMENT_QUICKSTART.md](DEPLOYMENT_QUICKSTART.md)

## 📋 Requirements

### Local Development
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (optional, for frontend assets)
- GD Library (for image processing)

### AWS Production
- AWS EC2 instance (Ubuntu 22.04 LTS)
- LAMP Stack (Apache, MySQL, PHP 8.3)
- SSL Certificate (Certbot)
- Domain name (recommended)

## 🎯 Technical Highlights

This project demonstrates:

1. **Laravel 11 Best Practices**
   - RESTful routing
   - Policy-based authorization
   - Eloquent ORM
   - Blade templating
   - Middleware

2. **Advanced Image Processing**
   - Dynamic watermark generation
   - GD Library integration
   - Conditional watermarking logic

3. **Security Implementation**
   - Input validation
   - CSRF protection
   - Authorization policies
   - Secure file uploads

4. **Professional Deployment**
   - AWS LAMP setup
   - HTTPS configuration
   - Production optimization
   - Automated deployment script

## 📖 Documentation

- [Setup Guide](SETUP.md) - Local development setup
- [Features Documentation](FEATURES.md) - Detailed feature list
- [Admin Panel Guide](ADMIN_PANEL.md) - Admin features overview
- [Watermark Implementation](WATERMARK_IMPLEMENTATION.md) - Technical details
- [AWS Deployment Guide](AWS_DEPLOYMENT.md) - Production deployment
- [Deployment Summary](DEPLOYMENT_SUMMARY.md) - Deployment checklist
- [Security Guide](SECURITY_FIX.md) - Security features

## 🏗️ Project Structure

```
photo-gallery/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PhotoController.php      # Photo CRUD & watermarking
│   │   │   ├── AdminController.php      # Admin panel
│   │   │   └── ProfileController.php    # User profile
│   │   └── Middleware/
│   │       └── AdminMiddleware.php      # Admin access control
│   ├── Models/
│   │   ├── User.php                     # User model
│   │   └── Photo.php                    # Photo model
│   └── Policies/
│       └── PhotoPolicy.php              # Photo authorization
├── database/
│   └── migrations/                      # Database migrations
├── resources/
│   └── views/
│       ├── photos/                      # Photo views
│       ├── admin/                       # Admin panel views
│       └── layouts/                     # Layout templates
├── routes/
│   └── web.php                         # Application routes
├── storage/
│   └── app/
│       └── public/
│           └── photos/                  # Uploaded photos
└── public/
    └── storage -> ../storage/app/public # Symbolic link
```

## 🎨 Screenshots

### Gallery View
![Gallery](docs/screenshots/gallery.png)

### Photo Detail with Watermark
![Photo Detail](docs/screenshots/photo-detail.png)

### Admin Dashboard
![Admin Dashboard](docs/screenshots/admin-dashboard.png)

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter PhotoUploadTest
```

## 🔄 Update Application

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🐛 Troubleshooting

### Common Issues

**500 Internal Server Error**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
php artisan cache:clear
```

**Image Upload Not Working**
```bash
sudo chown -R www-data:www-data storage/app/public/photos
sudo chmod -R 775 storage/app/public/photos
php artisan storage:link
```

**Database Connection Error**
- Check `.env` database credentials
- Ensure MySQL service is running
- Verify database exists

See [DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md) for more troubleshooting tips.

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 👨‍💻 Author

**Ayw**

## 🙏 Acknowledgments

- Laravel Framework
- Intervention Image Library
- Tailwind CSS
- Laravel Breeze

---

**Need help?** Check the documentation files or create an issue on GitHub.

**Ready to deploy?** Follow the [AWS Deployment Guide](AWS_DEPLOYMENT.md).
