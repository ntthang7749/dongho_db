#!/bin/bash
set -e

cd /var/www/html

echo "=== Running Laravel startup tasks ==="

echo "--- Running migrations ---"
php artisan migrate --force

echo "--- Caching config/routes/views ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Linking storage ---"
php artisan storage:link 2>/dev/null || true

echo "--- Setting permissions ---"
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "=== Starting supervisord ==="
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/app.conf
