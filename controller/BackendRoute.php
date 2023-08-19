<?php
abstract class BackendRoute{
    public function Render($filePath,$res){
        $data = $res;
        include_once(ROOT_PATH."view/backend/".BACKEND_THEME_DIR_NAME."/$filePath.php");
    }
}
?>