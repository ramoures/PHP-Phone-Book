<?php
class Profile extends Backend{
    use errors;
    private $model;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new ProfileModel();
    }
    public function profile(){
        try {
            $passwordPattenr = "/".PASSWORD_PATTERN."/";
            $id = $this->Utils->safeInt($_SESSION['admin_id']);
            if(isset($_SESSION['token']))
                $this->object['csrf_token'] = $_SESSION['token'];
            if($this->Utils->safeInt($this->Utils->post('btn_submit'))){
                try{
                    if (!$_POST['token'] || $_POST['token'] !== $_SESSION['token'])
                        $this->object['msg']=['status'=>1,'style'=>'danger','text'=>'CSRF Token is not valid!'];
                    else{
                        $userName = $this->Utils->encode($this->Utils->post('username'));
                        $changePass = $this->Utils->post('changePass')==='on'?1:0;
                        $currentPassword = $this->Utils->encode($this->Utils->post('currentPassword'));
                        $newPassword = $this->Utils->encode($this->Utils->post('newPassword'));
                        $cnfNewPassword = $this->Utils->encode($this->Utils->post('cnfNewPassword'));
                        $checkUsernames = $this->model->getData(['tableName'=>'admins','selector',['id'],'where'=>['username'=>$userName],'whereNot'=>['id'=>$id],'checkIsset'=>true]);
                        $currentPasswordDb = $this->model->getData(['tableName'=>'admins','selector'=>['password'],'where'=>['id'=>$id]]);
                        $currentPasswordDb = $currentPasswordDb?$currentPasswordDb[0]:false;
                        if($userName==='')
                            $this->object['msg']=['status'=>1,'style'=>'danger','text'=>"Please enter username.",'script'=>'username'];
                        else
                        if($checkUsernames)
                            $this->object['msg']=['status'=>2,'style'=>'danger','name'=>$userName,'text'=>"The username is already exists.",'script'=>'username'];
                        else
                        if($changePass && $currentPassword==='')
                            $this->object['msg']=['status'=>3,'style'=>'danger','text'=>"Please enter current password.",'script'=>'currentPassword'];
                        else
                        if($changePass && $this->encrypt($currentPassword) !== ($currentPasswordDb['password']??false))
                            $this->object['msg']=['status'=>4,'style'=>'danger','text'=>"Current password is wrong.",'script'=>'currentPassword'];
                        else
                        if($changePass && $newPassword==='')
                            $this->object['msg']=['status'=>5,'style'=>'danger','text'=>"Please enter new password.",'script'=>'newPassword'];
                        else
                        if($changePass && !preg_match($passwordPattenr,$newPassword))
                            $this->object['msg']=['status'=>6,'style'=>'danger','text'=>"New password is easy. Please set a strong password.",'script'=>'newPassword'];
                        else
                        if($changePass && $cnfNewPassword==='')
                            $this->object['msg']=['status'=>7,'style'=>'danger','text'=>"Please enter new password confirmation..",'script'=>'cnfNewPassword'];
                        else
                        if($changePass && $newPassword !==  $cnfNewPassword)
                            $this->object['msg']=['status'=>8,'style'=>'danger','text'=>"New password and confirmation do not match.",'script'=>'cnfNewPassword'];
                        $this->object['profile_form_info'] = ['username'=>$userName,'currentPassword'=>$currentPassword,'newPassword'=>$newPassword,'cnfNewPassword'=>$cnfNewPassword];
                        $avatarId = null;
                        if(!isset($this->object['msg'])){
                            $avatarId = $this->uploader('avatar',AVATARS_DIR_NAME,$this->model,$userName);
                            if($avatarId){
                                if($changePass && $cnfNewPassword!=='')
                                    $obj = ['tableName'=>'admins','data'=>["username"=>$userName,'password'=>$this->encrypt($cnfNewPassword),'avatar_id'=>$avatarId,'updated_at'=>date("Y-m-d H:i:s")],'where'=>['id'=>$id]];
                                else
                                    $obj = ['tableName'=>'admins','data'=>["username"=>$userName,'avatar_id'=>$avatarId,'updated_at'=>date("Y-m-d H:i:s")],'where'=>['id'=>$id]];
                                $res = $this->model->updateData($obj);
                                if($res){
                                    $this->object['profile_form_info']='';
                                    $this->object['msg']=['style'=>'success','text'=>'Submission successful!'];
                                }
                                else
                                    $this->object['msg']=['status'=>10,'style'=>'danger','text'=>'Error! Try again later.'];
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    $this->object['msg']=['status'=>9,'style'=>'danger','text'=>'Error! Try again later.'];
                }
            }else{
                $_SESSION['token'] = bin2hex(random_bytes(35));
                $this->object['csrf_token'] = $_SESSION['token'];
            }
            $this->object['changePass'] = $changePass??0;
            $this->object['admin_info'] = $this->adminInfo($this->Utils->safeInt($_SESSION['admin_id']),$this->model);
            return $this->Render('profile',$this->object);
        } catch (\Throwable $th) {
             return $this->error($th);
        }
     }
    
}
?>