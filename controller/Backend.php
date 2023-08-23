<?php
abstract class Backend extends Base{

    public function __construct($param) {
        parent::__construct($param);
        parent::initTwig('backend');
        if($param !== 'signin' && !$this->adminIsSigned())
            $this->Utils->redirect(PROJECT_URL."admin/signin");
    }
   
   
    protected function adminIsSigned(){
        if(isset($_SESSION['admin_id']))
            return true;
        else
            return false;
    }
    public function setVar($path,$key,$value)
    {
        $_SESSION[$path][$key] = $value;
    }
    public function getVar($path,$key)
    {
        if(isset($_SESSION[$path][$key]))
            return $_SESSION[$path][$key];
        else
            return null;
    }
    public function delVarKey($path,$key)
    {
        if(isset($_SESSION[$path][$key]))
            unset($_SESSION[$path][$key]);
    }
    public function delVar($path)
    {
        if(isset($_SESSION[$path]))
            unset($_SESSION[$path]);
    } 
    public function encrypt($str){
        $secretSault = SECRET_SAULT??[];
        $saultOrder = SAULT_ORDER??[];
        $sault = $secretSault[$saultOrder[0]].$str.SECRET_KEY.$secretSault[$saultOrder[1]].SECRET_KEY.SECRET_KEY.$secretSault[$saultOrder[2]].$str.$secretSault[$saultOrder[3]].SECRET_KEY;
        return hash('sha256',$sault);
    }
    public function uploadImage($fieldName,$path,$maxSize=3145728){
        if(isset($_FILES[$fieldName])){
            $error = $_FILES[$fieldName]['error'];
            $tmp_name = $_FILES[$fieldName]['tmp_name'];
            $fileSize = $_FILES[$fieldName]['size'];
            $fileName = hash('sha256',date('YmdHis').mt_rand(1000000,9999999).date('YmdHis'));
            if($error === 0){
                $allowFiles = array('image/jpg'=>'jpg','image/jpeg'=>'jpeg','image/png'=>'png');
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp_name);
                $index = array_search($mime,array_keys($allowFiles));
                if($index !== false){
                    $isImg = getimagesize($tmp_name);
                    if(is_array($isImg) && count($isImg) > 0 ){
                        if($maxSize > 0)
                            if($fileSize > $maxSize)
                                return -6; //file is big
                        $newFileName = $fileName;
                        $newFileName .= ".".$allowFiles[$index];
                        $move = move_uploaded_file($tmp_name,"$path/$newFileName");
                        if($move)
                            return $newFileName;
                        else
                            return -5; //upload failed
                    }
                    else
                        return -4;//file is not valid
                }
                else
                    return -3;//file type is not allow
            }
            else
                return -2; //upload failed
        }
        else
            return -1; //file is not isset
    }
}
?>