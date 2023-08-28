<?php
class AdminModel extends Models{
    public function signin($object){
        try {
            $login = $this->db->read($object);
            if($login){
                // $login = json_decode(json_encode($login),true);
                $login = $login;
                $_SESSION['admin_id'] = $login[0]['id'];
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
 

}
?>