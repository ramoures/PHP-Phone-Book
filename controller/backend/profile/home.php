<?php
class Profile extends Backend{
    use errors;
    public function __construct($param) {
        parent::__construct($param);
    }
    public function profile(){
        try {
            $this->Render('profile',$this->object);
        } catch (\Throwable $th) {
             return $this->error($th);
        }
     }
    
}
?>