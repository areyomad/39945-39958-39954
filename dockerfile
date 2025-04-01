FROM php:8.2-apache

# Instalacja potrzebnych rozszerzeń PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Włączenie mod_rewrite Apache
RUN a2enmod rewrite

# Ustawienie katalogu roboczego
WORKDIR /var/www/html