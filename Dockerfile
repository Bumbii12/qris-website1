# syntax=docker/dockerfile:1

############################
# 1) FRONTEND (VITE BUILD)
############################
FROM node:20-alpine AS frontend

WORKDIR /app

# File yang dibutuhkan untuk npm install
COPY package*.json vite.config.* ./

RUN npm install

# Copy source untuk build (Blade, JS, CSS, dll)
COPY resources ./resources
COPY public ./public

# Build asset Vite -> public/build
RUN npm run build


############################
# 2) PHP / LARAVEL APP
############################
FROM php:8.2-cli-alpine AS app

# Paket sistem yang dibutuhkan Laravel + MySQL
RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    bash \
    mysql-client

# Ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Copy binary composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Root aplikasi Laravel
WORKDIR /var/www/html

# ⬅ PENTING: copy SELURUH project (supaya file artisan, route, view, dll ada)
COPY . .

# Copy hasil build Vite dari stage frontend
COPY --from=frontend /app/public/build ./public/build

# Install dependency PHP untuk production
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Port yang akan dipakai di Railway (Networking → Target port = 8080)
EXPOSE 8080

# Jalankan Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
