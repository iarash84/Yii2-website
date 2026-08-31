# راهنمای نصب

این راهنما نصب یک نسخه تازه را توضیح می‌دهد. برای ارتقای سایت فعال، از [راهنمای ارتقا](update.md) استفاده کنید.

## پیش‌نیازها

- PHP 8.2 سازگار با Composer و افزونه‌های `mbstring`، `openssl`، `pdo_mysql` و `fileinfo`
- MySQL یا MariaDB با پشتیبانی از `utf8mb4`
- Composer 2
- دسترسی نوشتن PHP به مسیرهای runtime، asset و upload

برای توسعه رابط کاربری، Node.js نسخه LTS و npm نیز لازم‌اند.

## نصب وب

```powershell
composer install --no-dev --optimize-autoloader
php init --env=Production --overwrite=All
```

Document Root را روی `frontend/web` تنظیم و `/install.php` را باز کنید. نصب‌کننده پیش‌نیازها، دسترسی پوشه‌ها و اتصال دیتابیس را بررسی می‌کند؛ سپس migrationها، حساب مدیر و تنظیمات اولیه را می‌سازد.

پس از پایان، وجود `.install.lock` را بررسی کنید. این فایل نباید حذف یا در Git ثبت شود.

## نصب CLI

```powershell
php yii install
```

فرمان به‌صورت تعاملی اطلاعات لازم را می‌پرسد. برای نصب خودکار، متغیرهای `DB_*`، `ADMIN_*`، `SITE_NAME` و `APP_URL` را پیش از اجرا تنظیم کنید. رمزها را در خط فرمان یا log چاپ نکنید.

## بررسی نتیجه

```powershell
php yii install/check
php yii migrate/history
```

صفحه اصلی، ورود مدیر، صفحات زبان‌دار و نوشتن در مسیرهای runtime را بررسی کنید. در production مقدارهای `YII_ENV=prod` و `YII_DEBUG=0` الزامی‌اند.
