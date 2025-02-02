FROM php:8.2-fpm

WORKDIR /app

RUN apt update && apt install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpq-dev

COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_pgsql
RUN composer install
RUN echo '' > .env

CMD php artisan test --order-by=random --testsuite=E2E
