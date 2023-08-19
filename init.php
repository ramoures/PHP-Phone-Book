<?php
define('DS',DIRECTORY_SEPARATOR);
define('ROOT_PATH',dirname(__FILE__).DS);

require_once ROOT_PATH.'config.php';
require_once ROOT_PATH.'controller/Render.php';
require_once ROOT_PATH.'controller/Errors.php';
require_once ROOT_PATH.'core/Database.php';
require_once ROOT_PATH.'core/Bootstrap.php';

?>