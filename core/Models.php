<?php
abstract class Models{
    use errors;
    protected $db;
    public function __construct() {
        $this->db = database::getInstance();
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
    public function insertData($obj){
        try {
            $result = $this->db->create($obj);
            if($result)
                return $result;
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }  
    public function updateData($obj){
        try {
            $result = $this->db->update($obj);
            if($result)
                return $result;
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function search($obj) {
        try {
            $obj['search'] = true;
            $result = $this->db->read($obj);
            if($result)
                return $result;
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function removeData($obj) {
        try {
            $result = $this->db->delete($obj);
            if($result)
                return $result;
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}

?>