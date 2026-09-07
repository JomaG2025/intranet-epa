<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');

    
    #SI NO EXISTE UN COMPONENTE SELECCIONADO SE ASIGNA EL COMPONENTE CONTROL 
    $_GET['com'] = ( ! isset($_GET['com'])) ? 'control' : addslashes($_GET['com']);
        
    
    #SI NO EXISTE UNA ACCION SELECCIONADA SE ASIGNA LA ACCION VER      
    $_GET['accion'] = ( ! isset($_GET['accion'])) ? 'index' : addslashes($_GET['accion']);  
    
    #MODELO POR DEFECTO, SOLO SI EXISTE
    if(is_file("componentes/com/modelo/".  $_GET['accion'].  ".php"))
    {
        require("componentes/com/modelo/".  $_GET['accion'].  ".php");  
    }

    #CONTROLADOR Y MODELOS SELECCIONADOS
    if(is_file("componentes/com_".  $_GET['com']  ."/controlador/index.php"))
    {
        require("componentes/com_".  $_GET['com']  ."/controlador/index.php");
    }
    else
    {
        die();
    } 
    
    if(is_file("componentes/com_".  $_GET['com']  ."/modelo/".  $_GET['accion']  .".php"))
    {
        require("componentes/com_".  $_GET['com']  ."/modelo/".  $_GET['accion']  .".php"); 
    }
    else
    {
        die();
    }
    
    $_SERVER['HTTP_REFERER'] = ( ! isset($_SERVER['HTTP_REFERER'])) ? 'index.php' : $_SERVER['HTTP_REFERER'];    
                
    $template->assign('REFERER', $_SERVER['HTTP_REFERER']);
    $template->assign('SESSION_USUARIO', $_SESSION[SESSION_KEY.  'usuario']);
    $template->assign('SESSION_ID', $_SESSION[SESSION_KEY.  'id']);
    $template->assign('SESSION_TIPO', $_SESSION[SESSION_KEY.  'tipo']);
    $template->assign('SESSION_NOMBRE', $_SESSION[SESSION_KEY.  'nombre']);
    $template->assign('SESSION_SEXO', $_SESSION[SESSION_KEY.  'sexo']);
    $template->assign('HTTP_SERVER', HTTP_SERVER);   
    $template->assign('COM', $_GET['com']);   
    
    if($_GET['accion'] != 'descargar')
    {
        $template->display('header.tpl'); 
        $template->display('componentes/com_'.  $_GET['com']  .'/vista/'.  $_GET['accion']  .'.tpl');
        $template->display('footer.tpl'); 
    }

?>