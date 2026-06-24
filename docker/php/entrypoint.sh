#!/bin/sh
set -e

echo "[Entrypoint] Running Laravel optimizations..."

# Cache config, routes, views, events — eliminates parsing overhead per-request
php artisan optimize 2>/dev/null || true

echo "[Entrypoint] Laravel optimized successfully."

if [ ! -f "rr" ]; then
    echo "[Entrypoint] Installing RoadRunner binary..."
    php artisan octane:install --server=roadrunner || true
fi

# Execute the original CMD (php-fpm, queue:work, schedule:work, etc.)
exec "$@"
