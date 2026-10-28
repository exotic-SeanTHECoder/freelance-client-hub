FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

# Copy app
COPY . /var/www/html/

# Make sure config can be overridden by env if needed later
WORKDIR /var/www/html

EXPOSE 80
