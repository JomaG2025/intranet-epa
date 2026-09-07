<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
        
    $_GET['estado'] = (empty($_GET['estado'])) ? FALSE : $_GET['estado'];
    
    $tareas = Tarea::listar();
    
    foreach($tareas as $key => $value)
    {
        $tareas[$key]['estado_glosa'] = $array_estados[$value['estado']];
    }
    
    $template->assign('tareas', $tareas);
    
?>