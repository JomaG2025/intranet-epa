<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');

require("componentes/com_contrato/clase/contrato.class.php");
require("componentes/com_grupo/clase/grupo.class.php");
require("componentes/com_manual/clase/manual.class.php");

    $template->assign('actividad', Actividad::seleccionar($_GET['id']));
    $template->assign('grupos', Grupo::listar());
    $template->assign('manuales', Manual::listar());    
    $template->assign('contratos', Contrato::listar());
    
?>