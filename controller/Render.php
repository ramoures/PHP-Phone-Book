<?php
function Render($filePath,$res){
    $data = $res;
    include_once(ROOT_PATH."view/frontend/".FRONTEND_THEME_DIR_NAME."/$filePath.php");
}

?>