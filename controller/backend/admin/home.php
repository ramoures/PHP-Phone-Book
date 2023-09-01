<?php
class Admin extends Backend{
    use errors;
    private $model;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new AdminModel();
    }
    public function admin(){
        $this->Utils->redirect(PROJECT_URL."admin/phone_numbers");
    }
   
    public function signin(){
        try {
            if($this->adminIsSigned())
                 $this->Utils->redirect(PROJECT_URL."admin");
            $signout = $this->Utils->safeInt($this->Utils->get('signout'));
                if($signout)
                    $this->object['msg']=['style'=>'success','text'=>'Signed out successfully.'];   
                if(isset($_POST['btn_signin'])){
                    $captcha=$_POST['cf-turnstile-response'];
                    if (!$captcha) 
                        $this->object['msg']=['status'=>'-1','style'=>'danger','text'=>'Please check the the captcha form.'];
                    else{
                        $ip = $this->getIp();
                        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
                        $data = array('secret' => CAPTCHA_SECRET_KEY, 'response' => $captcha, 'remoteip' => $ip);
                        $options = array(
                            'http' => array(
                            'method' => 'POST',
                            'content' => http_build_query($data))
                        );
                        $stream = stream_context_create($options);
                        $result = file_get_contents($url, false, $stream);
                        $response =  $result;
                        $responseKeys = json_decode($response,true);
                        if((int)$responseKeys["success"] !== 1) 
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
                                        $this->Utils->redirect(PROJECT_URL."admin");
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
            $this->Utils->redirect(PROJECT_URL."admin/signin?signout=1");
        } catch (\Throwable $th) {
            if(isset($_SESSION['admin_id']))
                unset($_SESSION['admin_id']);
            return $this->error($th);
        }
    }
}
?>