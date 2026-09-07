<?php

    defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
            
    Privilegio::actualizar($_POST, $_POST['usuario'], $_POST['contrato'], $_POST['ano']);
        
    require("componentes/com_auditoria/clase/auditoria.class.php");
    Auditoria::insertar($_GET['com'], 'actualizar', $_POST['usuario'] );
        
    header("Location: index.php?com=privilegio");
  
?>