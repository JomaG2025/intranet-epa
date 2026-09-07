<?php define('DIRECT_ACCESS', true);
    require("../../../configuracion.php");
    require("../../../includes/database.inc.php");
    require("../../../componentes/com_actividad/clase/actividad.class.php");    
    
    $actividad = Actividad::seleccionar($_GET['id']);
    echo json_encode($actividad);    
    
?>