<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    require("componentes/com_auditoria/clase/auditoria.class.php");
        
    if(isset($_POST['id']))
    {
        if($usuario = Usuario::actualizar($_POST, $_POST['id']))
        {
        Auditoria::insertar($_GET['com'], 'actualizar', $_POST['id'] );
    }
    }
    else
{
        if($id = Usuario::insertar($_POST))
        {
Auditoria::insertar($_GET['com'], 'insertar', $id );
}   
    }
        
    header("Location: index.php?com=usuario");
?>