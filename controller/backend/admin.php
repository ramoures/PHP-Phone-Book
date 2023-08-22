<?php
class Admin extends Backend{
    private $model;
    private $object;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new AdminModel();
        $this->object['media_url'] = PROJECT_URL."view/assets";
        $this->object['language'] = strtoupper($this->language);
        $this->object['param'] = $param;
        $this->object['msg'] = $this->Utils->safeString($this->Utils->get('msg'));
        

    }
    
    public function signin(){
        if($this->adminIsSigned())
            $this->Utils->redirect(PROJECT_URL."admin");
        if(isset($_POST['btn_signin'])){
            $username= $this->Utils->safeString($this->Utils->post('username'));
            $password= $this->Utils->safeString($this->Utils->post('password'));
            $this->setVar('admin/signin','username',$username);
            $this->setVar('admin/signin','password',$password);
            if($username=='')
                $this->Utils->redirect(PROJECT_URL."admin/signin?msg=err1");
            else
            if($password=='')
                $this->Utils->redirect(PROJECT_URL."admin/signin?msg=err2");
            else{
                $resultSignin = $this->model->signin($username,$this->encrypt($password));
                if($resultSignin){
                    $this->delVar('username');
                    if($this->adminIsSigned())
                        $this->Utils->redirect(PROJECT_URL."admin");
                }
                else
                    $this->Utils->redirect(PROJECT_URL."admin/signin?msg=err3");
            }
            
        }

        $this->object['username'] = $this->getVar('admin/signin','username');

        $this->Render('signin',$this->object);
    }
    public function home(){
        $this->Render('phoneBook',$this->object);
    } 
    public function add(){
        $this->Render('add',$this->object);
    }
    public function signout(){
        unset($_SESSION['admin_id']);
        $this->Utils->redirect(PROJECT_URL."admin/signin?msg=signout");
    } 
}
?>