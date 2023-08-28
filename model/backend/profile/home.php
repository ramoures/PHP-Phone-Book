<?php
class ProfileModel extends Models{
    public function getRow($obj):array{
        try {
            return $this->db->read($obj);
        } catch (\Throwable $th) {
            return false;
        }
    }
}

?>