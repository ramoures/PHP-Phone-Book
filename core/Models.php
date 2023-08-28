<?php
abstract class Models{
    use errors;
    protected $db=null;
    public function __construct() {
        $this->db = database::getInstance();
    }
   
}

?>