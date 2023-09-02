<?php
class Admin extends Backend{
    use errors;
    private $model;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new AdminModel();
    }
    public function admin(){ 
        //admin dashboard page:

        return $this->Utils->redirect(PROJECT_URL.ADMIN_DIR_NAME."/phone_numbers");
        // For enable dashboard page: 1.delete previous line and 2.uncomment next line.
        // return $this->Render('dashboard',$this->object);
    }
   
    public function signin(){
        try {
            if(is_dir('setup'))
                rmdir('setup');
            if($this->adminIsSigned())
                 $this->Utils->redirect(PROJECT_URL.ADMIN_DIR_NAME);
            $signout = $this->Utils->safeInt($this->Utils->get('signout'));
                if($signout)
                    $this->object['msg']=['style'=>'success','text'=>'Signed out successfully.'];   
                if(isset($_POST['btn_signin'])){
                    $captcha=$this->Utils->post('cf-turnstile-response');
                    if (!$captcha) 
                        $this->object['msg']=['status'=>'-1','style'=>'danger','text'=>'Please check the the captcha form.'];
                    else{
                        if(!$this->captchaCheck($captcha)) 
                            $this->object['msg']=['status'=>'-2','style'=>'danger','text'=>'Security error!'];
                        else { 
                            $username= $this->Utils->encode($this->Utils->post('username'));
                            $password= $this->Utils->encode($this->Utils->post('password'));
                            if($username=='')
                                $this->object['msg']=['status'=>'1','style'=>'danger','text'=>'Please enter username.'];
                            else
                            if($password=='')
                                $this->object['msg']=['status'=>'2','style'=>'danger','text'=>'Please enter password.'];
                            else{
                                $obj = ['tableName'=>'admins','selector'=>['id'],'where'=>['username'=>$username,'password'=>$this->encrypt($password)]];
                                $resultSignin = $this->model->getData($obj);
                                $resultSignin = $resultSignin?$resultSignin[0]:false;
                                if($resultSignin){
                                    $_SESSION['admin_id'] = $resultSignin['id'];
                                    $obj = ['tableName'=>'admins','data'=>['last_signed_at'=>date('Y-m-d H:i:s')],'where'=>['id'=>$resultSignin['id']]];
                                    $this->model->updateData($obj);
                                    if($this->adminIsSigned())
                                        $this->Utils->redirect(PROJECT_URL.ADMIN_DIR_NAME);
                                }
                                else
                                    $this->object['msg']=['status'=>'3','style'=>'danger','text'=>'The user is not valid.'];
                            }
                            $this->object['username'] = $this->Utils->decode($username);
                        }
                    }  
                    
                }
             return $this->Render('signin',$this->object);
        } catch (\Throwable $th) {
             return $this->error($th);
        }
         
     }
     public function signout(){
        try {
            unset($_SESSION['admin_id']);
            $this->Utils->redirect(PROJECT_URL.ADMIN_DIR_NAME."/signin?signout=1");
        } catch (\Throwable $th) {
            if(isset($_SESSION['admin_id']))
                unset($_SESSION['admin_id']);
            return $this->error($th);
        }
    }
}
?>