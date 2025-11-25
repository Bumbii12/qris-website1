# ========= STAGE 1: Build asset frontend (Vite) =========
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy file package*
COPY package*.json ./

# Install dependency frontend
RUN npm install

# Copy semua source (buat akses resources/js, css, dll)
COPY . .

# Build asset Vite
RUN npm run build


# ========= STAGE 2: PHP + Laravel app =========
FROM php:8.2-cli-alpine

# Install ekstensi & tools dasar
RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    sqlite \
    oniguruma-dev \
    bash

# Ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring zip gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy file composer dulu (biar cache dependency kepakai)
COPY composer.json composer.lock ./

# Install dependency PHP (tanpa dev, untuk production)
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Copy source code Laravel
COPY . .

# Copy asset build dari stage node ke public/build
COPY --from=frontend /app/public/build ./public/build

# Buat direktori storage dan berikan permission
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# --- Kalau pakai SQLite, UNCOMMENT baris ini ---
# Buat file database SQLite kosong
RUN mkdir -p database && touch database/database.sqlite

# Laravel optimize (boleh, tapi optional)
RUN php artisan config:clear \
    && php artisan route:clear

# Expose port yang akan dipakai php artisan serve
EXPOSE 8000

# Command saat container start:
# 1. Jalankan migrate (idempotent, aman dijalankan berulang)
# 2. Jalankan server Laravel
CMD php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8000
