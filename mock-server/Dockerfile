FROM php:8.2-apache

# Enable mod_rewrite if needed
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY sleep.php .

EXPOSE 80
