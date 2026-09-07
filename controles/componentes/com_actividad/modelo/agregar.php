<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    require("componentes/com_grupo/clase/grupo.class.php");
    require("componentes/com_manual/clase/manual.class.php");
    require("componentes/com_contrato/clase/contrato.class.php");
    
    $template->assign('SESSION_CONTRATO', $_SESSION[SESSION_KEY.'contrato']);   
    $template->assign('grupos', Grupo::listar());
    $template->assign('manuales', Manual::listar('', $_SESSION[SESSION_KEY.'contrato']));
    $template->assign('contratos', Contrato::listar());
    
?>