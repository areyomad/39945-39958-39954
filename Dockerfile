FROM php:8.1-apache

# Instalacja rozszerzenia mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Włączenie mod_rewrite (np. dla .htaccess)
RUN a2enmod rewrite

# Ustawienie uprawnień domyślnych (zostaną nadpisane przez mount w compose)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
