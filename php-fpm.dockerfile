FROM php:8.2-fpm

WORKDIR /var/www

RUN apt update && apt install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpq-dev \
    wget

COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_pgsql
RUN pecl install redis-5.3.7 \
	&& docker-php-ext-enable redis

RUN composer install --no-dev --optimize-autoloader

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN cat custom.ini >> "$PHP_INI_DIR/php.ini"
RUN cat fpm.conf > "/usr/local/etc/php-fpm.d/www.conf"

#RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chown -R www-data:www-data /var/www/
RUN chmod -R 775 /var/www/storage
RUN chmod -R 775 /var/www/bootstrap/cache
RUN chown www-data:www-data /var/www/database/database.sqlite
RUN chmod 777 /var/www/database/database.sqlite


RUN wget https://github.com/prometheus/node_exporter/releases/download/v1.8.2/node_exporter-1.8.2.linux-amd64.tar.gz \
    && tar -xvzf node_exporter-1.8.2.linux-amd64.tar.gz \
    && mv node_exporter-1.8.2.linux-amd64/node_exporter /usr/local/bin/ \
    && rm -rf node_exporter-1.8.2.linux-amd64

CMD cd /var/www && ./start_php_fpm.sh
#RUN echo '' > .env
#RUN php artisan key:generate
# this command breaks app somehow
#RUN php artisan optimize

