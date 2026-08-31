# راهنمای مشارکت

از مشارکت در توسعه این پروژه استقبال می‌شود. هدف این راهنما این است که Issueها و Pull Requestها قابل بررسی، آزمایش و نگهداری باشند.

## پیش از شروع

1. Issueهای باز را جست‌وجو کنید تا موضوع تکراری نباشد.
2. برای تغییر بزرگ، ابتدا Issue بسازید و درباره محدوده و اثر آن توافق کنید.
3. مشکلات امنیتی را مطابق [SECURITY.md](SECURITY.md) خصوصی گزارش کنید.

## محیط توسعه

مراحل کامل در [راهنمای توسعه](docs/development.md) آمده است. خلاصه راه‌اندازی:

```powershell
composer install
npm install
php init --env=Development --overwrite=All
Copy-Item .env.example .env
php yii migrate --interactive=0
php yii seed
npm run build
```

## انتظار از هر تغییر

- فقط فایل‌های مرتبط با مسئله تغییر کنند.
- تغییر دیتابیس migration برگشت‌پذیر داشته باشد.
- مسیر مدیریتی جدید پشت RBAC و AccessControl قرار بگیرد.
- متن رابط با Yii i18n و ترجمه فارسی ارائه شود.
- قابلیت جدید تست خودکار و مستندات داشته باشد.
- رابط در RTL، LTR، موبایل و استفاده با صفحه‌کلید بررسی شود.
- secret، اطلاعات واقعی کاربران، upload و asset runtime وارد Git نشوند.

## کنترل کیفیت

پیش از Pull Request اجرا کنید:

```powershell
composer ci
npm audit --audit-level=moderate
npm run build
git diff --exit-code -- frontend/web/css/app.css
```

اگر اجرای بخشی ممکن نبود، علت را صریح در توضیح PR بنویسید.

## Pull Request

توضیح PR باید شامل مسئله، راه‌حل، روش آزمایش، تصویر تغییر رابط و اثر احتمالی بر migration، امنیت و نسخه‌های قبلی باشد. PR را تا حد ممکن کوچک نگه دارید و تغییر نامرتبط را جدا ارسال کنید.

ارسال مشارکت به معنی پذیرش [مجوز پروژه](LICENSE) و [آیین‌نامه رفتاری](CODE_OF_CONDUCT.md) است.
