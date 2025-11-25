# ========== STAGE 1: FRONTEND (Vite build) ==========
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


# ========== STAGE 2: PHP + LARAVEL ==========
FROM php:8.2-cli-alpine AS app

# Dependensi OS
RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    sqlite \
    oniguruma-dev \
    bash \
 && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Penting: copy semua dulu supaya artisan ada
COPY . .

# Install dependency PHP
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Copy hasil build Vite dari stage frontend
COPY --from=frontend /app/public/build ./public/build

# (opsional) optimasi Laravel
# RUN php artisan config:cache \
#     && php artisan route:cache \
#     && php artisan view:cache

# Port default Railway (sesuaikan kalau beda)
EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
