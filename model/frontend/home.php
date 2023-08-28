<?php
class HomeModel extends Models{
     public function getData($obj) {
          try {
               return $this->db->read($obj);
          } catch (\Throwable $th) {
               return false;
          }
     }
     public function total($obj) {
          try {
               return $this->db->search($obj);
          } catch (\Throwable $th) {
               return false;
          }
     }
}
?>