# وب‌سایت شرکتی مبتنی بر Yii2

این پروژه بر پایه Yii 2 Advanced ساخته شده و برای اجرا با PHP 8.2 و
Yii 2.0.55 به‌روزرسانی شده است.

## پیش‌نیازها

- PHP 8.2 به همراه افزونه‌های `mbstring`، `openssl`، `pdo_mysql` و `fileinfo`
- Composer 2
- MySQL یا MariaDB با پشتیبانی از `utf8mb4`

فعال‌کردن افزونه‌های `zip`، `intl` و `gd` نیز توصیه می‌شود. افزونه `gd` برای
نمایش CAPTCHA لازم است.

## نصب محلی

```powershell
composer install
php init --env=Development --overwrite=All
```

فایل تنظیمات محیطی را بسازید و مقادیر آن را تغییر دهید:

```powershell
Copy-Item .env.example .env
```

رمز دیتابیس و رمز مدیر فقط در `.env` قرار می‌گیرند و این فایل وارد Git نمی‌شود.
دیتابیس خالی را ایجاد کنید:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE yii2_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

سپس محیط و دسترسی پوشه‌ها را بررسی و migrationها را اجرا کنید:

```powershell
php yii install/check
php yii migrate --interactive=0
php yii seed
```

فرمان `seed` تنظیمات پایه، مدیر ارشد و در حالت پیش‌فرض یک نوشته نمونه ایجاد
می‌کند. برای نصب بدون محتوای نمونه از `php yii seed 0` استفاده کنید.

## اجرا

برنامه را با وب‌سرور داخلی PHP اجرا کنید:

```powershell
php yii serve --docroot=frontend/web 127.0.0.1:8080
```

- سایت: <http://127.0.0.1:8080/>
- مدیریت: <http://127.0.0.1:8080/admin>

در Apache یا XAMPP باید Document Root روی `frontend/web` تنظیم شود. ریشه مخزن
نباید به‌صورت عمومی در دسترس وب قرار بگیرد.

## بررسی سلامت نصب

```powershell
php requirements.php
php yii install/check
composer validate --no-check-publish
composer audit --locked
```

ساخت دیتابیس صرفاً از طریق migration انجام می‌شود و پروژه هیچ وابستگی‌ای به
فایل dump قدیمی ندارد.
