<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');

require("componentes/com_control/clase/control.class.php");
    require("componentes/com_registro/clase/registro.class.php");
    require("componentes/com_auditoria/clase/auditoria.class.php");
    require("componentes/com_notificacion/clase/notificacion.class.php");
    require("componentes/com_usuario/clase/usuario.class.php");
    require("includes/class.phpmailer.php");
    require("includes/class.smtp.php");
    require("includes/friendly.inc.php");
    
    if( ! empty($_FILES['adjunto']['name']))
    {
        $extension = substr(strrchr($_FILES['adjunto']['name'], '.'), 1);  
  $archivo = 'adjuntos/'.  Date("Y")  .'/'.  Date("m")  .'/'.  uniqid()  .'.'.  $extension;

        if( ! is_dir(HTTP_UPLOAD  .'adjuntos/'.  Date("Y"))){
       mkdir(HTTP_UPLOAD  .'adjuntos/'.  Date("Y"), 0755);
    }
            
        if( ! is_dir(HTTP_UPLOAD  .'adjuntos/'.  Date("Y")  .'/'.  Date("m"))){
       mkdir(HTTP_UPLOAD  .'adjuntos/'.  Date("Y")  .'/'.  Date("m"), 0755);
    }          
            
        if(move_uploaded_file($_FILES['adjunto']['tmp_name'], HTTP_UPLOAD  .  $archivo))
        {
        chmod(HTTP_UPLOAD  .  $archivo, 0755);
    
$_POST['archivo'] = $archivo;
$_POST['size'] = format_filesize(filesize(HTTP_UPLOAD  .  $archivo));
$_POST['checksum'] = md5_file(HTTP_UPLOAD  .  $archivo);
$_POST['extension'] = $extension;
        }
        else
        {
        die('Error por tamaño de archivo.');
        }
        
    }
        
    if($id = Adjunto::insertar($_POST))
    {
        Control::cambiar_estado($_POST['control'] , 'PEN');
        Registro::actualizar($_POST['control'] , 'PEN');
        Auditoria::insertar($_GET['com'], 'insertar', $id );
        Auditoria::insertar('control', 'estado PEN', $_POST['control']);
        Notificacion::notificar_aprobadores($_POST['control']);
    }
       
    header("Location: index.php?com=control&accion=detalle&id=".  $_POST['control']  ."");
  
?>