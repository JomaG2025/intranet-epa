<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }

    require("componentes/com_actividad/clase/actividad.class.php");        

?>