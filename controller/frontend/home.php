<?php

class Home extends Frontend{

    public function index(){
        $object['language'] = strtoupper($this->language);
        $object['media_url'] = PROJECT_URL."view/assets";

        $this->Render('index',$object);
    }
}
?>