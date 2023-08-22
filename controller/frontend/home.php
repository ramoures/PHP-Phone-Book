<?php

class Home extends Frontend{
    
    public function home(){
        $object['language'] = strtoupper($this->language);
        $object['media_url'] = PROJECT_URL."view/assets";
        $object['sign'] = $this->adminIsSigned();

        $this->Render('home',$object);
    }
}
?>