<?php
class Admin extends Backend{
    use errors;
    private $model;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new AdminModel();
        $this->object['msg'] = $this->Utils->safeString($this->Utils->get('msg'));
    }
    public function admin(){
        $this->Utils->redirect(PROJECT_URL."admin/phone_numbers");
    }
   
    public function signin(){
        try {
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
                     $object = ['tableName'=>'admins','where'=>['username'=>$username,'password'=>$this->encrypt($password)]];
                     $resultSignin = $this->model->signin($object);
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
 
             return $this->Render('signin',$this->object);
        } catch (\Throwable $th) {
             return $this->error($th);
        }
         
     }
     public function signout(){
        try {
            unset($_SESSION['admin_id']);
            $this->Utils->redirect(PROJECT_URL."admin/signin?msg=signout");
        } catch (\Throwable $th) {
            if(isset($_SESSION['admin_id']))
                unset($_SESSION['admin_id']);
            return $this->error($th);
        }
    }
}
?>