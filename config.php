<?php
/**
 * PHP Phone Book
 * @Version: 1.0
 * @Author: ramin moradi . github.com/ramoures
 * @Email: ramoures@gmail.com
 * @License: MIT
*/
//MySQL Information
    define("DB_HOST","localhost");
    define("DB_USER","root");
    define("DB_PASWORD","");
    define("DB_NAME","phone_book");

    define("TABLE_PREFIX","pb_");
//Project Informations
    define("PROJECT_URL","https://localhost/phone_book".DS);

//Project Setting
    define('PHONE_NUMBER_PATTERN','[0-9]{11}'); #Ex.09121234567
    // Default Directories
        define("ADMIN_DIR_NAME","admin");
        define("FRONTEND_THEME_DIR_NAME", "default");
        define("BACKEND_THEME_DIR_NAME", "default");
        define('UPLOAD_PATH',ROOT_PATH.'media'.DS);
        define('AVATARS_DIR_NAME',"avatars");
        define('IMAGES_DIR_NAME','images');
    // Password validation
        define('PASSWORD_PATTERN','(?=(.*[a-z]){1,})(?=(.*[A-Z]){1,})(?=(.*[0-9]){2,})(?=(.*[!@#$%^&*_=+\-]){2,}).{8,16}'); 
        # 8 minimum character, [a-z]=(1 min char.), [A-Z]=(1 min char.), [0-9]=(2 min number.), [!@#$%^&*_=+\-]=(2 min char.)
        # If you changed the pattern: Please find $('#newPassword') keyup function, in /view/assets/js/backend.js (validation UI)

        // define('PASSWORD_PATTERN',false); # Uncomment this define and delete previous define, to disable strong password.

    // Upload
        define('ALLOW_FILES_TYPE', array('image/jpg'=>'jpg','image/jpeg'=>'jpeg','image/png'=>'png'));
                                    # Mime types list: https://www.iana.org/assignments/media-types/media-types.xhtml
        define('MAX_FILE_SIZE','3000000'); # 3 Megabytes = 3000000 Byte
    // Date and Time
        define('TIMEZONE_TO_DISPLAY','UTC'); # Set ASIA/TEHRAN for Tehran timezone
                                             # List of Supported Timezones : https://www.php.net/manual/en/timezones.php
        define('DATE_FORMAT_TO_DISPLAY',"Y-m-d");
        define('TIME_FORMAT_TO_DISPLAY',"H:i:s");
        
        define('JALALI_CALENDAR',false); # true or false
    // Security
        define("DEBUG",false); # true or false
        define('SESSION_NAME',"Phone-Book");
        #important: please change values for your project:
        define('SECRET_KEY','D96B2B6A02850F2BF085DC39632E6A'); 
    // Backend Pages Setting
        define("B_DEFAULT_LANG",'en'); # cookie set before
        define("B_LIMIT",'10'); # Limit post per pages
    // Frontend Pages Setting
        define("DEFAULT_LANG",'en'); # cookie set before
        define("LIMIT",'10'); # Limit post per pages


?>