<?php
abstract class Backend extends Base{

    public function __construct($className) {
        parent::__construct($className);
        $this->checkAuth($className);
        parent::initTwig('backend');
    }
   
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
            $this->Utils->redirect(PROJECT_URL."admin/login");
        }
       
        
    }
}
?>