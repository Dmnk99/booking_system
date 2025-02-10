FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install zip pdo pdo_mysql intl

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/memory-limit.ini