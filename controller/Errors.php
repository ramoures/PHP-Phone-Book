<?php

trait errors{
    function dbError()  {  
        die('<h2 style="position:absolute;top:50%;left:50%;transform: translate(-50%,-50%);font-weight:normal">Database Connection Failed!</h2>'); 
    }
}

?>