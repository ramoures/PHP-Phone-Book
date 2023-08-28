<?php
class HomeModel extends Models{
     public function getData($obj) {
          return $this->db->read($obj);
     }
     public function total($obj) {
          return $this->db->search($obj);
     }
}
?>