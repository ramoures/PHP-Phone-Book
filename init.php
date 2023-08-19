<?php
define('DS',DIRECTORY_SEPARATOR);
define('ROOT_PATH',dirname(__FILE__).DS);

require_once ROOT_PATH.'config.php';
require_once ROOT_PATH.'controller/Errors.php';
require_once ROOT_PATH.'core/Bootstrap.php';
require_once ROOT_PATH.'controller/BackendRoute.php';
require_once ROOT_PATH.'core/Database.php';

new Bootstrap();
?>