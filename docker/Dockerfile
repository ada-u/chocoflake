FROM php:8.2.0RC3-cli

RUN pecl install redis-5.3.4 \
    && pecl install xdebug-3.0.4 \
    && docker-php-ext-enable redis xdebug

RUN docker-php-ext-install sysvsem
RUN docker-php-ext-install sysvshm
