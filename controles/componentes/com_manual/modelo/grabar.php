<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    require("componentes/com_auditoria/clase/auditoria.class.php");
    
    if( ! empty($_FILES['archivo']['name']))
    {
        $extension = substr(strrchr($_FILES['archivo']['name'], '.'), 1);  
  $archivo = 'manual/'.  uniqid()  .'.'.  $extension;
            
        if(move_uploaded_file($_FILES['archivo']['tmp_name'], HTTP_UPLOAD  .  $archivo))
        {
        chmod(HTTP_UPLOAD  .  $archivo, 0755);
$_POST['archivo'] = $archivo;
        }
        
    }           
    
    if(isset($_POST['id']))
    {
        Manual::actualizar($_POST['id'], $_POST);
        Auditoria::insertar($_GET['com'], 'actualizar', $_POST['id'] );
    }
    else
    {        
        $id = Manual::insertar($_POST);
        Auditoria::insertar($_GET['com'], 'insertar', $id ); 
    }
    
    header("Location: index.php?com=manual");
  
?>