<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    require("componentes/com_contrato/clase/contrato.class.php");
    
    if( ! empty($_GET['contrato']) AND empty($_SESSION[SESSION_KEY.'contrato_fijo']))
{
if($_SESSION[SESSION_KEY.'contrato'] != $_GET['contrato'])
{
            $expandir_arbol = TRUE;
setcookie("treeTableActividad","0",0);
setcookie("treeTable","0",0);
}
}

if(empty($_SESSION[SESSION_KEY.'contrato_fijo'])) {
    $_SESSION[SESSION_KEY.'contrato'] = (empty($_GET['contrato'])) ? $_SESSION[SESSION_KEY.'contrato'] : $_GET['contrato'];
}

    if( ! Contrato::seleccionar($_SESSION[SESSION_KEY.'contrato']))
    {
   $contratos = Contrato::listar();
   $_SESSION[SESSION_KEY.'contrato'] = $contratos[0]['id'];
    }

    if( ! isset($_COOKIE["treeTable"])) setcookie("treeTable","0",0);
        
    $_GET['ver'] = (empty($_GET['ver'])) ? $_SESSION[SESSION_KEY.'ver'] : $_GET['ver'];
    $_GET['mes'] = (empty($_GET['mes'])) ? date("n") : $_GET['mes'];
    
    $anos = Control::listar_anos($_SESSION[SESSION_KEY.'contrato']);
    
    $_SESSION[SESSION_KEY.'ano'] = (empty($_GET['ano'])) ? $_SESSION[SESSION_KEY.'ano'] : $_GET['ano'];

    if( ! in_array($_SESSION[SESSION_KEY.'ano'], $anos)) $_SESSION[SESSION_KEY.'ano'] = $anos[0];

    if($_GET['ver'] == 'ano')
    {
        $_SESSION[SESSION_KEY.'ver'] = 'ano';   
        $controles = Control::vista_normal($_SESSION[SESSION_KEY.'ano']);
    }
    else if($_GET['ver'] == 'mes')
    {  
        $_SESSION[SESSION_KEY.'ver'] = 'mes';
        $controles = Control::vista_alternativa($_SESSION[SESSION_KEY.'ano'], $_GET['mes']);
    }

    if( ! empty($expandir_arbol)) header('location: '. HTTP_SERVER .'index.php#expandir');
        
    $template->assign('controles', $controles[0]);
    $template->assign('map1', $controles[1]);
    $template->assign('anos', $anos);
    $template->assign('contratos', Contrato::listar());
    $template->assign('ano', $_SESSION[SESSION_KEY.'ano']);
    $template->assign('contrato', $_SESSION[SESSION_KEY.'contrato']);
    $template->assign('ver', $_SESSION[SESSION_KEY.'ver']);
    $template->assign('contrato_fijo', !empty($_SESSION[SESSION_KEY.'contrato_fijo']));
    $contrato_actual = Contrato::seleccionar($_SESSION[SESSION_KEY.'contrato']);
    $template->assign('contrato_nombre', $contrato_actual['nombre']);

?>