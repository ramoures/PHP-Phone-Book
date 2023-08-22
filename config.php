<?php
ini_set("display_errors","on");
define('YEAR',date('Y'));
//Database Information:
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASWORD","");
define("DB_NAME","phone_book");
//Project Informations:
define("PROJECT_VERSION","0.1 Beta");
define("PROJECT_URL","https://localhost/phone_book/");
define("PROJECT_NAME","Phone-Book");
define("ADMIN_DIR_NAME","admin");
define("FRONTEND_THEME_DIR_NAME", "default");
define("BACKEND_THEME_DIR_NAME", "default");
//Project Setting:
define("DEBUG",true);
define('DEFAULT_TIMEZONE','ASIA/TEHRAN');
##security
define('SSL',true);
##important: please change values for your project:
define('SECRET_KEY','D96B2B6A02850F2BF085DC39632E6A'); 
define('SECRET_SAULT',['89sd6fd7s','098sddsfs','098dfd098','fghgfh90808']);
define('SAULT_ORDER',[3,0,1,2]);

define('SESSION_NAME',PROJECT_NAME);

//Backend Pages Setting
define("B_DEFAULT_LANG",'en');
define("B_LIMIT",'10'); 

//Frontend Pages Setting
define("DEFAULT_LANG",'en'); # cookie set before
define("LIMIT",'10'); # Limit post per pages


?>