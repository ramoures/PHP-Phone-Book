<?php
class EditPhoneNumbersModel extends Models{
    public function __construct() {
        parent::__construct();
    }
    public function getData($obj){
        try {
         $result = $this->db->read($obj);
         if($result)
             return $result;
         return false;
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
    public function search($tableName,$where,$id) {
        try {
            $obj = ["tableName"=>$tableName,"where"=>$where,'whereNot'=>['id'=>$id]];
            return $this->db->search($obj);
        } catch (\Throwable $th) {
            return false;
        }
    }
    
   

}
?>