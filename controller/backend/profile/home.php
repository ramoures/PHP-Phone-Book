<?php
class Profile extends Backend{
    use errors;
    private $model;
    public function __construct($param) {
        parent::__construct($param);
        $this->model = new ProfileModel();
        $this->object['avatar_info'] = $this->model->avatar($this->Utils->safeInt($_SESSION['admin_id']));
    }
    public function profile(){
        try {
            $passwordPattenr = "/".PASSWORD_PATTERN."/";
            $id = $this->Utils->safeInt($_SESSION['admin_id']);

            if($this->Utils->safeInt($this->Utils->post('btn_submit'))){
                try{
                $userName = $this->Utils->encode($this->Utils->post('username'));
                $changePass = $this->Utils->post('changePass')==='on'?1:0;
                $currentPassword = $this->Utils->encode($this->Utils->post('currentPassword'));
                $newPassword = $this->Utils->encode($this->Utils->post('newPassword'));
                $cnfNewPassword = $this->Utils->encode($this->Utils->post('cnfNewPassword'));
 

                if($userName==='')
                    $this->object['msg']=['status'=>1,'style'=>'danger','text'=>"username is empty.",'script'=>'username'];
                else
                if($changePass && $currentPassword==='')
                    $this->object['msg']=['status'=>2,'style'=>'danger','text'=>"Current password is empty.",'script'=>'currentPassword'];
                else
                if($changePass && $this->encrypt($currentPassword) !== $this->model->getData(['tableName'=>'admins','selector'=>['password'],'where'=>['id'=>$id]])[0]['password'])
                    $this->object['msg']=['status'=>3,'style'=>'danger','text'=>"Current password is wrong.",'script'=>'currentPassword'];
                else
                if($changePass && $newPassword==='')
                    $this->object['msg']=['status'=>4,'style'=>'danger','text'=>"New password is empty.",'script'=>'newPassword'];
                else
                if($changePass && !preg_match($passwordPattenr,$newPassword))
                    $this->object['msg']=['status'=>5,'style'=>'danger','text'=>"New password is easy. Please set strong password.",'script'=>'currentPassword'];
                else
                if($changePass && $cnfNewPassword==='')
                    $this->object['msg']=['status'=>6,'style'=>'danger','text'=>"Confirm new password is empty.",'script'=>'cnfNewPassword'];
                else
                if($changePass && $newPassword !==  $cnfNewPassword)
                    $this->object['msg']=['status'=>7,'style'=>'danger','text'=>"New and confirm password dos'nt matched.",'script'=>'cnfNewPassword'];
                
                $profile_form_info = ['username'=>$userName,'currentPassword'=>$currentPassword,'newPassword'=>$newPassword,'cnfNewPassword'=>$cnfNewPassword];
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
                                $this->object['msg']=['status'=>$upload,'style'=>'danger','text'=>'File Upload Failure! Try again later.','script'=>'avatar'];
                            else if($upload === -3)
                                $this->object['msg']=['status'=>-3,'style'=>'danger','text'=>'The file extension is not allowed. Allowable file types : .jpeg, .jpg, .png','script'=>'avatar'];
                            else if($upload === -6)
                                $this->object['msg']=['status'=>-3,'style'=>'danger','text'=>'The file is too large. Max file size ='.MAX_FILE_SIZE,'script'=>'avatar'];
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
                            $profile_form_info='';
                            $this->object['msg']=['style'=>'success','text'=>'Submission successful!'];
                        }
                        else
                            $this->object['msg']=['status'=>9,'style'=>'danger','text'=>'Error! Try again later.'];
                    }
                }
                } catch (\Throwable $th) {
                    $this->object['msg']=['status'=>8,'style'=>'danger','text'=>'Error! Try again later.'];
                }
            }
            $this->object['profile_form_info'] = $profile_form_info??[];
            $row = $this->model->getData(['tableName'=>'admins','selector'=>['username','avatar_id'],'where'=>['id'=>$id]]);
            $row = $row?$row[0]:null;
            if($row['avatar_id'])
                $row['image'] = $this->model->getData(['tableName'=>'upload','selector'=>['id','name','alt','folder'],'where'=>['id'=>$row['avatar_id']]])[0];
            $this->object['row_info'] = $this->Utils->decode($row);
            $this->object['changePass'] = $changePass??0;

            return $this->Render('profile',$this->object);
        } catch (\Throwable $th) {
             return $this->error($th);
        }
     }
    
}
?>