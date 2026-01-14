#!/bin/bash

# Word Game Deployment Script
# Run this on your server after initial setup

echo "========================================="
echo "  Word Game Deployment Script"
echo "========================================="
echo ""

# Navigate to application directory
cd /var/www/word-game || exit 1

# Check if running as root
if [ "$EUID" -eq 0 ]; then
  echo "❌ Please do not run this script as root"
  exit 1
fi

# Enable maintenance mode
echo "📦 Enabling maintenance mode..."
php artisan down || true

# Pull latest changes (if using Git)
if [ -d ".git" ]; then
    echo "🔄 Pulling latest changes from Git..."
    git pull origin main
fi

# Install/Update Composer dependencies
echo "📥 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Install/Update NPM dependencies
echo "📥 Installing NPM dependencies..."
npm ci

# Build frontend assets
echo "🔨 Building frontend assets..."
npm run build

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear and rebuild cache
echo "🗑️  Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Restart services
echo "🔄 Restarting services..."
sudo supervisorctl restart all
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx

# Disable maintenance mode
echo "✅ Disabling maintenance mode..."
php artisan up

echo ""
echo "========================================="
echo "  ✅ Deployment completed successfully!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Visit your website to verify it's working"
echo "2. Check logs: tail -f storage/logs/laravel.log"
echo "3. Monitor supervisor: sudo supervisorctl status"
echo ""
