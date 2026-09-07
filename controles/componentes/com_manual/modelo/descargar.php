<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
        
    require("includes/friendly.inc.php");    
        
    $manual = Manual::seleccionar($_GET['id']);
$archivo = HTTP_UPLOAD . $manual['archivo'];

$extension = substr(strrchr($archivo, '.'), 1);
 
if (is_file($archivo))
{
while(ob_get_level())
        ob_end_clean();

header('Content-Disposition: attachment; filename='.  friendly($manual['nombre']) .'.'. $extension );
header("Content-type: application/octet-stream");
        header("Content-Type: application/force-download");
readfile($archivo);
}
else
{
        header('HTTP/1.0 404 Not Found');
die("El archivo no existe.");
}
?>