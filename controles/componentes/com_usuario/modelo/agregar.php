<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    $usuarios = Usuario::listar_intranet();
    $template->assign('usuarios', $usuarios);
    
?>