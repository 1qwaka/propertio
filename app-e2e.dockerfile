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
RUN pecl install opentelemetry \
	&& docker-php-ext-enable opentelemetry

RUN composer install

CMD php artisan migrate:refresh --force && \
    php artisan db:seed --force && \
    php artisan serve --host=0.0.0.0 --port=8000
