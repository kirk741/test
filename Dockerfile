# ----------------------
# Stage 0: PHP + Composer
# ----------------------
FROM php:8.2-fpm AS base

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql zip

# Устанавливаем Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /app

# Копируем composer файлы сначала (для кеширования)
COPY composer.json composer.lock ./

# ----------------------
# Stage 1: Установка зависимостей
# ----------------------
# Сначала копируем .env.example в .env, чтобы artisan key:generate не падал
RUN cp .env.example .env

# Устанавливаем PHP-зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Генерируем APP_KEY
RUN php artisan key:generate --force

# ----------------------
# Stage 2: Копирование приложения
# ----------------------
COPY . .

# Устанавливаем права на storage и bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ----------------------
# Stage 3: Запуск
# ----------------------
EXPOSE 9000
CMD ["php-fpm"]
