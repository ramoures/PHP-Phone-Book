<?php
class AddPhoneNumbersModel extends Models{
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
    public function issetData($object){
        try {
            return $this->db->read($object);
        }
        catch (\Throwable $th) {
            return false;
        }
    }
    public function search($tableName,$where) {
        try {
            $obj = ["tableName"=>$tableName,"where"=>$where];
            return $this->db->search($obj);
        } catch (\Throwable $th) {
            return false;
        }
    }
    
   

}
?>