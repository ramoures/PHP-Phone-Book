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
                        if($userName==='')
                            $this->object['msg']=['status'=>1,'style'=>'danger','text'=>"Please enter username.",'script'=>'username'];
                        else
                        if($changePass && $currentPassword==='')
                            $this->object['msg']=['status'=>2,'style'=>'danger','text'=>"Please enter current password.",'script'=>'currentPassword'];
                        else
                        if($changePass && $this->encrypt($currentPassword) !== $this->model->getData(['tableName'=>'admins','selector'=>['password'],'where'=>['id'=>$id]])[0]['password'])
                            $this->object['msg']=['status'=>3,'style'=>'danger','text'=>"Current password is wrong.",'script'=>'currentPassword'];
                        else
                        if($changePass && $newPassword==='')
                            $this->object['msg']=['status'=>4,'style'=>'danger','text'=>"Please enter new password.",'script'=>'newPassword'];
                        else
                        if($changePass && !preg_match($passwordPattenr,$newPassword))
                            $this->object['msg']=['status'=>5,'style'=>'danger','text'=>"New password is easy. Please set a strong password.",'script'=>'newPassword'];
                        else
                        if($changePass && $cnfNewPassword==='')
                            $this->object['msg']=['status'=>6,'style'=>'danger','text'=>"Please enter new password confirmation..",'script'=>'cnfNewPassword'];
                        else
                        if($changePass && $newPassword !==  $cnfNewPassword)
                            $this->object['msg']=['status'=>7,'style'=>'danger','text'=>"New password and confirmation do not match.",'script'=>'cnfNewPassword'];
                        $this->object['profile_form_info'] = ['username'=>$userName,'currentPassword'=>$currentPassword,'newPassword'=>$newPassword,'cnfNewPassword'=>$cnfNewPassword];
                        $avatarId = null;
                        if(!isset($this->object['msg'])){
                            if(isset($_FILES['avatar'])){
                                $upload = $this->uploader('avatar',AVATARS_DIR_NAME);
                                if(!is_int($upload) && $upload !== false){
                                    $data = ['tableName'=>'upload','data'=>['folder'=>AVATARS_DIR_NAME,'name'=>$upload,'alt'=>$userName]];
                                    $avatarId = $this->model->insertData($data);
                                    if(!$avatarId)
                                        $this->object['msg']=['status'=>9,'style'=>'danger','text'=>'Error! Try again later.'];
                                }
                                else
                                if(is_int($upload) && $upload !== false)
                                    if(in_array($upload,[-1,-2,-4,-5]))
                                        $this->object['msg']=['status'=>$upload,'style'=>'danger','text'=>'File Upload Failure!','script'=>'avatar'];
                                    else if($upload === -3)
                                        $this->object['msg']=['status'=>-3,'style'=>'danger','text'=>'The file extension is not allowed. Allowable file types=','ex'=>implode(", ",array_values(ALLOW_FILES_TYPE)),'script'=>'avatar'];
                                    else if($upload === -6)
                                        $this->object['msg']=['status'=>-3,'style'=>'danger','text'=>'The file is too large. Max file size=','ex'=>MAX_FILE_SIZE,'script'=>'avatar'];
                            }
                            else
                                $avatarId= $this->Utils->safeInt($this->Utils->post('avatar_id'));
                            if(!isset($this->object['msg'])){
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
                                    $this->object['msg']=['status'=>9,'style'=>'danger','text'=>'Error! Try again later.'];
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    $this->object['msg']=['status'=>8,'style'=>'danger','text'=>'Error! Try again later.'];
                }
            }else{
                $_SESSION['token'] = bin2hex(random_bytes(35));
                $this->object['csrf_token'] = $_SESSION['token'];
            }
            $row = $this->model->getData(['tableName'=>'admins','selector'=>['username','avatar_id'],'where'=>['id'=>$id]]);
            $row = $row?$row[0]:null;
            if($row['avatar_id'])
                $row['image'] = $this->model->getData(['tableName'=>'upload','selector'=>['id','name','alt','folder'],'where'=>['id'=>$row['avatar_id']]])[0];
            $this->object['row_info'] = $this->Utils->decode($row);
            $this->object['changePass'] = $changePass??0;
            $this->object['admin_info'] = $this->model->adminInfo($this->Utils->safeInt($_SESSION['admin_id']));
            return $this->Render('profile',$this->object);
        } catch (\Throwable $th) {
             return $this->error($th);
        }
     }
    
}
?>