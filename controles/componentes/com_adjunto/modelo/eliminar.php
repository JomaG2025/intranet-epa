<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
        
    require("componentes/com_auditoria/clase/auditoria.class.php");
    Adjunto::eliminar($_GET['id']);
    Auditoria::insertar($_GET['com'], 'eliminar', $_GET['id'] );        
           
    header("Location: ". $_SERVER['HTTP_REFERER']);
  
?>