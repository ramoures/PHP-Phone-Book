<?php
class AdminModel extends Models{
    public function __construct() {
        parent::__construct();
    }
    public function searchFromDb() {
        return $this->db->search();
    }
    public function signin($username,$password){

        $login = $this->db->getRow('admins',[$username,$password]);
        if($login){
            $login = json_decode($login,true);
            $_SESSION['admin_id'] = $login[0]['id'];
            return true;
        }
        return false;
       
    }
}
?>