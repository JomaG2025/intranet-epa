<?php 

    /** 
    *
    * @abstract  CONTROL CONTRATO CONCESIÓN
    * @versión 1.0.3
    * @autor Humberto Morales Gutierrez
    *
    */
    
//error_reporting(E_ALL ^ E_DEPRECATED);
//ini_set('display_errors', '0');

    session_start(); 
    define('DIRECT_ACCESS', true);
  
    #CARGA DE DEPENDENCIAS, ARCHIVOS DE CONFIGURACION E INICIALIZACION DE VARIABLES

    require("configuracion.php");
    require("librerias/smarty/Smarty.class.php");
    require("includes/params.inc.php");
    require("includes/format_filesize.inc.php");
    require("includes/database.inc.php");
   
    #INICIALIZACION SMARTY
   
    $template = new Smarty; 
    $template->template_dir = 'template/';
    $template->compile_dir = 'temp/smarty/';
    
    #SE DEFINE EL CHARSET
    header('Content-Type "text/html; charset=UTF-8');  
    
    #SE DETECTA LA SESION
    
    if( ! isset($_SESSION[SESSION_KEY.'usuario'])){
        
        require("componentes/com_usuario/modelo/ingresar.php");         
        $template->display('componentes/com_usuario/vista/ingresar.tpl'); 
        exit();
   
                
    }else{
        
        #CARGA CONTROLADOR POR DEFECTO
        
        require("componentes/com/controlador/index.php");            
        mysql_close();
        exit(); 

    }

?>