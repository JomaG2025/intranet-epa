<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');

    Control::descripcion($_POST['actividad'], $_POST['descripcion']);
    require("componentes/com_auditoria/clase/auditoria.class.php");
    Auditoria::insertar($_GET['com'], 'actualizar', $_POST['actividad'] );
  
    header("Location: index.php?com=control&accion=detalle&id=".  $_POST['control']);
  
?>