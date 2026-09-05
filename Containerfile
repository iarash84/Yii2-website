FROM node:24-alpine AS frontend-assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY frontend/web/css ./frontend/web/css
RUN npm run build

FROM composer:2 AS php-dependencies

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

FROM php:8.2-apache-bookworm AS runtime

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd intl mbstring opcache pdo_mysql zip \
    && a2enmod expires headers rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . .
COPY --from=php-dependencies /app/vendor ./vendor
COPY --from=frontend-assets /app/frontend/web/css/app.css ./frontend/web/css/app.css
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

ENV YII_ENV=prod \
    YII_DEBUG=0 \
    APP_ENV_FILE=/var/www/html/storage/config/.env \
    INSTALL_LOCK_FILE=/var/www/html/storage/config/.install.lock

RUN mkdir -p \
        console/runtime \
        frontend/runtime \
        frontend/web/assets \
        frontend/web/upload \
        storage/config \
        storage/resumes \
    && chown -R www-data:www-data \
        console/runtime \
        frontend/runtime \
        frontend/web/assets \
        frontend/web/upload \
        storage

VOLUME ["/var/www/html/storage", "/var/www/html/frontend/web/upload"]
EXPOSE 80
