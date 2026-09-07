<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
    
    if($_SESSION[SESSION_KEY.  'tipo'] != 'ADM'){ die(); }
    
    require("componentes/com_registro/clase/registro.class.php");
    require("componentes/com_auditoria/clase/auditoria.class.php");
    
    if(isset($_POST['id']))
    {
        $id = $_POST['id']; 
        
        Control::actualizar($_POST['id'], $_POST);
        Registro::actualizar($_POST['id'], $_POST['estado']);
        Auditoria::insertar($_GET['com'], 'actualizar', $_POST['id'] );
    
    }
    else
    {
    if(isset($_POST['actividad']))
    {
    $fecha = explode('-', $_POST['fecha']);
        $meses = $_POST['mes'];
        unset($_POST['mes']);
        
        if(($meses[0] == $fecha[1]) AND (count($meses)== 1))
        {  
            $id = Control::insertar($_POST);
            Registro::actualizar($id, 'PLA');
            Auditoria::insertar($_GET['com'], 'insertar', $id );    
        }
        else
        {
            foreach($meses as $value)
            {
                $dia  = $fecha[2];
                
                while(checkdate($value,$fecha[2],$fecha[0]) == false){
                    $fecha[2] = $fecha[2]-1;
                }
                                
                $_POST['fecha'] = implode('-', array($fecha[0], $value, $fecha[2]));
            
                $id = Control::insertar($_POST);
                Registro::actualizar($id, 'PLA');
                Auditoria::insertar('control', 'insertar', $id );     
            }      
        }
    }
    else
    {
    Die('Actividad, no existe actividad.');
    } 
    }
    
header("Location: index.php?com=control");
  
?>