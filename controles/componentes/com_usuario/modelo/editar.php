<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    $usuario = Usuario::seleccionar($_GET['id']);
    $template->assign('usuario', $usuario); 
    
?>