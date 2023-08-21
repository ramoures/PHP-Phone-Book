<?php
abstract class Frontend extends Base{
    public function __construct($className) {
        parent::__construct($className);
        parent::initTwig('frontend');
    }

}
?>