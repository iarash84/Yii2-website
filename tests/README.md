# تست‌ها

مجموعه تست فعال پروژه با PHPUnit 10 در پوشه‌های `tests/unit` و
`tests/integration` قرار دارد. تست‌ها همیشه از دیتابیس مستقل
`yii2_website_test` استفاده می‌کنند و اطلاعات محیط توسعه را تغییر نمی‌دهند.

```powershell
php init --env=Development --overwrite=All
composer test:prepare
composer test
composer test:install
```

- `test:prepare` دیتابیس تست را از صفر با migrationها می‌سازد.
- `test` تست‌های واحد و یکپارچه را اجرا می‌کند.
- `test:install` نصب روی دیتابیس خالی را آزمایش و سپس دیتابیس موقت را حذف می‌کند.

کنترل‌های کیفیت دیگر:

```powershell
composer lint
composer style
composer analyse
composer security:audit
```

مجموعه قدیمی Codeception به‌دلیل اجرا نشدن در CI و پوشش همان سناریوها توسط PHPUnit حذف شده است. تست `LocalizationTest` مسیرهای زبان‌دار، RTL/LTR و fallback
ترجمه‌های دیتابیسی را پوشش می‌دهد.
