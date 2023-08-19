<?php

trait errors{
    public function error($error='') {  
        if($error)
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        else
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Document Not Found', true, 404);
        if(DEBUG)
            if($error)
                die($error);
            else
                die('404 Document not found!');
        else
            if($error)
                die('<h2 style="position:absolute;top:50%;left:50%;transform: translate(-50%,-50%);font-weight:normal">500, Internal Server Error!</h2>'); 
            else
                die('<h2 style="position:absolute;top:50%;left:50%;transform: translate(-50%,-50%);font-weight:normal">404, Document not found!</h2>'); 
         
    }
}

?>