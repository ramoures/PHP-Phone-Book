<?php
abstract class Backend{
    protected $Utils;
    protected $DB;
    public function __construct($className) {
        $this->Utils = Utils::getInstance();
        $this->DB = Database::getInstance();
        $this->checkAuth($className);
    }
    public function Render($filePath,$res){
        $data = $res;
        include_once(ROOT_PATH."view/backend/".BACKEND_THEME_DIR_NAME."/$filePath.php");
    }
    // public function getAdminProfile()
    // {
    //     $q = $this->DB->get();
    //     return $this->Utils->decode($q);
       
    // }
    public function adminIsLogin()
    {
        if(isset($_SESSION['admin_id']))
            return true;
        else
            return false;
    }
    public function checkAuth($className)
    {
        if($className!=='login'){
            if(!$this->adminIsLogin())
            $this->Utils->redirect("admin/login");
        }
       
        
    }
}
?>