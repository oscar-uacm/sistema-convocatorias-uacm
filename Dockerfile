FROM php:8.2-apache
# Instalamos la extensión necesaria para conectar PHP con MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql