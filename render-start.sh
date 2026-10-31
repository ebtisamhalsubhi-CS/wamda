#!/bin/sh
set -e

touch database/database.sqlite
php artisan migrate --force --seed

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
