<?php

class Home extends Frontend{
    
    public function home(){

        $object['media_url'] = PROJECT_URL."view/assets";

        $this->Render('home',$object);
    }
}
?>