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
            $row = $this->model->getRow(['tableName'=>'admins']);
            print_r($row);
            if($this->Utils->safeInt($this->Utils->post('btn_submit'))){
                $userName = $this->Utils->safeString($this->Utils->post('username'));
                $currentPassword = $this->Utils->safeString($this->Utils->post('currentPassword'));
                $newPassword = $this->Utils->safeString($this->Utils->post('newPassword'));
                $cnfNewPassword = $this->Utils->safeString($this->Utils->post('cnfNewPassword'));
                $avatar = $this->Utils->encode($_FILES['image']);
                $_SESSION['profile_form_info'] = ['username'=>$userName];

            }
            $this->object['profile_form_info'] = $_SESSION['profile_form_info'];

            return $this->Render('profile',$this->object);
        } catch (\Throwable $th) {
             return $this->error($th);
        }
     }
    
}
?>