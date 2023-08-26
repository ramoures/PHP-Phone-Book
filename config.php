<?php
/*
    Phone Book Ver.0.1 Beta . 
    Licensed MIT . 
    Created by: github.com/ramoures
*/
//Database Information:
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASWORD","");
define("DB_NAME","phone_book");
//Project Informations:
define("PROJECT_VERSION","0.1 Beta");
define("PROJECT_URL","https://localhost/phone_book/");
define("PROJECT_NAME","Phone-Book");

//Project Setting:
define("ADMIN_DIR_NAME","admin");
define("FRONTEND_THEME_DIR_NAME", "default");
define("BACKEND_THEME_DIR_NAME", "default");
define('UPLOAD_PATH',ROOT_PATH.'media'.DS);
define('VALID_UPLOAD_DIR_NAMES',array('avatars','images'));
define('AVATARS_DIR_NAME',"avatars");
define('IMAGES_DIR_NAME','images');
define('ALLOW_FILES_TYPE', array('image/jpg'=>'jpg','image/jpeg'=>'jpeg','image/png'=>'png'));
define('MAX_FILE_SIZE','3000000'); // 3 Megabytes = 3000000 Byte
define('PHONE_NUMBER_PATTERN','[0-9]{11}'); //Ex.09121234567
define('DEFAULT_TIMEZONE','ASIA/TEHRAN');
##security
define("DEBUG",true);
define('SSL',true);
define('SESSION_NAME',PROJECT_NAME);

##important: please change values for your project:
define('SECRET_KEY','D96B2B6A02850F2BF085DC39632E6A'); 
##important: please change values for your project:
define('SECRET_SAULT',['89sd6fd7s','098sddsfs','098dfd098','fghgfh90808']);
##important: please change values for your project:
define('SAULT_ORDER',[3,0,1,2]);


##Backend Pages Setting
define("B_DEFAULT_LANG",'en');
define("B_LIMIT",'5'); 

##Frontend Pages Setting
define("DEFAULT_LANG",'en'); # cookie set before
define("LIMIT",'10'); # Limit post per pages

define('YEAR',date('Y'));

?>