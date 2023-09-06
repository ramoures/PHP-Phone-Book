<?php
final class Bootstrap{
    use errors;
    private static $instance;
    private function __construct(){
        $this->init();
        $this->errorReporting();       
        $this->routing();
    }
    static function run(){
        if(self::$instance==null)
            self::$instance = new Bootstrap();
        return self::$instance;
    }
    private function errorReporting(){
        try {
            error_reporting(-1);
            if(DEBUG)
                ini_set("display_errors",1); //develop mode
            else{
                //pro mode
                ini_set("display_errors",0);
                $logFileName = date("Y-m-d").".log";
                ini_set("error_log",ROOT_PATH."logs/$logFileName");
            }
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
    private function init(){
       try {
            date_default_timezone_set('UTC');
            ob_start();
            header('X-Frame-Options: DENY');
            header('X-XSS-Protection: 1; mode=block');
            header('X-Content-Type-Options: nosniff');
       } catch (\Throwable $th) {
            return $this->error($th);
       }
    }
    private function initSession(){
       try {
            if(isset($_COOKIE[SESSION_NAME])){
                if(!preg_match('/^[a-zA-Z0-9]{22,40}$/',$_COOKIE[SESSION_NAME]))
                    die('Security error!');
            }
            if(!isset($_SESSION)){
                ini_set('session.cookie_samesite','Strict');
                ini_set('session.cookie_httponly',1);
                ini_set('session.use_only_cookies', 1);
                ini_set('session.hash_function','sha1');
                ini_set('session.cookie_secure',1);
                ini_set('session.name',SESSION_NAME);
                session_start();
            }
       } catch (\Throwable $th) {
            return $this->error($th);
       }
    }
    private function routing(){
        try {
            $requestPath = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO']) : '';
            $requestPathArray = explode('/',$requestPath);
            array_shift($requestPathArray);
            $requestPathArray = array_map('trim',$requestPathArray);
            $requestPathArray = array_filter($requestPathArray, fn($value) => trim($value) !== '');
            $type='';
            if(count($requestPathArray) > 0 && $requestPathArray[0] == ADMIN_DIR_NAME ){
                //Backend
                $this->initSession();
                $type='backend';
                $requestPathArray[0] = 'admin';
                $routeName = $requestPathArray;
            }
            else{
                //Frontend
                $type='frontend';
                $routeName = $requestPathArray??$requestPathArray;
            }
            $this->dispatcher($type,$routeName);
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
    private function dispatcher($type,$route){
        try {
            if($type==='backend'){
                //Backend
                if(count($route)>3)
                    return $this->error();
                $file = isset($route[2])?$route[2]:'home';
                $folder = $this->convertString(isset($route[1])?$route[1]:$route[0]);
                $file = $route[count($route)-1]==='signin'||$route[count($route)-1]==='signout'?'home':$file;
                $folder = $route[count($route)-1]==='signin'||$route[count($route)-1]==='signout'?'admin':$folder;
                $routeFile = ROOT_PATH."controller/backend/".$folder."/".$file.".php";
                $modelFile = ROOT_PATH."model/backend/".$folder."/".$file.".php";
                if(!file_exists($modelFile) && !file_exists($routeFile))
                    return $this->error();
                require_once($modelFile);
                require_once($routeFile);
                $method = $this->convertString(count($route)===3?$route[count($route)-1]."_".$route[count($route)-2]:$route[count($route)-1]);
                $className = ucwords($method);
                $className = $route[count($route)-1]==='signin'||$route[count($route)-1]==='signout'?'admin':$className;
                $instanceController = new $className(['type'=>'backend','method'=>$method]);
                $isCallableMethod = array($instanceController,$method);
                if(!is_callable($isCallableMethod))
                    return $this->error();
                else  call_user_func($isCallableMethod);
            }
            else{
                //Frontend
                $route[0] = !isset($route[0])?'home':$route[0];
                $method = isset($route[1])?$route[1]:'index';
                $routeFile = ROOT_PATH."controller/frontend/".$route[0].".php";
                $modelFile = ROOT_PATH."model/frontend/".$route[0].".php";
                if(!file_exists($routeFile) || !file_exists($modelFile) )
                    return $this->error();
                require_once($modelFile);
                require_once($routeFile);
                $className = ucwords($route[0]);
                $instanceController = new $className(['type'=>'frontend','method'=>$method]);
                $isCallableMethod = array($instanceController,$method);
                if(!is_callable($isCallableMethod))
                    return $this->error();
                else  call_user_func($isCallableMethod);
                
            }
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
    private function convertString($str) : string {
        $str = explode('_',$str);
        $str = array_map(function ($str){return ucwords($str); }, array_values($str));
        $str = implode($str);
        return lcfirst($str);
    }
}

?>