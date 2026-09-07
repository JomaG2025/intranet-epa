<?php

class Resultado{
         
        public static function insertar($array){
            
            if(self::verificar_privilegio($_SESSION[SESSION_KEY.  'id'], $array['actividad'], self::seleccionar_ano($array['control']))){
                $keys = '';
    $values = '';
    foreach($array as $key => $value){
    
    $keys .= mysql_real_escape_string($key).',';
    
        $values .= "'".  htmlspecialchars(mysql_real_escape_string($value))  ."',";
    
    }
                
    $keys = substr($keys, 0, -1);
    $values = substr($values, 0, -1);
    mysql_query("INSERT INTO ".  DB_PREFIX  ."resultado ($keys) VALUES ($values) ON DUPLICATE KEY UPDATE observacion='$array[observacion]', estado='$array[estado]'");
       
                return mysql_insert_id();
           
           }else{
            
                return false;
            
           }
                    
        }
        
        public static function eliminar($control){
            
            mysql_query("DELETE FROM ".  DB_PREFIX  ."resultado WHERE control='".  (int)$control  ."'");
            
        }
        
        public static function seleccionar($control){
            $row=mysql_fetch_assoc(mysql_query("SELECT * FROM ".  DB_PREFIX  ."resultado WHERE control='".  (int)$control  ."'"));
return $row;
        }
        
        private static function seleccionar_ano($control){
            
            $row=mysql_fetch_assoc(mysql_query("SELECT YEAR(fecha) AS ano FROM ".  DB_PREFIX  ."control WHERE id='".  (int)$control  ."'"));
return $row['ano'];   
        }
        
        private static function verificar_privilegio($usuario, $actividad, $ano){
            
            $result = mysql_query("SELECT * FROM ".  DB_PREFIX  ."privilegio WHERE usuario='".  (int)$usuario  ."' AND actividad='".  (int)$actividad."' AND ano='". (int)$ano ."' AND apr='1' ");
            if($row=mysql_fetch_row($result)){
                
                return true;
                
            }else{
                
                return false;
            
            }
            
        } 
}

?>