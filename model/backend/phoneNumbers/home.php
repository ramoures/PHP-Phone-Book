<?php
class PhoneNumbersModel extends Models{
     public function getData($obj) {
          return $this->db->read($obj,false);
     }
     public function total($obj) {
          return $this->db->totalRows($obj);
     }
}
?>