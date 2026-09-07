<?php define('DIRECT_ACCESS', true);
    
    require("../../../configuracion.php");
    require("../../../includes/database.inc.php");
    require("../../../componentes/com_control/clase/control.class.php");    
    
    $_GET['id'] = (empty($_GET['id'])) ? FALSE : $_GET['id'];
    
    if(Control::verificar($_GET['fecha'], $_GET['actividad'], $_GET['id']))
    {
       header("HTTP/1.1 200 OK");;
    }
    else
    {
       header("HTTP/1.1 406 Not Acceptable");
    }  
    
?>