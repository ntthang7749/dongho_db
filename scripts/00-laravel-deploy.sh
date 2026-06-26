#!/usr/bin/env bash

echo "=== Starting Laravel Deploy Script ==="

cd /var/www/html

echo "--- Installing Composer dependencies ---"
composer install --no-dev --optimize-autoloader

echo "--- Running Migrations ---"
php artisan migrate --force

echo "--- Caching config, routes, views ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Linking storage ---"
php artisan storage:link

echo "--- Setting permissions ---"
chmod -R 775 storage bootstrap/cache

echo "=== Deploy Script Finished ==="
