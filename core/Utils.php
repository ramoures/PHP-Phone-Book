<?php
final class Utils{
    use errors;
    private static $instance;
    private function __construct() {}
    static function getInstance(){
        if(self::$instance==null)
            self::$instance = new Utils();
        return self::$instance;
    }
    public function redirect($url){
        try {
            header("Location:$url");
            die;
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
    public function getLang($type='backend'):string{
        try {
            $cookieName = $type==='backend'?'phone_book_b_lang':'phone_book_lang';
            if(isset($_COOKIE[$cookieName])){
                $cookie = $this->encode($_COOKIE[$cookieName]);
                if(is_numeric($cookie) || strlen($cookie)>2)
                    return 'en';
                else
                    return strtolower($cookie);
            }
            else
                return 'en';
        } catch (\Throwable $th) {
            return 'en';
        }
    }
    public function post($key){
       try {
        if(isset($_POST[$key]) && !is_array($_POST[$key]))
            return trim($_POST[$key]);
        else
            return null;
       } catch (\Throwable $th) {
            return null;
       }
    }
    public function get($key){
        try {
            if(isset($_GET[$key]) && !is_array($_GET[$key]))
                return trim($_GET[$key]);
            else
                return null;
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function safeInt($int):int{
        try {
            return (!is_numeric($int) || is_nan($int) || $int<=0)?0:(int)$int;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    function encode($data) {
        try {
            if (is_array($data)) {
                return array_map(array($this,'encode'), $data);
            }
            if (is_object($data)) {
                $tmp = clone $data;
                foreach ( $data as $k => $var )
                    $tmp->{$k} = $this->encode($var);
                return $tmp;
            }
            return $data ? htmlentities(addslashes(trim($data)), ENT_QUOTES, 'UTF-8') : '';
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function decode($data) {
        if (is_array($data)) {
            return array_map(array($this,'decode'), $data);
        }
        if (is_object($data)) {
            $tmp = clone $data; 
            foreach ( $data as $k => $var )
                $tmp->{$k} = $this->decode($var);
            return $tmp;
        }
        return $data ? html_entity_decode(stripslashes($data),ENT_QUOTES,'UTF-8') : '';
    }
    public function renderNumber($asc=1,$limit,$page,$total){
        try {
            if($asc)
                $n = $total - ($limit * $page) + $limit + 1;                
            else
                $n = ($limit * $page) - $limit;
            return $n;

        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
}

?>