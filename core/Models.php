<?php
abstract class Models{
    use errors;
    protected $db;
    protected $adminInfo;
    public function __construct() {
        $this->db = database::getInstance();
    }
    public function adminInfo($id) {
        try {
            $admin = $this->db->read(['tableName'=>'admins','selector'=>['avatar_id','username'],'where'=>['id'=>$id]]);
            $this->adminInfo['username'] = $admin?$admin[0]['username']:null;
            $avatarId = $admin?$admin[0]['avatar_id']:false;
            if($avatarId){
                $avatar = $this->db->read(['tableName'=>'upload','selector'=>['name','alt','folder'],'where'=>['id'=>$avatarId]]);
                $avatar = $avatar?$avatar[0]:false;
                if($avatar)
                     $this->adminInfo['avatar'] = $avatar;
            }
            return $this->adminInfo;

        } catch (\Throwable $th) {
            return 0;
        }
    }
   
}

?>