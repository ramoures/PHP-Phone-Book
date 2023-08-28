<?php
abstract class Backend extends Base{
    public function __construct($param) {
        parent::__construct($param);
        if($param['method'] !== 'signin' && !$this->adminIsSigned())
            $this->Utils->redirect(PROJECT_URL."admin/signin");
    }
    protected function adminIsSigned(){
        if(isset($_SESSION['admin_id']))
            return true;
        else
            return false;
    }
    protected function setVar($path,$key,$value){
        $_SESSION[$path][$key] = $value;
    }
    protected function getVar($path,$key){
        if(isset($_SESSION[$path][$key]))
            return $_SESSION[$path][$key];
        else
            return null;
    }
    protected function delVarKey($path,$key){
        if(isset($_SESSION[$path][$key]))
            unset($_SESSION[$path][$key]);
    }
    protected function delVar($path){
        if(isset($_SESSION[$path]))
            unset($_SESSION[$path]);
    } 
    protected function encrypt($str){
        $secretSault = SECRET_SAULT??[];
        $saultOrder = SAULT_ORDER??[];
        $sault = $secretSault[$saultOrder[0]].$str.SECRET_KEY.$secretSault[$saultOrder[1]].SECRET_KEY.SECRET_KEY.$secretSault[$saultOrder[2]].$str.$secretSault[$saultOrder[3]].SECRET_KEY;
        return hash('sha256',$sault);
    }
    private function uploadImage($fieldName,$path){
        try {
            $error = $_FILES[$fieldName]['error'];
            $tmp_name = $_FILES[$fieldName]['tmp_name'];
            $fileSize = $_FILES[$fieldName]['size'];
            $fileName = hash('sha256',date('YmdHis').mt_rand(1000000,9999999).date('YmdHis'));
            if($error === 0){
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp_name);
                $index = array_search($mime,array_keys(ALLOW_FILES_TYPE));
                if($index !== false){
                    $isImg = getimagesize($tmp_name);
                    if(is_array($isImg) && count($isImg) > 0 ){
                        if($fileSize > MAX_FILE_SIZE)
                            return -6; //file is big
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
    protected function uploader($fieldName,$folder){
        try {
            if(isset($_FILES[$fieldName])){
                if(in_array($folder,VALID_DIR_NAMES_TO_UPLOAD)){
                    $fileField= $_FILES[$fieldName]['tmp_name'];
                    if($fileField)
                        return $this->uploadImage($fieldName,UPLOAD_PATH."$folder");
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
}
?>