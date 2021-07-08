FROM php:7.4-apache
RUN apt-get update && apt-get install -yq git sudo libpcre3-dev nano gcc
# psr
RUN pecl channel-update pecl.php.net && pecl install psr
# phalcon
# RUN pecl install phalcon
# composer
#RUN curl -S https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
# phalcon dev tools
#RUN composer global require phalcon/devtools
COPY . /var/www/html/
EXPOSE 80