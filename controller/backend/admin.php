<?php
class Admin extends Backend{
    private $model;
    public function __construct($className) {
        parent::__construct($className);
        $this->model = new AdminModel();
    }
    public function home(){
        $object = $this->model->searchFromDb();
        $object = json_decode($object,true);
        $this->Render('dashboard',$object);
    } 
    public function add(){
        $this->Render('add','hi');
    }
}
?>