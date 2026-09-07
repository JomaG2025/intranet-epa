<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    require("componentes/com_auditoria/clase/auditoria.class.php");
    
    Actividad::eliminar($_GET['id']);
    Auditoria::insertar($_GET['com'], 'eliminar', $_GET['id'] );
    
    setcookie("treeTableActividad","0",0);
    setcookie("treeTable","0",0);
    header("Location: index.php?com=actividad");
  
?>