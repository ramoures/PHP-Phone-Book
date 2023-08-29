<?php
abstract class Models{
    use errors;
    protected $db=null;
    public function __construct() {
        $this->db = database::getInstance();
    }
    public function avatar($id) {
        try {
            $avatarId = $this->db->read(['tableName'=>'admins','selector'=>['avatar_id'],'where'=>['id'=>$id]]);
            $avatarId = $avatarId?$avatarId[0]['avatar_id']:false;
            if($avatarId){
                $avatar = $this->db->read(['tableName'=>'upload','selector'=>['name','alt','folder'],'where'=>['id'=>$avatarId]]);
                $avatar = $avatar?$avatar[0]:false;
                if($avatar)
                return $avatar;
            }
            return false;

        } catch (\Throwable $th) {
            return 0;
        }
    }
   
}

?>