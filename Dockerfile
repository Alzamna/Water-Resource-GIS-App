FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    curl

RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    intl \
    mbstring \
    zip

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install 

RUN chown -R www-data:www-data writable

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80