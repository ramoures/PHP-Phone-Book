<?php
class AdminModel extends Models{
    public function signin($object){
        try {
            $login = $this->db->read($object);
            if($login){
                return $login[0];
            }
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function setLastSigned($object) {
        try {
            $this->db->update($object);
        } catch (\Throwable $th) {
            return false;
        }
    }
 

}
?>