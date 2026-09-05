# انتشار نسخه جدید

انتشار پروژه کاملاً بر اساس تگ نسخه انجام می‌شود. Push معمولی روی branchها هیچ Release یا image جدیدی ایجاد نمی‌کند. تگ باید با الگوی Semantic Versioning و پیشوند `v` باشد؛ برای مثال `v1.2.0` یا `v1.2.0-rc.1`.

پیش از ساخت خروجی، workflow اصلی CI به‌طور کامل اجرا می‌شود. اگر هر تست یا کنترل کیفیتی ناموفق باشد، نه GitHub Release ساخته می‌شود و نه image کانتینر منتشر خواهد شد.

## ایجاد Release

ابتدا مطمئن شوید commit موردنظر روی branch اصلی قرار دارد، سپس تگ annotated را ایجاد و ارسال کنید:

```powershell
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```

workflow فایل `.github/workflows/release.yml` خروجی‌های زیر را می‌سازد:

- آرشیو نصب‌پذیر `Yii2-KamanCms-<version>.zip`
- آرشیو نصب‌پذیر `Yii2-KamanCms-<version>.tar.gz`
- فایل `SHA256SUMS` برای بررسی صحت دانلودها
- image چندمعماری `linux/amd64` و `linux/arm64` در GitHub Container Registry

برای نسخه پایدار، image با تگ‌های کامل، minor، major و `latest` منتشر می‌شود. برای prerelease مانند `v1.2.0-rc.1` تگ `latest` تغییر نمی‌کند و GitHub Release نیز به‌عنوان prerelease ثبت می‌شود.

## ساخت و اجرای کانتینر

ساخت محلی با Containerfile:

```powershell
docker build -f Containerfile -t yii2-kamancms:local .
```

نمونه اجرای نسخه منتشرشده:

```powershell
docker run --name yii2-kamancms -p 8080:80 `
  -v yii2-kamancms-storage:/var/www/html/storage `
  -v yii2-kamancms-upload:/var/www/html/frontend/web/upload `
  ghcr.io/iarash84/yii2-kamancms:latest
```

سپس installer از `http://127.0.0.1:8080/install.php` در دسترس است. دیتابیس باید جداگانه در دسترس کانتینر باشد؛ اگر MySQL در کانتینر دیگری اجرا می‌شود، هر دو را روی یک Docker network قرار دهید و نام کانتینر MySQL را به‌عنوان `DB_HOST` وارد کنید.

دو volume بالا ضروری‌اند: اولی تنظیمات تولیدشده، lock نصب، رزومه‌ها و داده‌های داخلی را نگه می‌دارد و دومی فایل‌های uploadشده را حفظ می‌کند. در نتیجه recreate یا upgrade کانتینر موجب از دست رفتن نصب نمی‌شود.

## دسترسی‌های GitHub

workflow از `GITHUB_TOKEN` داخلی GitHub Actions استفاده می‌کند و secret جداگانه‌ای لازم ندارد. در تنظیمات repository، دسترسی Workflow permissions باید امکان نوشتن package و Release را داشته باشد. برای repositoryهای خصوصی، visibility بسته GHCR را نیز متناسب با روش استقرار تنظیم کنید.

تگ منتشرشده را جابه‌جا یا دوباره استفاده نکنید. برای هر تغییر نسخه، یک شماره نسخه جدید بسازید تا Releaseها و imageها قابل ردیابی باقی بمانند.
