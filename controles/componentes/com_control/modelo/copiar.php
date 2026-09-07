<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }

    require("componentes/com_auditoria/clase/auditoria.class.php");
    require("componentes/com_registro/clase/registro.class.php");
    
    
    $controles = Control::copiar($_GET['contrato']);
           
    foreach($controles as $control)
    {
    if($control)
    {
    Auditoria::insertar($_GET['com'], 'insertar', $control );
    Registro::actualizar($control, 'PLA');
    }
    }
    
    header("Location: index.php");
    
?>