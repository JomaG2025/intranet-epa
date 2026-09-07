<?php

class Manual{

        public static function actualizar($id, $array){
            
$values = array();

foreach($array as $key => $value)
{
                array_push($values, mysql_real_escape_string($key)  ."=". (trim($value) == '' ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'"));
            }
            
mysql_query("UPDATE ".  DB_PREFIX  ."manual SET ". implode(',', $values) ." WHERE id='".  (int)$id  ."'");
}             
        
        
        public static function eliminar($id){
            
            $row = mysql_fetch_row(mysql_query("SELECT archivo FROM ".  DB_PREFIX  ."manual WHERE id='$id'"));
            if(is_file(HTTP_UPLOAD  .  $row[0])){
                unlink(HTTP_UPLOAD  .  $row[0]);
            };
            
            mysql_query("DELETE FROM ".  DB_PREFIX  ."manual WHERE id='".  (int)$id  ."'");      
        
        }
        
        public static function insertar($array){
            
            $keys = array();
$values = array();

foreach($array as $key => $value)
{
array_push($keys, mysql_real_escape_string($key));
array_push($values, (trim($value) == '') ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'");
}

mysql_query("INSERT INTO ".  DB_PREFIX  ."manual (". implode(',', $keys) .") VALUES (". implode(',', $values) .")");
            return mysql_insert_id();
            
            return mysql_insert_id();

        } 
        
        public static function listar(){
            
            $listar = array();
            
$result=mysql_query("SELECT M.*, (SELECT COUNT(id) FROM ".  DB_PREFIX  ."manual) AS total, C.nombre AS nombre_contrato FROM ".  DB_PREFIX  ."manual M 
LEFT OUTER JOIN ".  DB_PREFIX  ."contrato C 
ON M.contrato = C.id 
ORDER BY M.id DESC");

            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;

        } 
        
        public static function seleccionar($id){
$row=mysql_fetch_assoc(mysql_query("SELECT * FROM ".  DB_PREFIX  ."manual WHERE id='".  (int)$id  ."'"));
return $row;
}
}

?>