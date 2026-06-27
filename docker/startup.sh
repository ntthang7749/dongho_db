#!/bin/bash
set -e

cd /var/www/html

echo "=== Running Laravel startup tasks ==="

echo "--- Running migrations ---"
php artisan migrate --force

echo "--- Checking if DB needs seeding (first deploy only) ---"
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1 | tr -d '[:space:]')
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "--- DB is empty, seeding initial data ---"
    php artisan db:seed --force
else
    echo "--- DB already has data ($USER_COUNT users), skipping seed to preserve existing data ---"
fi

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
