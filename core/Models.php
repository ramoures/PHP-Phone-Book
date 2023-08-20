<?php
abstract class Models{
    protected $db=null;
    public function __construct() {
        $this->db = database::getInstance();
    }
}

?>