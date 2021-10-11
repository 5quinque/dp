FROM php:8.0-fpm
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    pecl install zip && \
    docker-php-ext-enable zip && \
    rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install opcache pdo_mysql
# also want `intl`, but get an error

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash && \
    apt-get install -y nodejs  && \
    npm install --global yarn

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

# composer install --no-dev --optimize-autoloader 
CMD composer install ; \
    php bin/console cache:clear ; \
    yarn install ; \
    yarn encore ${APP_ENV} ; \
    php-fpm 

EXPOSE 9000