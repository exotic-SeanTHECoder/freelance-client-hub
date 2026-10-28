FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

# Enable mod_rewrite if needed later
RUN a2enmod rewrite

WORKDIR /var/www/html
