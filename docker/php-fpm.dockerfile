FROM php:7.2-fpm
RUN apt-get update && \
    apt-get upgrade -y

RUN apt-get install -y --force-yes git unzip zlib1g-dev
RUN docker-php-ext-install zip pdo_mysql
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN composer global require hirak/prestissimo