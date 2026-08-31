# راهنمای توسعه

## آماده‌سازی

```powershell
composer install
npm install
php init --env=Development --overwrite=All
Copy-Item .env.example .env
php yii migrate --interactive=0
php yii seed
npm run build
```

برای build پیوسته CSS از `npm run dev` استفاده کنید.

## قواعد تغییر

- تغییر دیتابیس فقط با migration و بدون ویرایش migration اجراشده انجام شود.
- قابلیت مدیریتی permission و AccessControl داشته باشد.
- رشته رابط با Yii i18n ترجمه شود.
- رابط جدید در RTL، LTR، موبایل و صفحه‌کلید آزمایش شود.
- قابلیت جدید همراه تست و مستندات باشد.
- فایل تولیدشده، secret، upload واقعی یا asset runtime وارد Git نشود.

## کنترل کیفیت

```powershell
composer ci
npm audit --audit-level=moderate
npm run build
git diff --exit-code -- frontend/web/css/app.css
```

جزئیات تست‌ها در [tests/README.md](../tests/README.md) قرار دارد. برای یک تغییر محدود ابتدا تست مرتبط و پیش از Pull Request مجموعه کامل را اجرا کنید.
