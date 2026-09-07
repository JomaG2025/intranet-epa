<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    require("componentes/com_contrato/clase/contrato.class.php");
    
    $template->assign('contratos', Contrato::listar());
    
?>