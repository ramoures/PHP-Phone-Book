# PHP-Phone-Book
#### This is my first public repository on github. 

[<img src="screenshot.png">](https://awaweb.ir/projects/free/php_phone_book)
[<img src="screenshot2.png">](https://awaweb.ir/projects/free/php_phone_book/admin)

___
### Demo 
> UTC Timezone
+ [Admin panel demo](https://awaweb.ir/projects/free/php_phone_book/admin) &#8628;
     > **username:** admin<br> 
     > **password:** 123  
+ [Front page demo](https://awaweb.ir/projects/free/php_phone_book)

### Setup
1. Change `media` directory permission to 777 
   > Ex. `sudo chmod -R 777 /opt/lampp/htdocs/PHP-Phone-Book/media`
2. Create a new MySQL database.
3. Set your **database information** and your `PROJECT_URL` in `config.php`.
4. Browse `/setup` to create the required tables and admin sign up.
   > Ex. `https://example.com/PHP-Phone-Book/setup/`
5. Remove `/setup` directory.
___
### Information
+ HTTP server: [Apache](https://httpd.apache.org/) 
+ Programming language: [PHP](https://www.php.net/) 8.2.4
+ Programming paradigm: [OOP](https://en.wikipedia.org/wiki/Object-oriented_programming)
+ Architectural patterns: [MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
+ Template engine: [TWIG](https://twig.symfony.com/) 3.0
+ Database: [MySQL](https://www.mysql.com/)
* Licensed under [MIT](https://github.com/ramoures/PHP-Phone-Book/blob/main/LICENSE)

#### I used:
+ Multi language suppourt.
+  *[PDO](https://www.php.net/manual/en/book.pdo.php) & [Prepared Statements](https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)* MySQL Connection.
+ Errors handling method.
+ [Singleton](https://en.wikipedia.org/wiki/Singleton_pattern) [**design patterns**](https://en.wikipedia.org/wiki/Design_Patterns) for some required classes.
+ Many *options* for configuration. *`config.php`*
+ Sorting and pagination of items.
+ Client and server side **captcha** for sign in form.
+ [Bootstrap](https://getbootstrap.com/) and [jQuery](https://jquery.com/).
___
### HELP
#### Add new language
1. Create your language file in the `lang` folder.
>Ex. *fr.php* or *ar.php* and develop similar to `lang/fa.php`.
2. Add your new language for frontend pages.<br>
```
<!-- Example: -->
<div class="changeLanguage">
     <button id="fr">Fr</button>
     <button id="en">En</button>
</div>
<!-- Look at: .changeLanguage click function on view/assets/js/app.js or backend.js  -->

```
____

### ``** Ready to develop for other pure PHP projects **``


### Connect with me:
Linkedin: [ramoures](https://www.linkedin.com/in/ramoures/)<br>
E-mail: ramoures@gmail.com
