<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    require("componentes/com_usuario/clase/usuario.class.php");
    require("componentes/com_contrato/clase/contrato.class.php");
    require("componentes/com_control/clase/control.class.php");
    
    $_SESSION[SESSION_KEY.'contrato'] = (empty($_GET['contrato'])) ? $_SESSION[SESSION_KEY.'contrato'] : $_GET['contrato'];
    
    if( ! Contrato::seleccionar($_SESSION[SESSION_KEY.'contrato']))
    {
   $contratos = Contrato::listar();
   $_SESSION[SESSION_KEY.'contrato'] = $contratos[0]['id'];
    }

    $anos = Control::listar_anos($_SESSION[SESSION_KEY.'contrato']);
    $_SESSION[SESSION_KEY.'ano'] = (empty($_GET['ano'])) ? $_SESSION[SESSION_KEY.'ano'] : $_GET['ano'];
    if( ! in_array($_SESSION[SESSION_KEY.'ano'], $anos)) $_SESSION[SESSION_KEY.'ano'] = $anos[0];
    
    if( ! empty($_GET['contrato']))
{
if($_SESSION[SESSION_KEY.'contrato'] != $_GET['contrato'])
{
setcookie("treeTableActividad","0",0);
setcookie("treeTable","0",0);
}
}

    $actividades = Privilegio::listar($_GET['usuario'], $_SESSION[SESSION_KEY.'contrato'], $_SESSION[SESSION_KEY.'ano']);
    
    foreach($actividades as $key => $value)
    {
        $actividades[$key]['capitulo_format'] = str_replace(".", "_", $value['capitulo']);   
    }
    
    $template->assign('usuario', Usuario::seleccionar($_GET['usuario']));
    $template->assign('anos', $anos);
    $template->assign('contratos', Contrato::listar());
    $template->assign('ano', $_SESSION[SESSION_KEY.'ano']);
    $template->assign('contrato', $_SESSION[SESSION_KEY.'contrato']);
    $template->assign('actividades',$actividades);
    
?>