<?php
class SignoutAdmin extends Backend{
    use errors;
    public function signout(){
        try {
            unset($_SESSION['admin_id']);
            $this->Utils->redirect(PROJECT_URL."admin/signin?msg=signout");
        } catch (\Throwable $th) {
            if(isset($_SESSION['admin_id']))
                unset($_SESSION['admin_id']);
            return $this->error($th);
        }
    } 
}
 
?>