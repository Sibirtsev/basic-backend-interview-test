FROM php:7.1-fpm

RUN apt-get update && apt-get upgrade -y && apt-get install -y libssl-dev pkg-config

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer

WORKDIR /var/www/mcmakler.de