<?php

class Contrato{

public static function activo($id){
            
            $row=mysql_fetch_assoc(mysql_query("SELECT activo FROM ".  DB_PREFIX  ."contrato WHERE id='".  (int)$id  ."'"));
            $activo = ($row['activo']==0) ? 1 : 0;
            mysql_query("UPDATE ".  DB_PREFIX  ."contrato SET activo='$activo' WHERE id='".  (int)$id  ."'");
         
            if($activo == 1){
                return 'activar';
            }else if($activo == 0){
                return 'desactivar';
            }   
        }
                     
        public static function actualizar($id, $array){
            
            $array['nombre'] = self::verificar_nombre($array['nombre'], $id);
            
            $values = array();
            
foreach($array as $key => $value)
{
                array_push($values, mysql_real_escape_string($key)  ."=". (trim($value) == '' ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'"));
            }
            
mysql_query("UPDATE ".  DB_PREFIX  ."contrato SET ". implode(',', $values) ." WHERE id='".  (int)$id  ."'");
}             
        
        public static function eliminar($id){

            mysql_query("DELETE FROM ".  DB_PREFIX  ."contrato WHERE id='".  (int)$id  ."'");      
        
        }
        
        public static function insertar($array){

            $array['nombre'] = self::verificar_nombre($array['nombre'], '');
            
            $keys = array();
$values = array();

foreach($array as $key => $value)
{
array_push($keys, mysql_real_escape_string($key));
array_push($values, (trim($value) == '') ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'");
}

mysql_query("INSERT INTO ".  DB_PREFIX  ."contrato (". implode(',', $keys) .") VALUES (". implode(',', $values) .")");
            
            return mysql_insert_id();

        }
        
        public static function listar(){
        
            $listar = array();
            
$result=mysql_query("SELECT *, (SELECT COUNT(id) FROM ".  DB_PREFIX  ."contrato) AS total FROM ".  DB_PREFIX  ."contrato ORDER BY id DESC");

            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;

        }
        
        public static function seleccionar($id){
$row=mysql_fetch_assoc(mysql_query("SELECT * FROM ".  DB_PREFIX  ."contrato WHERE id='".  (int)$id  ."'"));
return $row;
}
            
        private static function verificar_nombre($nombre,$id){
            
            $count = 1;
            $pass = false;
            $tmp = $nombre;
            
            $where = ($id) ? "AND id !='".  (int)$id  ."'" : '';
            
            while($pass == false){
                
                $result = mysql_query("SELECT * FROM ".  DB_PREFIX  ."contrato WHERE nombre='".   mysql_real_escape_string($tmp)  ."' $where");
                
                if($row = mysql_fetch_assoc($result)){
                    $count++;
                    $tmp = $nombre.$count;
                }else{
                    $pass = true;
                }
            }
            
            $nombre = ($count>1) ? $nombre.$count : $nombre;
            
            return $nombre;      
            
        }
}
?>