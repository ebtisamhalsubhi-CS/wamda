#!/bin/sh
set -e

touch "$DB_DATABASE"
php artisan migrate --force

if [ ! -f "$(dirname "$DB_DATABASE")/seeded" ]; then
    php artisan db:seed --force
    touch "$(dirname "$DB_DATABASE")/seeded"
fi

if [ -x /start-container.sh ]; then
    exec /start-container.sh
fi

exec php artisan serve --host=0.0.0.0 --port="$PORT"
