FROM php:8.2-apache

RUN a2enmod rewrite
RUN docker-php-ext-install mysqli

COPY . /var/www/html

RUN mkdir -p /var/www/html/uploads /var/www/html/uploads/photos /var/www/html/uploads/videos /var/www/html/uploads/songs /var/www/html/uploads/events \
    && chown -R www-data:www-data /var/www/html/uploads

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
