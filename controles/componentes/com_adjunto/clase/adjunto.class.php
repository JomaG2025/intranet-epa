<?php

class Adjunto{
        
        public static function eliminar($id){
            
            $result = mysql_query("SELECT actividad, control FROM ".  DB_PREFIX  ."adjunto WHERE id='".  (int)$id  ."'");
            
            $row = mysql_fetch_assoc($result);
            
            if(self::verificar_privilegio($_SESSION[SESSION_KEY.  'id'], $row['actividad'], self::seleccionar_ano($row['control']))){
                
                if(self::verificar_estado($row['control'])){
                    
                    self::eliminar_archivo($id);
            
                    mysql_query("DELETE FROM ".  DB_PREFIX  ."adjunto WHERE id='".  (int)$id  ."'");
                    
                }
                
            }
        }
        
        public static function eliminar_adjuntos($control){
            
            self::eliminar_archivos($control);
            
            mysql_query("DELETE FROM ".  DB_PREFIX  ."adjunto WHERE control='".  (int)$control  ."'");
            
        }
        
        private static function eliminar_archivo($id){
            
            $result = mysql_query("SELECT archivo FROM ".  DB_PREFIX  ."adjunto WHERE id='".  (int)$id  ."' ");
            $row = mysql_fetch_row($result);
            
            if(is_file(HTTP_UPLOAD  .  $row[0])){     
                unlink(HTTP_UPLOAD  .  $row[0]);
            }
            
        }
        
        private static function eliminar_archivos($control){
            
            $result = mysql_query("SELECT archivo FROM ".  DB_PREFIX  ."adjunto WHERE control='".  (int)$control  ."' ");
            while($row = mysql_fetch_row($result)){
            
                if(is_file(HTTP_UPLOAD  .  $row[0])){     
                    unlink(HTTP_UPLOAD  .  $row[0]);
                }
            }
            
        } 
        
        public static function insertar($array){
            
            if(self::verificar_privilegio($_SESSION[SESSION_KEY.  'id'], $array['actividad'], self::seleccionar_ano($array['control']))){
    
                if(self::verificar_estado($array['control'])){
                        
                $keys = array();
$values = array();

foreach($array as $key => $value){

array_push($keys, mysql_real_escape_string($key));
array_push($values, (trim($value) == '') ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'");
}

        mysql_query("INSERT INTO ".  DB_PREFIX  ."adjunto (". implode(',', $keys) .") VALUES (". implode(',', $values) .")");  
                    
                    return mysql_insert_id();
                    
                }else{
                    
                    return false;
                    
                }
                
            }else{
                
                return false;
                
            }

        }
        
        public static function listar($control){
            
            $listar = array();
            
$result=mysql_query("SELECT A.*, DATE_FORMAT(A.fecha, '%d-%m-%Y') AS fecha, DATE_FORMAT(A.fecha, '%H:%i') AS hora, U.usuario AS nombre_usuario, U.id AS usuario 
                                FROM ".  DB_PREFIX  ."adjunto A
                                LEFT OUTER JOIN ".  DB_PREFIX  ."usuario U
                                ON A.usuario = U.id 
                                WHERE A.control='".  (int)$control  ."' ORDER BY A.id DESC ");

            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;
            
        }
        
        public static function listar_otros($actividad, $control){
            
            $where = "AND actividad='".  (int)$actividad  ."' AND control != '".  (int)$control  ."' ";
                        
            $listar = array();
            
$result=mysql_query("SELECT *, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha FROM ".  DB_PREFIX  ."adjunto WHERE archivo!='' $where ORDER BY id DESC LIMIT 0, 10");

            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;

        }
        
        public static function seleccionar($id){
$row=mysql_fetch_assoc(mysql_query("SELECT *, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha FROM ".  DB_PREFIX  ."adjunto WHERE id='".  (int)$id  ."'"));
return $row;
}
        
        public static function seleccionar_ultimo($control){
$row=mysql_fetch_assoc(mysql_query("SELECT *, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha FROM ".  DB_PREFIX  ."adjunto WHERE control='".  (int)$control  ."' ORDER BY fecha DESC LIMIT 0, 1"));
return $row;
}
        
        private static function verificar_estado($control){
            
            $result = mysql_query("SELECT estado FROM ".  DB_PREFIX  ."control WHERE id='".  (int)$control  ."'");
            $row=mysql_fetch_row($result);
            if($row[0] != 'APR'){
                
                return true;
                
            }else{
                
                return false;
            
            }    
        }
        
        private static function seleccionar_ano($control){
            
            $row=mysql_fetch_assoc(mysql_query("SELECT YEAR(fecha) AS ano FROM ".  DB_PREFIX  ."control WHERE id='".  (int)$control  ."'"));
return $row['ano'];   
        }
        
        private static function verificar_privilegio($usuario, $actividad, $ano){
            
            $result = mysql_query("SELECT * FROM ".  DB_PREFIX  ."privilegio WHERE usuario='".  (int)$usuario  ."' AND actividad='".  (int)$actividad."' AND ano='". (int)$ano ."' AND dig='1' ");
            if($row=mysql_fetch_row($result)){
                
                return true;
                
            }else{
                
                return false;
            
            }
            
        }                
       
       
}
?>