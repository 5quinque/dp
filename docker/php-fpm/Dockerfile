FROM php:8.0-fpm
RUN apt-get update && \
    apt-get install -y unzip libzip-dev && \
    pecl install zip && \
    docker-php-ext-enable zip && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install opcache pdo_mysql && \
    addgroup --system php && adduser --system --no-create-home --group php
# also want `intl`, but get an error

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash && \
    apt-get install -y nodejs  && \
    npm install --global yarn && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY php.ini "$PHP_INI_DIR/php.ini"

USER php

WORKDIR /var/www

CMD composer install --optimize-autoloader; \
    php bin/console cache:clear; \
    yarn install; \
    yarn encore ${APP_ENV}; \
    php-fpm 

EXPOSE 9000
