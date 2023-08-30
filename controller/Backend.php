<?php
abstract class Backend extends Base{
    public function __construct($param) {
        parent::__construct($param);
        if($param['method'] !== 'signin' && !$this->adminIsSigned())
            $this->Utils->redirect(PROJECT_URL."admin/signin");
    }
    protected function adminIsSigned(){
        try {
            if(isset($_SESSION['admin_id']))
                return true;
            else
                return false;
        } catch (\Throwable $th) {
            return $this->error($th);
        }
    }
    protected function encrypt($str){
       try {
            $result = SECRET_KEY.$str."67H8fgp)@sd8u*ac".$str.SECRET_KEY."546DFsG%^dfA";
            return hash('sha256',$result);
       } catch (\Throwable $th) {
            return $this->error($th);
       }
    }
    private function uploadImage($fieldName,$path){
        try {
            $error = $this->Utils->safeInt($_FILES[$fieldName]['error']);
            $tmp_name = $this->Utils->encode($_FILES[$fieldName]['tmp_name']);
            $fileSize = $this->Utils->safeInt($_FILES[$fieldName]['size']);
            $fileName = hash('sha256',date('YmdHis').mt_rand(1000000,9999999).date('YmdHis'));
            if($error === 0){
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp_name);
                $index = array_search($mime,array_keys(ALLOW_FILES_TYPE));
                if($index !== false){
                    $isImg = getimagesize($tmp_name);
                    if(is_array($isImg) && count($isImg) > 0 ){
                        if($fileSize > MAX_FILE_SIZE)
                            return -6; //file is too large
                        $newFileName = $fileName;
                        $newFileName .= ".".ALLOW_FILES_TYPE[$mime];
                        $move = move_uploaded_file($tmp_name,"$path/$newFileName");
                        if($move)
                            return $newFileName;
                        else
                            return -5; //move uploaded file failed
                    }
                    else
                        return -4;//file is not valid
                }
                else
                    return -3;//file type is not allow
            }
            else
                return -2; //upload failed
           
        } catch (\Throwable $th) {
            return -2;
        }
    }
    protected function toUpload($fieldName,$path){
        try {
            if(isset($_FILES[$fieldName])){
                if(in_array($path,[AVATARS_DIR_NAME,IMAGES_DIR_NAME])){
                    $fileField= $this->Utils->encode($_FILES[$fieldName]['tmp_name']);
                    if($fileField)
                        return $this->uploadImage($fieldName,UPLOAD_PATH."$path");
                    else
                        return false; // file temp is not isset
                }
                else
                    return -2;
            }
            else
                return false; // file field is not isset
        } catch (\Throwable $th) {
            return false;
        }
    }
    protected function uploader($fieldName,$path,$model,$alt){
       try {
            $id=null;
            if(isset($_FILES[$fieldName])){
                $upload = $this->toUpload($fieldName,$path);
                if(!is_int($upload) && $upload !== false){
                    $data = ['tableName'=>'upload','data'=>['folder'=>$path,'name'=>$upload,'alt'=>$alt,'created_at'=>date("Y-m-d H:i:s")]];
                    $id = $model->insertData($data);
                    if(!$id){
                         $this->object['msg']=['status'=>9,'style'=>'danger','text'=>'Error! Try again later.'];
                         return false;
                    }
                }
                else
                if(is_int($upload) && $upload !== false)
                    if(in_array($upload,[-1,-2,-4,-5])){
                        $this->object['msg']=['status'=>$upload,'style'=>'danger','text'=>'File Upload Failure!','script'=>$fieldName];
                        return false;
                    }
                    else if($upload === -3){
                        $this->object['msg']=['status'=>-3,'style'=>'danger','text'=>'The file extension is not allowed. Allowable file types=','ex'=>implode(", ",array_values(ALLOW_FILES_TYPE)),'script'=>$fieldName];
                        return false;
                    }
                    else if($upload === -6){
                        $this->object['msg']=['status'=>-3,'style'=>'danger','text'=>'The file is too large. Max file size=','ex'=>MAX_FILE_SIZE,'script'=>$fieldName];
                        return false;
                    }
                    else{
                        $prevId = $id= $this->Utils->safeInt($this->Utils->post($fieldName."_id"));
                        if($prevId){
                            $image = $model->getData(['tableName'=>'upload','selector'=>['name'],'where'=>['id'=>$prevId]]);
                            $image = $image?$image[0]:false;
                            if($image['name']){
                                    $unlink = unlink(UPLOAD_PATH.$path."/".$image['name']);
                                    if($unlink)
                                        $model->removeData(['tableName'=>'upload','id'=>$prevId]);
                            }
                            else{
                                $this->object['msg']=['status'=>9,'style'=>'danger','text'=>'Error! Try again later.'];
                                return false;
                            }
                            
                        }
                    }
            }
            else
                $id= $this->Utils->safeInt($this->Utils->post($fieldName."_id"));
            return $id;
       } catch (\Throwable $th) {
            $this->object['msg']=['status'=>9,'style'=>'danger','text'=>'Error! Try again later.'];
            return false;
       }
    }
    protected function adminInfo($id,$model) {
        try {
            $admin = $model->getData(['tableName'=>'admins','selector'=>['avatar_id','username'],'where'=>['id'=>$id]]);
            $adminInfo['username'] = $admin?$admin[0]['username']:null;
            $avatarId = $admin?$admin[0]['avatar_id']:false;
            if($avatarId){
                $avatar = $model->getData(['tableName'=>'upload','selector'=>['id','name','alt','folder'],'where'=>['id'=>$avatarId]]);
                $avatar = $avatar?$avatar[0]:false;
                if($avatar)
                     $adminInfo['avatar'] = $avatar;
            }
            return $adminInfo;

        } catch (\Throwable $th) {
            return 0;
        }
    }
}
?>