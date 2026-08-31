# راهنمای امنیت

این سند چک‌لیست مدیر سرور و توسعه‌دهنده است. روش ارسال گزارش خصوصی در [SECURITY.md](../SECURITY.md) آمده است.

## محیط عملیاتی

- HTTPS، `YII_ENV=prod` و `YII_DEBUG=0` الزامی‌اند.
- Document Root فقط `frontend/web` باشد.
- `.env`، backupها، logها و رزومه‌ها از وب قابل دسترسی نباشند.
- حساب دیتابیس کمترین مجوز لازم را داشته باشد.
- PHP، Yii و وابستگی‌ها مرتب به‌روزرسانی شوند.
- `.install.lock` پس از نصب حفظ شود.

## برنامه

- برای مسیر جدید مدیریتی permission مشخص و AccessControl تعریف کنید.
- عملیات ایجاد، ویرایش و حذف را با POST و CSRF انجام دهید.
- خروجی متنی را encode و HTML مجاز را sanitize کنید.
- آپلود را بر اساس MIME واقعی، پسوند و اندازه کنترل کنید.
- هیچ رمز، token یا محتوای حساس را log نکنید.
- آخرین `superAdmin` نباید حذف یا تنزل نقش داده شود.

## بررسی دوره‌ای

```powershell
composer security:audit
npm audit --audit-level=moderate
composer test
```

نسخه‌های پشتیبان را رمزنگاری و Restore را به‌صورت دوره‌ای در محیط جدا آزمایش کنید.
