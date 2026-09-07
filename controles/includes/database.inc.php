<?php

    function db_connect(){

$connect = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
mysql_set_charset('utf8', $connect);

if (!$connect) {
echo "<B>Error</B>: No hay conexión a la Base de Datos<br>";
}else mysql_select_db(DB_DATABASE, $connect) or die ("error seleccionando la base de datos");

return true;
}
    
    db_connect();
    
?>