#!/usr/bin/env bash
#
# project.sh — быстрый старт Laravel-проекта в Docker
#
# Параметры:
#   --seed  : после миграций дополнительно запустить db:seed
#   --rebuild : принудительно пересобрать образы (docker compose build --no-cache)
#
set -euo pipefail

# ------------------------------------------------------------------------------
# 0. Константы и вспом. функции
# ------------------------------------------------------------------------------
COMPOSE="docker compose"
APP_DIR="app"
ENV_FILE="$APP_DIR/.env"
SEED=false
REBUILD=false

log()   { printf "\e[32m▶ %s\e[0m\n" "$*"; }
error() { printf "\e[31m❌ %s\e[0m\n" "$*" >&2; exit 1; }

# ------------------------------------------------------------------------------
# 1. Обработка аргументов
# ------------------------------------------------------------------------------
for arg in "$@"; do
  case $arg in
    --seed)    SEED=true ;;
    --rebuild) REBUILD=true ;;
    *)         error "Неизвестный параметр: $arg" ;;
  esac
done

# ------------------------------------------------------------------------------
# 2. Проверка .env
# ------------------------------------------------------------------------------
if [[ ! -f $ENV_FILE ]]; then
  log "Файл .env не найден — копирую .env.example"
  cp "$APP_DIR/.env.example" "$ENV_FILE"
fi

# ------------------------------------------------------------------------------
# 3. Сборка и запуск контейнеров
# ------------------------------------------------------------------------------
if $REBUILD; then
  log "Пересборка образов без кэша"
  $COMPOSE build --no-cache
fi

log "Запускаю контейнеры (detached)"
$COMPOSE up -d --build

# ------------------------------------------------------------------------------
# 4. Ожидание готовности MySQL
# ------------------------------------------------------------------------------
log "Жду, пока MySQL станет доступен…"
until $COMPOSE exec -T mysql mysqladmin \
       --user="${DB_USERNAME:-root}" \
       --password="${DB_PASSWORD:-root}" \
       --host=localhost ping &>/dev/null; do
  sleep 2
done
log "MySQL готов"

# ------------------------------------------------------------------------------
# 5. Artisan-команды (в контейнере php)
# ------------------------------------------------------------------------------
PHP_EXEC="$COMPOSE exec -T php"

log "Генерирую APP_KEY"
$PHP_EXEC php artisan key:generate --force

log "Кеширую конфигурацию"
$PHP_EXEC php artisan config:clear
$PHP_EXEC php artisan config:cache

log "Применяю миграции"
$PHP_EXEC php artisan migrate --force

if $SEED; then
  log "Запускаю сиды"
  $PHP_EXEC php artisan db:seed --force
fi

log "Создаю симлинк storage → public"
$PHP_EXEC php artisan storage:link || true

# ------------------------------------------------------------------------------
# 6. Готово
# ------------------------------------------------------------------------------
APP_URL=${APP_URL:-http://localhost:8080}
log "✅ Приложение доступно по адресу: $APP_URL"
