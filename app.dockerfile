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

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN echo "opcache.enable=1\nopcache.enable_cli=1\nopcache.jit_buffer_size=256M" >> "$PHP_INI_DIR/php.ini"

CMD php artisan migrate:refresh --force && php artisan serve --host=0.0.0.0 --port=8000
