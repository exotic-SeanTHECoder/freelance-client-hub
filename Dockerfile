FROM php:8.2-apache

# Install Postgres client libraries and PHP pgsql extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# Copy app
COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 80
