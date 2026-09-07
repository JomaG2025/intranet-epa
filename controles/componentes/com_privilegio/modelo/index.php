<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');

    require("componentes/com_usuario/clase/usuario.class.php");
    $template->assign('usuarios', Usuario::listar());
    
?>