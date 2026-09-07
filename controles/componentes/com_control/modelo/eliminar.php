<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    Control::eliminar($_GET['id']);
        
    require("componentes/com_auditoria/clase/auditoria.class.php");
    Auditoria::insertar($_GET['com'], 'eliminar', $_GET['id'] );
    
    header("Location: index.php?com=control");
  
?>