FROM php:8.2-apache

# Встановлюємо розширення для роботи з базою даних (PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY dist/ /var/www/html/

RUN a2enmod rewrite

# Налаштування прав доступу (опціонально, але корисно)
RUN chown -R www-data:www-data /var/www/html

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

EXPOSE 80