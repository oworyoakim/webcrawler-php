FROM php:7.4-apache
RUN apt-get update && apt-get install -yq zip unzip git sudo libpcre3-dev nano gcc
# psr
RUN pecl channel-update pecl.php.net && pecl install psr
# composer
RUN curl -S https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY ./composer.* /var/www/html/

RUN /usr/local/bin/composer update --no-scripts --no-autoloader

COPY . /var/www/html/

## Set permissions for storage and bootstrap/cache directories
RUN chmod -R 777 storage bootstrap/cache

## Run the post-install scripts manually
RUN composer dump-autoload \
    && composer run-script post-root-package-install \
    && composer run-script post-create-project-cmd

COPY ./php-apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN service apache2 restart

EXPOSE 80
