#!/usr/bin/env bash
set -e

docker compose up -d --build
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate --seed
docker compose exec php php artisan config:cache

echo -e "\n✅  Application is running at http://localhost:${WEB_PORT:-8080}\n"
