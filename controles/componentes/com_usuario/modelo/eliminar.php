<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    require("componentes/com_auditoria/clase/auditoria.class.php");
    
    Usuario::eliminar($_GET['id']);
    Auditoria::insertar($_GET['com'], 'eliminar', $_GET['id'] );
    
    header("Location: index.php?com=usuario");
  
?>