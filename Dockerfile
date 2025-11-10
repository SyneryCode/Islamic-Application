FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY . /var/www/html
WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --no-dev --optimize-autoloader
RUN php artisan key:generate || true
RUN chown -R www-data:www-data storage bootstrap/cache

# 🔧 إعداد Apache ليستخدم مجلد public
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

EXPOSE 8080
CMD ["apache2-foreground"]
