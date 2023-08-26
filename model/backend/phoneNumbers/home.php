<?php
class PhoneNumbersModel extends Models{
     public function getData($obj) {
          return $this->db->read($obj,false);
     }
     public function total($obj) {
          return $this->db->totalRows($obj);
     }
     public function removeItem($id){
       try {
          $imageId = $this->db->read(['tableName'=>'phone_numbers','where'=>['id'=>$id]]);
          $imageId = $imageId?$imageId[0]['image_id']:false;
          if($imageId){
               $imageName = $this->db->read(['tableName'=>'upload','where'=>['id'=>$imageId]]);
               $imageName = $imageName?$imageName[0]['name']:false;
               if($imageName){
                    $unlink = unlink(UPLOAD_PATH.IMAGES_DIR_NAME."/".$imageName);
                    if($unlink)
                         $delUploadDb = $this->db->delete(['tableName'=>'upload','id'=>$imageId]);
               }
               else return false;
          }
          if($imageId && (!$unlink || !$delUploadDb))
               return false; 
          $obj = ['tableName'=>'phone_numbers','id'=>$id];
          $delDataDb = $this->db->delete($obj);
          if($delDataDb)
               return true;
       } catch (\Throwable $th) {
          return false; 
       }
      }
}
?>