FROM php:fpm-alpine

# For PostgreSQL
RUN set -ex && apk --no-cache add postgresql-dev
RUN docker-php-ext-install pdo_pgsql 
###

# For MySQL
#RUN docker-php-ext-install pdo_mysql
###

COPY php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/public
