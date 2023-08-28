<?php
define('DS',DIRECTORY_SEPARATOR);
define('ROOT_PATH',dirname(__FILE__).DS);

require_once ROOT_PATH.'config.php';
require_once ROOT_PATH.'lang/calendar/jalali.php';
require_once ROOT_PATH.'vendor/autoload.php';
require_once ROOT_PATH.'controller/Errors.php';
require_once ROOT_PATH.'core/Utils.php';
require_once ROOT_PATH.'core/Models.php';
require_once ROOT_PATH.'core/Bootstrap.php';
require_once ROOT_PATH.'controller/Base.php';
require_once ROOT_PATH.'controller/Backend.php';
require_once ROOT_PATH.'controller/Frontend.php';
require_once ROOT_PATH.'core/Database.php';

new Bootstrap();
?>