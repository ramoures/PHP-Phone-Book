<?php
abstract class Frontend extends Base{
    public function __construct($param) {
        parent::__construct($param);
        parent::initTwig('frontend');
        $this->language = $this->Utils->getLang()??DEFAULT_LANG;
        if(file_exists(ROOT_PATH.'lang/'. $this->language .'.php'))
            require_once(ROOT_PATH.'lang/'. $this->language .'.php');
        $this->lang = $lang??'';
    }

}
?>