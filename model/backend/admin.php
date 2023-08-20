<?php
class AdminModel extends Models{
    public function __construct() {
        parent::__construct();
    }
    public function searchFromDb() {
        return $this->db->search();
    }
}
?>