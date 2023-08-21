<?php
abstract class Frontend{
    public function Render($filePath,$res=''){
        $data = $res??'';
        include_once(ROOT_PATH."view/frontend/".BACKEND_THEME_DIR_NAME."/$filePath.php");
    }
}
?>