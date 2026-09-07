<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    require("componentes/com_contrato/clase/contrato.class.php");
    
    if(!empty($_GET['contrato']))
{
if($_SESSION[SESSION_KEY.'contrato'] != $_GET['contrato'])
{
setcookie("treeTableActividad","0",0);
setcookie("treeTable","0",0);
}
}
    
    $_SESSION[SESSION_KEY.'contrato'] = (empty($_GET['contrato'])) ? $_SESSION[SESSION_KEY.'contrato'] : $_GET['contrato'];
    
    if(!Contrato::seleccionar($_SESSION[SESSION_KEY.'contrato']))
    {
   $contratos = Contrato::listar();   
   $_SESSION[SESSION_KEY.'contrato'] = $contratos[0]['id'];
    }
    
    if(!isset($_COOKIE["treeTableActividad"]))
    {
        setcookie("treeTableActividad","0",0);
    }
    
    $actividades = Actividad::crear_tabla();
    $template->assign('actividades', $actividades[0]);
    $template->assign('map1', $actividades[1]);
    $template->assign('contratos', Contrato::listar());
    $template->assign('contrato', $_SESSION[SESSION_KEY.'contrato']);
    
?>