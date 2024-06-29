# دفترچه تلفن با PHP

فارسی | [English](./readme.md)

[<img src="screenshot.png">](https://awaweb.ir/projects/free/php_phone_book)
[<img src="screenshot2.png">](https://awaweb.ir/projects/free/php_phone_book/admin2023)

---

### دمو

> براساس زمان UTC است.

- [پنل مدیریت](https://awaweb.ir/projects/free/php_phone_book/admin2023) &#8628;
  > **username:** admin<br>  **password:** 123
- [صفحه‌ی اصلی دفترچه تلفن](https://awaweb.ir/projects/free/php_phone_book)

### طریقه‌ی نصب

۱. ابتدا دیتابیس خود را در mySQL بسازید.

۲. **اطلاعات دیتابیس‌**تان و `PROJECT_URL` خودتان را در فایل config.php وارد کنید.

> ۳. برای افزوده‌شدن خودکار جدول‌های دیتابیس و افزودن نام کاربری و رمز عبور مدیریت، کافی‌ست `/setup` را اجرا کنید.
>
> > مثال: `https://localhost/PHP-Phone-Book/setup`
>
> ۴. پس از نصب، پوشه setup را حذف کنید.
>
> #### یــا اینکه:
>
> ۳. پوشه `setup` را حذف کنید.
>
> ۴. فایل ‍`php_phone_book.sql` را در دیتابیس خود IMPORT کنید.
>
> > نام کاربری شما `admin` و رمز عبورتان `123` خواهد بود.

۵. دسترسی یا `permission` پوشه‌ی `media` را روی ‍777 قرار دهید.

> sudo chmod -R 777 media

حالا `PROJECT_URL` خود را اجرا کنید:

صفحه‌ی اصلی: https://localhost/PHP-Phone-Book/

پنل مدیریت: https://localhost/PHP-Phone-Book/admin2023

---

### اطلاعات برنامه

- وب سرور: [Apache](https://httpd.apache.org/)
- زبان برنامه: [PHP](https://www.php.net/) 8.2.4
- پارادایم برنامه: [OOP](https://en.wikipedia.org/wiki/Object-oriented_programming)
- الگوی معماری: [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
- موتور قالب‌ساز: [TWIG](https://twig.symfony.com/) 3.0
- دیتابیس: [MySQL](https://www.mysql.com/)

* لایسنس: [MIT](https://github.com/ramoures/PHP-Phone-Book/blob/main/LICENSE)

#### موارد به‌ کار رفته شده:

- پشتیبانی از سیستم چندزبانه.
- استفاده از _[PDO](https://www.php.net/manual/en/book.pdo.php) و [Prepared Statements](https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)_ در ارتباط با mySQL.
- خطاهای احتمالیِ مدیریت شده.
- استفاده از [Singleton](https://en.wikipedia.org/wiki/Singleton_pattern) [(**الگوی طراحی**)](https://en.wikipedia.org/wiki/Design_Patterns) برای برخی از class ها.
- چندین _option_ برای کانفیک برنامه در _`config.php`_.
- سیستم صفحه‌بندی و سیستم چیدمان دلخواه برای موارد اضافه شده.
- سیستم **captcha** برای ورود (سمت کلاینت و سرور).
  > دو مورد : _Google reCaptcha_ یا _Cloudflare Turnstile_
- استفاده از [Bootstrap](https://getbootstrap.com/) و [jQuery](https://jquery.com/).

---

### راهنمایی

#### افزودن زبان

1. فایل زبان خود را در پوشه‌ی `lang` بسازید:
   > مثال: _fr.php_ یا _ar.php_ که باید مثل فایل`lang/fa.php` توسعه دهید .
2. در html صفحه‌های خود، زبان تازه را اضافه کنید.

```
<!-- Example: -->
<div class="changeLanguage">
     <button id="fr">Fr</button>
     <button id="en">En</button>
</div>
<!-- و همچنین نگاه کنید به: .changeLanguage click function
 در:
 view/assets/js/app.js و backend.js  -->

```

---

### `** این برنامه برای سایر پروژه‌های خام PHP شما آماده است. می‌توانید هر طور که خواستید تغییرش دهید و موارد و سیستم‌های دلخواه خود را اضافه کنید **`

### ارتباط با من:

Linkedin: [ramoures](https://www.linkedin.com/in/ramoures/)<br>
E-mail: ramoures@gmail.com
