FROM php:8.1-apache

# Instalacja rozszerzenia mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Kopiowanie plików aplikacji
COPY . /var/www/html/

# Włączenie modułu mod_rewrite (jeśli chcesz używać np. .htaccess)
RUN a2enmod rewrite

# Ustawienie uprawnień
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
