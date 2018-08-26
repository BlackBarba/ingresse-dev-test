FROM php:fpm
RUN apt-get update && \
    apt-get upgrade -y

RUN apt-get install -y --force-yes git unzip zlib1g-dev
RUN docker-php-ext-install zip pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer