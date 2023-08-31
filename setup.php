<?php
define('DS',DIRECTORY_SEPARATOR);
define('ROOT_PATH',dirname(__FILE__).DS);
require_once ROOT_PATH.'config.php';

try {
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASWORD);
    if($pdo){
        $pdo->exec("set names utf8mb4");
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        require_once "setup_html.php";
    }

} catch (Exception $e) {
    print "Connect Error! Please set your MySQL connect information in config.php";
}


?>