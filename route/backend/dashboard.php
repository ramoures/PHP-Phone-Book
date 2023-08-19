<?php
class Dashboard extends BackendRoute{
    public function home(){
        $obj = new stdClass();
        $obj->title= 'hi';
        $obj->name= 'ramin';
        $object = json_encode($obj);
        $this->Render('dashboard',$object);
    } 
    public function add(){
        $this->Render('dashboard','hi');
    }
}
?>