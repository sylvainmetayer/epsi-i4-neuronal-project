FROM php:7.2-apache

# see https://stackoverflow.com/a/36908278
RUN usermod -u 1000 www-data

RUN apt-get update && apt-get install -y \
        libpng-dev


RUN docker-php-ext-install gd && docker-php-ext-enable gd

ENV ENVIRONMENT PROD

WORKDIR /var/www/