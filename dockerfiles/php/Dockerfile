###############################################################################
# dockerfiles/php/Dockerfile
###############################################################################
# 1) глобальный ARG, доступен во всех стадиях
ARG PHP_VERSION=8.3

###############################################################################
# 2) stage: vendor  – тянем зависимости Composer
###############################################################################
FROM composer:2.7 AS vendor

WORKDIR /app
COPY app/composer.json app/composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress

###############################################################################
# 3) stage: runtime – финальный образ PHP-FPM
###############################################################################
FROM php:${PHP_VERSION}-fpm-alpine

# повторяем ARG, если хотим использовать его внутри stage
ARG PHP_VERSION

# ---- build-time + run-time зависимости -------------------------------------
RUN set -eux \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
    && apk add --no-cache --virtual runtime \
        icu-libs \
        libzip \
        libpng \
        libjpeg-turbo \
        freetype \
    \
    # GD: собираем с поддержкой JPEG и FreeType
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    \
    # компиляция расширений, -j$(nproc) = параллельно
    && docker-php-ext-install -j$(nproc) intl pdo_mysql zip gd opcache \
    \
    # очистка мусора
    && docker-php-source delete \
    && apk del .build-deps

# ---- рабочая директория приложения -----------------------------------------
WORKDIR /var/www/html
COPY --from=vendor /app/vendor vendor
COPY app .

# ---- кастомный php.ini ------------------------------------------------------
COPY dockerfiles/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini
