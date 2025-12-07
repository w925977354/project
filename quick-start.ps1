# Quick Start Script for Photo Gallery Testing
# This script helps you quickly set up and test the watermark feature

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  Photo Gallery - Quick Start Script  " -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

# Step 1: Check if database exists
Write-Host "[1/5] Checking database setup..." -ForegroundColor Yellow
php artisan migrate:status 2>$null

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Database not migrated yet" -ForegroundColor Red
    Write-Host "`nRunning migrations..." -ForegroundColor Yellow
    php artisan migrate
} else {
    Write-Host "✅ Database is ready" -ForegroundColor Green
}

# Step 2: Create storage link
Write-Host "`n[2/5] Creating storage symlink..." -ForegroundColor Yellow
php artisan storage:link 2>$null
Write-Host "✅ Storage link created" -ForegroundColor Green

# Step 3: Seed test users
Write-Host "`n[3/5] Creating test accounts..." -ForegroundColor Yellow
php artisan db:seed --class=AdminUserSeeder

# Step 4: Build assets
Write-Host "`n[4/5] Building frontend assets..." -ForegroundColor Yellow
Write-Host "(This may take a moment...)" -ForegroundColor Gray
npm run build 2>$null
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Assets built successfully" -ForegroundColor Green
} else {
    Write-Host "⚠️  Asset build failed, using dev mode" -ForegroundColor Yellow
}

# Step 5: Start server
Write-Host "`n[5/5] Starting development server..." -ForegroundColor Yellow
Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  🚀 Application is ready!  " -ForegroundColor Green
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "📝 Test Accounts:" -ForegroundColor Cyan
Write-Host "   👑 Admin:  admin@example.com / password" -ForegroundColor White
Write-Host "   👤 User:   user@example.com / password" -ForegroundColor White

Write-Host "`n🌐 Open your browser and visit:" -ForegroundColor Cyan
Write-Host "   http://localhost:8000" -ForegroundColor White

Write-Host "`n🎨 To test watermark feature:" -ForegroundColor Cyan
Write-Host "   1. Login with either account" -ForegroundColor White
Write-Host "   2. Click 'Upload Photo' button" -ForegroundColor White
Write-Host "   3. Upload an image" -ForegroundColor White
Write-Host "   4. Check the bottom-right corner for watermark!" -ForegroundColor White

Write-Host "`n📚 For detailed testing guide, see:" -ForegroundColor Cyan
Write-Host "   WATERMARK_TESTING.md" -ForegroundColor White

Write-Host "`n⌨️  Press Ctrl+C to stop the server`n" -ForegroundColor Yellow

# Start the server
php artisan serve
