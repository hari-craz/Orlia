#!/bin/bash
# Install mysqli extension
docker-php-ext-install mysqli
docker-php-ext-enable mysqli

# Set permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# Start Apache
apache2-foreground
