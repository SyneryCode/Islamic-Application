# 1️⃣ استخدم صورة PHP 8.2 مع Apache
FROM php:8.2-apache

# 2️⃣ ثبّت الإضافات المطلوبة للـ Laravel
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# 3️⃣ انسخ إعدادات Apache الافتراضية
COPY . /var/www/html

# 4️⃣ مجلد العمل
WORKDIR /var/www/html

# 5️⃣ أضف Composer من الصورة الرسمية
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 6️⃣ تأكد من وجود composer.json
RUN if [ ! -f composer.json ]; then echo "composer.json not found"; exit 1; fi

# 7️⃣ ثبّت المكتبات
RUN composer install --no-interaction --no-dev --optimize-autoloader

# 8️⃣ إعداد Laravel Key (تجاهل الخطأ لو .env غير جاهز بعد)
RUN php artisan key:generate || true

# 9️⃣ إعداد الأذونات
RUN chown -R www-data:www-data storage bootstrap/cache

# 🔟 استمع على المنفذ 8080
EXPOSE 8080

# 🚀 شغّل Apache
CMD ["apache2-foreground"]
