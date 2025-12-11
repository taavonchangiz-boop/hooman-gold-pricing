# Hooman Gold Pricing - سیستم حرفه‌ای قیمت‌گذاری طلا

افزونه‌ای قدرتمند برای ووکامرس که قیمت لحظه‌ای طلا را از APIهای معتبر (مانند Navasan) fetch کرده و بر اساس وزن محصول، اجرت، سود و مالیات محاسبه می‌کند. مناسب برای فروشگاه‌های جواهرفروشی در ایران.

## ویژگی‌ها
- **استعلام لحظه‌ای**: اتصال به API Navasan یا سفارشی با cron job (هر ۵ دقیقه).
- **محاسبه خودکار**: Override قیمت محصولات ساده و variable بر اساس فرمول (وزن × قیمت طلا + اجرت + سود + مالیات).
- **فیلدهای سفارشی**: وزن و اجرت در پنل محصول.
- **نمایش**: Topbar ثابت و باکس specs در صفحه محصول.
- **امنیت**: لایسنس validation، SSL، nonces، encryption tokenها.
- **ادغام**: WooCommerce native + Elementor widget.
- **i18n**: پشتیبانی از پارسی (فایل .pot بسازید با WP-CLI).

## نصب
1. فایل‌ها را در `/wp-content/plugins/hooman-gold-pricing/` آپلود کنید.
2. افزونه را فعال کنید (WooCommerce الزامی).
3. در **تنظیمات > سیستم طلا**:
   - لایسنس کلیدی وارد کنید (از hoomanweb.ir).
   - Provider انتخاب کنید (Navasan پیشنهادی) و token وارد کنید.
   - درصدها را تنظیم کنید.
4. در محصولات، وزن و اجرت را وارد کنید (قیمت عادی را ۰ بگذارید).

## الزامات
- WordPress 5.0+
- WooCommerce 6.0+
- PHP 7.4+

## عیب‌یابی
- **قیمت آپدیت نمی‌شود**: Cron را چک کنید (`wp cron test` در WP-CLI). Cache را clear کنید.
- **لایسنس invalid**: با hoomanweb.ir تماس بگیرید.
- **خطای SSL**: سرور HTTPS داشته باشید.

## Changelog
- 3.5.0: Security hardening, i18n, cache.

## پشتیبانی
- وبسایت: [hoomanweb.ir](https://hoomanweb.ir)
- گیتهاب: [Issues](https://github.com/taavonchangiz-boop/hooman-gold-pricing/issues)

لایسنس: GPL v2. © Hooman Naghshi
