#!/bin/bash
set -e

php artisan config:cache
php artisan route:cache

php artisan key:generate
php artisan jwt:secret

php artisan migrate --force
php artisan db:seed --force

php artisan serve --host=0.0.0.0 --port=9000