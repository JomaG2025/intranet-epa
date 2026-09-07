<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    require("componentes/com_auditoria/clase/auditoria.class.php");

    setcookie("treeTableActividad","0",0);
    setcookie("treeTable","0",0);
    
    $capitulo = explode('.', $_POST['capitulo']);
    
    foreach($capitulo as $key => $value)
    {
    
    if(is_numeric($value))
    {
    if(($value != '00') OR ($value != '0'))
    {
    $capitulo[$key] = sprintf("%02d", $value);
    }
    else
    {
    unset($capitulo[$key]);
    }
    
    }
    else
    {
    $capitulo[$key] = strtoupper($value);  
    }   
    }
    
    $_POST['padre'] = (empty($_POST['padre'])) ? 0 : 1;
    $_POST['capitulo'] = implode('.' , $capitulo);        
    
    if(isset($_POST['id']))
    {
        Actividad::actualizar($_POST['id'], $_POST);
        Auditoria::insertar($_GET['com'], 'actualizar', $_POST['id'] );
    }
    else
    {        
        $id = Actividad::insertar($_POST);
        Auditoria::insertar($_GET['com'], 'insertar', $id ); 
    }
        
    header("Location: index.php?com=actividad");
  
?>