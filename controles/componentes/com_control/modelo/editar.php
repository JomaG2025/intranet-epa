<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    require("componentes/com_actividad/clase/actividad.class.php");
    
    $control = Control::seleccionar($_GET['id']);
    $fecha = explode('-', $control['fecha']);
    $min = $fecha[0].'-'.$fecha[1].'-02';
    $max = (($fecha[1] == 12) ? ($fecha[0]+1).'-'.'01' : $fecha[0].'-'.($fecha[1]+1)).'-01';

    $template->assign('control', $control);
    $template->assign('fechamin', $min); 
    $template->assign('fechamax', $max); 
    $template->assign('actividades', Actividad::listar());
    $template->assign('array_estados', $array_estados);
    
?>