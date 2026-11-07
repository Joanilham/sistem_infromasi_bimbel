FROM php:8.3-fpm-alpine

RUN apk add --no-cache libpng-dev libjpeg-dev libzip-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo_mysql mbstring gd zip sockets \
    && pecl install redis && docker-php-ext-enable redis

WORKDIR /var/www/html

COPY vendor ./vendor
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY routes ./routes
COPY public ./public
COPY resources ./resources
COPY artisan composer.json composer.lock .env ./

RUN chown -R www-data:www-data /var/www/html

USER www-data
EXPOSE 9000
CMD ["php-fpm"]
