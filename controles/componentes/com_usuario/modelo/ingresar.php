<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
        
require_once("componentes/com_usuario/clase/usuario.class.php");
        
        $REFERER = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        setcookie("treeTableActividad", " ", time()-3600);
setcookie("treeTable", " ", time()-3600);

if(!empty($_COOKIE['puertoaricaintranet']))
{
$cookie = unserialize($_COOKIE['puertoaricaintranet']);

if(Usuario::autentificar($cookie['session_id'])){
            
            require("componentes/com_auditoria/clase/auditoria.class.php");
Auditoria::insertar('usuario', 'autentificar', $_SESSION[SESSION_KEY.'id'] );
            
            header('location: '. HTTP_SERVER .'index.php#expandir');
}
}  
        
        $template->assign('ANO', Date('Y'));
        $template->assign('HTTP_INTRANET', HTTP_INTRANET);
        $template->assign('HTTP_GRAFICAS', HTTP_GRAFICAS);
        $template->assign('REFERER', $REFERER);        
                    
?>