FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    git \
    unzip \
    zip \
    curl \
    sqlite \
    sqlite-dev

RUN docker-php-ext-install \
    pdo \
    pdo_sqlite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
