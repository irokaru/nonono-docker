FROM php:7.4-fpm
LABEL maintainer "irokaru"

ARG TZ

# install composer
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer
RUN apt-get update --fix-missing \
 && apt-get install -y git zip unzip vim sqlite \
 && cp /usr/share/zoneinfo/${TZ} /etc/localtime

RUN apt-get install -y apt-utils libfreetype6-dev libonig-dev libpq-dev zlib1g-dev libzip-dev libjpeg-dev libpng-dev \
    && docker-php-ext-install pdo_mysql mysqli zip \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

WORKDIR /var/www/html