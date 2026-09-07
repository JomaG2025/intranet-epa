<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    $usuarios = Usuario::listar();
    
    foreach($usuarios as $key => $usuario)
    {
    $usuarios[$key]['tipo_nombre'] = $array_usuarios[$usuario['tipo']];
    }
    
    $template->assign('usuarios', $usuarios);

?>