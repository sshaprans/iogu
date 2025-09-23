FROM php:8.2-apache

COPY dist/ /var/www/html/

RUN a2enmod rewrite

EXPOSE 80