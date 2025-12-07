# Quick Setup Guide

Follow these steps to set up and run the Photo Gallery application:

## Step 1: Start MySQL Server

Make sure your MySQL server is running:
- **XAMPP**: Start MySQL from the XAMPP Control Panel
- **Standalone MySQL**: Run `net start MySQL80` in PowerShell (as Administrator)

## Step 2: Create Database

Run this command in PowerShell (in the photo-gallery directory):

```powershell
mysql -u root -p123456 -e "CREATE DATABASE IF NOT EXISTS photo_gallery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

If your MySQL password is different, replace `123456` with your password.

## Step 3: Run Migrations

```powershell
php artisan migrate
```

This will create all necessary database tables.

## Step 4: Create Storage Link

```powershell
php artisan storage:link
```

This creates a symbolic link for uploaded photos.

## Step 5: Build Frontend Assets

```powershell
npm run build
```

Or for development with hot reload:
```powershell
npm run dev
```

## Step 6: Start the Application

```powershell
php artisan serve
```

Visit: **http://localhost:8000**

## Step 7: Create Your First Admin User

1. Register a new user through the web interface (http://localhost:8000/register)
2. Open a new PowerShell window and run:

```powershell
php artisan tinker
```

Then in tinker:
```php
$user = App\Models\User::where('email', 'your-email@example.com')->first();
$user->is_admin = true;
$user->save();
exit
```

Replace `your-email@example.com` with the email you registered with.

## Testing the Application

### As a Guest User:
- ✅ View photo gallery (homepage)
- ❌ Cannot upload photos
- ❌ Cannot delete photos

### As a Regular User:
- ✅ View photo gallery
- ✅ Upload photos with automatic watermark
- ✅ Delete only YOUR OWN photos
- ❌ Cannot delete other users' photos

### As an Administrator:
- ✅ View photo gallery
- ✅ Upload photos with automatic watermark
- ✅ Delete ANY photo (for content moderation)

## Common Issues & Solutions

### Issue: MySQL Connection Failed
**Solution**: Make sure MySQL is running and credentials in `.env` match your setup.

### Issue: Storage link already exists
**Solution**: 
```powershell
Remove-Item public/storage -Force
php artisan storage:link
```

### Issue: Permission denied on uploads
**Solution**: Make sure `storage/app/public/photos` directory is writable

### Issue: Photos not displaying
**Solution**: Check that the storage link exists and points to the correct location

## Next Steps

1. **Test Photo Upload**: 
   - Login and click "Upload Photo"
   - Select a JPG or PNG file (max 2MB)
   - Your username will be added as a watermark

2. **Test Authorization**:
   - Try deleting your own photo (should work)
   - Create another user account
   - Try deleting another user's photo (should fail for regular users)
   - Set `is_admin=true` and try again (should work)

3. **Explore Features**:
   - Responsive design (resize browser window)
   - Pagination (upload 13+ photos)
   - Form validation (try uploading invalid files)
   - Edit photo details

## File Structure

```
photo-gallery/
├── app/                    # Application logic
│   ├── Http/Controllers/  # PhotoController with watermarking
│   ├── Models/            # User & Photo models
│   └── Policies/          # PhotoPolicy for authorization
├── database/migrations/   # Database schema
├── resources/views/       # Blade templates
│   └── photos/           # Gallery views
├── routes/web.php        # All routes
├── storage/app/public/   # Uploaded photos location
└── public/storage/       # Symlink to storage
```

## Important Notes

- ✅ All uploaded photos automatically get watermarked
- ✅ Files must be JPG or PNG format
- ✅ Maximum file size is 2MB
- ✅ Photos are stored in `storage/app/public/photos/`
- ✅ Database stores relative paths for cross-platform compatibility
- ✅ Authorization is handled by PhotoPolicy (@can directive in views)

Enjoy your Photo Gallery! 📸
