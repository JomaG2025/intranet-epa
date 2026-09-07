<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }

    $contrato = Contrato::seleccionar($_GET['id']);
    
    $template->assign('contrato', $contrato);  
    
?>