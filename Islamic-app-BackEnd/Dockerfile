# 1️⃣ اختر صورة PHP فيها Composer و Apache
FROM php:8.2-apache

# 2️⃣ فعّل بعض الإضافات المهمة
RUN docker-php-ext-install pdo pdo_mysql

# 3️⃣ انسخ ملفات المشروع إلى مجلد السيرفر
COPY . /var/www/html

# 4️⃣ إعداد مجلد العمل
WORKDIR /var/www/html

# 5️⃣ تثبيت المكتبات عبر Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install --no-dev --optimize-autoloader

# 6️⃣ إعداد Laravel
RUN php artisan key:generate

# 7️⃣ ضبط الأذونات (لـ storage و bootstrap/cache)
RUN chown -R www-data:www-data storage bootstrap/cache

# 8️⃣ استمع على المنفذ 8080
EXPOSE 8080

# 9️⃣ أمر التشغيل
CMD ["apache2-foreground"]
