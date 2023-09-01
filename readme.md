# PHP-Phone-Book
#### This is my first public project on github. 

[<img src="screenshot.png">](https://awaweb.ir/projects/free/php_phone_book)
[<img src="screenshot2.png">](https://awaweb.ir/projects/free/php_phone_book/admin)

___
### Demo
+ [Admin panel demo](https://awaweb.ir/projects/free/php_phone_book/admin) &#8628;
     > **username:** admin<br> 
     > **password:** 123  
+ [Front page demo](https://awaweb.ir/projects/free/php_phone_book)

### Setup

1. Create a new MySQL database.
2. Set your database information and your `PROJECT_URL` `/` in `config.php`.
3. Browse `setup.php` to create the required tables and admin signup.
   > Ex. `https://example.com/phone_book/setup.php`

___
### Information
+ HTTP Server: [Apache](https://httpd.apache.org/) 
+ Language: [PHP](https://www.php.net/) 8.2.4
+ Paradime: [OOP](https://en.wikipedia.org/wiki/Object-oriented_programming)
+ Architectural patterns: [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
+ Template Engine: [TWIG](https://twig.symfony.com/) 3.0
+ Database: [MySQL](https://www.mysql.com/)
* Licensed under [MIT](https://github.com/ramoures/PHP-Phone-Book/blob/main/LICENSE)

#### I Usesd:
+ Multi language suppourt.
+  *[PDO](https://www.php.net/manual/en/book.pdo.php) & [Prepared Statements](https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)* MySQL Connection.
+ Errors Handling method.
+ [Singleton](https://en.wikipedia.org/wiki/Singleton_pattern) [**design patterns**](https://en.wikipedia.org/wiki/Design_Patterns) for some required classes.
+ Many *options* for configuration. *`config.php`*
+ [Bootstrap](https://getbootstrap.com/) and [jQuery](https://jquery.com/).
+ Setup system. (setup files will be deleted after setup.)
___
### HELP
#### Add new language
1. Create your language file in the `lang` folder.
>Ex. *fr.php* or *ar.php* and develop similar to `lang/fa.php`.
2. Add your new language for frontend pages.<br>
```
<!-- Example: -->
<button id="fr" class="dropdown-item {% if language =='FR' %}active{% endif %} d-flex gap-2 align-items-center">
     <span class="bg-light p-1 border rounded-1 lh-1 text-dark">FR</span>
     {{'France'|lang}}
</button>
```
____

### ``** Ready to develop for other pure PHP projects **``



### Connect with me:
ramoures@gmail.com
