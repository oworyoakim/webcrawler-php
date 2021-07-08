FROM php:7.4-apache
RUN apt-get update && apt-get install -yq git sudo libpcre3-dev nano gcc
# psr
RUN pecl channel-update pecl.php.net && pecl install psr
# composer
RUN curl -S https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
COPY ./composer.* /var/www/html/

RUN /usr/local/bin/composer update --no-scripts --no-autoloader

COPY .env.example /var/www/html/.env

COPY ./ /var/www/html/

## Set permissions for storage and bootstrap/cache directories
RUN chmod -R 777 storage bootstrap/cache

## Run the post-install scripts manually
RUN composer dump-autoload && php artisan key:generate

EXPOSE 80
