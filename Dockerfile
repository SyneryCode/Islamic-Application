# 1️⃣ استخدم صورة PHP مع Apache
FROM php:8.2-apache

# 2️⃣ فعّل امتدادات Laravel المطلوبة
RUN docker-php-ext-install pdo pdo_mysql

# 3️⃣ انسخ ملفات المشروع
COPY . /var/www/html

# 4️⃣ حدد مجلد العمل
WORKDIR /var/www/html

# 5️⃣ أضف Composer الرسمي
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 6️⃣ ثبّت المكتبات
RUN composer install --no-dev --optimize-autoloader

# 7️⃣ إعداد Laravel
RUN php artisan key:generate || true

# 8️⃣ صلاحيات التخزين
RUN chown -R www-data:www-data storage bootstrap/cache

# 9️⃣ الاستماع على المنفذ
EXPOSE 8080

# 🔟 تشغيل Apache
CMD ["apache2-foreground"]
