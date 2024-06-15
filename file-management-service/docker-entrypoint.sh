#!/bin/bash
set -e

composer install --no-interaction

php artisan optimize:clear
php artisan migrate --force
php artisan db:seed --force

php artisan serve --host=0.0.0.0 --port=9000 &
serve_pid=$!

php artisan rabbitmq:consume &
consume_pid=$!

wait $serve_pid
wait $consume_pid