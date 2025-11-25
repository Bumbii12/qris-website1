# ============================
# 1) FRONTEND STAGE (VITE BUILD)
# ============================
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy file yang dibutuhkan untuk npm install dulu
COPY package*.json vite.config.* ./
COPY resources ./resources
COPY public ./public

RUN npm install
RUN npm run build

# ============================
# 2) PHP / LARAVEL STAGE
# ============================
FROM php:8.2-cli-alpine AS app

# Ekstensi PHP yang dibutuhkan Laravel + MySQL
RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    bash \
    mysql-client

RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy file composer dan install dependency
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Copy seluruh source code laravel
COPY . .

# Copy HASIL BUILD VITE ke public/build
COPY --from=frontend /app/public/build ./public/build

# Permission basic
RUN chmod -R 775 storage bootstrap/cache

# Default command: jalankan Laravel di port 8080 (untuk Railway)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
