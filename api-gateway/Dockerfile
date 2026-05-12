FROM php:8.3-fpm


RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql gd


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .


RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN mkdir -p /app/storage /app/cache

RUN chown -R www-data:www-data /app/storage /app/cache

EXPOSE 8080
CMD php artisan serve --host=0.0.0.0 --port=8080