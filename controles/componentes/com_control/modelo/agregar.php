<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    require("componentes/com_actividad/clase/actividad.class.php");
    require("componentes/com_manual/clase/manual.class.php");

    if(isset($_GET['actividad']))
    {
        $template->assign('actividad', $_GET['actividad']);
    }
    else
    {
        $template->assign('actividad', NULL);
    }
    
    $template->assign('actividades', Actividad::listar());
    $template->assign('array_meses' , $array_meses);
    
?>