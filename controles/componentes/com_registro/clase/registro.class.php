<?php

class Registro{
           
        public static function actualizar($id, $estado){
            mysql_query("INSERT INTO ".  DB_PREFIX  ."registro (control, estado) VALUES ('$id', '$estado') ON DUPLICATE KEY UPDATE estado='$estado'");
        }
        
        public static function seleccionar($id){
$row=mysql_fetch_assoc(mysql_query("SELECT *, DATE_FORMAT(fecha, '%H:%i') AS hora FROM ".  DB_PREFIX  ."registro WHERE control='".  (int)$id  ."'"));
return $row;
} 
}

?>