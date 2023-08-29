<?php
class ProfileModel extends Models{
    public function getData($obj):array{
        try {
            return $this->db->read($obj);
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function insertData($data){
        try {
            $result = $this->db->create($data);
            if($result)
                return $result;
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }  
    public function updateData($data){
       try {
        $result = $this->db->update($data);
        if($result)
            return $result;
        return false;
       } catch (\Throwable $th) {
        return false;
       }
    }
    public function issetData($data){
        try {
            return $this->db->read($data);
        }
        catch (\Throwable $th) {
            return false;
        }
    }
}

?>