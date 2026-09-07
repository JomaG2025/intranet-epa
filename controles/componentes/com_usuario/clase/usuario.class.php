<?php

class Usuario{
   
        public static function activo($id){
            
            $row=mysql_fetch_assoc(mysql_query("SELECT activo FROM ".  DB_PREFIX  ."usuario WHERE id='".  (int)$id  ."'"));
            $activo = ($row['activo']==0) ? 1 : 0;
            mysql_query("UPDATE ".  DB_PREFIX  ."usuario SET activo='$activo' WHERE id='".  (int)$id  ."'");
         
            if($activo == 1){
                return 'activar';
            }else if($activo == 0){
                return 'desactivar';
            }   
        }
               
        public static function actualizar($array , $id){
            
$values = array();

foreach($array as $key => $value)
{
                array_push($values, mysql_real_escape_string($key)  ."=". (trim($value) == '' ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'"));
            }
            
mysql_query("UPDATE ".  DB_PREFIX  ."usuario SET ". implode(',', $values) ." WHERE id='".  (int)$id  ."'");
}

        public static function autentificar($session_id)
        {        
            mysql_select_db(DB_DATABASE_LOGIN);
            $result=mysql_query("SELECT * FROM int_sesion WHERE session_id='".  $session_id  ."'"); 
            
            if($row = mysql_fetch_assoc($result)){
            
            $row['user_data'] = preg_replace_callback ( '!s:(\d+):"(.*?)";!',
    function($match) {
        return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
    },
$row['user_data']);

            
            if( ! empty($row['user_data']))
{
$user_data = unserialize($row['user_data']);

                    mysql_select_db(DB_DATABASE);
$result=mysql_query("SELECT * FROM ".  DB_PREFIX  ."usuario WHERE usuario='".  $user_data['usuario']  ."' AND activo='1' AND eliminado='0'"); 
             
            if($row = mysql_fetch_assoc($result)){
                
                $_SESSION[SESSION_KEY.'id'] = $row['id'];
        $_SESSION[SESSION_KEY.'nombre'] = utf8_encode($user_data['nombre']);
        $_SESSION[SESSION_KEY.'usuario'] = $user_data['usuario']; 
        $_SESSION[SESSION_KEY.'tipo'] = $row['tipo'];
        $_SESSION[SESSION_KEY.'sexo'] = $user_data['sexo'];
                $_SESSION[SESSION_KEY.'resultados'] = 10;
                $_SESSION[SESSION_KEY.'ver'] = 'ano';
                $_SESSION[SESSION_KEY.'ano'] = Date('Y');
                $_SESSION[SESSION_KEY.'contrato'] = !empty($row['contrato_id']) ? $row['contrato_id'] : CONTRATO_POR_DEFECTO;
                $_SESSION[SESSION_KEY.'contrato_fijo'] = !empty($row['contrato_id']);

                        return TRUE;
            }

}
}
            
        }
        
        public static function eliminar($id){
            mysql_query("UPDATE ".  DB_PREFIX  ."usuario SET eliminado = 1 WHERE id='".  (int)$id  ."'");            
        }

        public static function purgar($id){
            mysql_query("DELETE FROM ".  DB_PREFIX  ."usuario WHERE id='".  (int)$id  ."'");             
        }
        
        public static function insertar($array){
            
            if(self::verificar_usuario($array['usuario'])){
            
$keys = array();
$values = array();

foreach($array as $key => $value)
{
array_push($keys, mysql_real_escape_string($key));
array_push($values, (trim($value) == '') ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'");
}

mysql_query("INSERT INTO ".  DB_PREFIX  ."usuario (". implode(',', $keys) .") VALUES (". implode(',', $values) .")");
            
            return mysql_insert_id();
            
        }else{
        return false;
        }
            
}

        public static function listar($pagina = '', $orden = '', $eliminados = FALSE){

            $limit = ($pagina) ? "LIMIT "  .(((int)$pagina*$_SESSION[SESSION_KEY.'resultados'])-$_SESSION[SESSION_KEY.'resultados']).  ", ".  $_SESSION[SESSION_KEY.'resultados'] : '';
            
            $order = ($orden) ? 'ORDER BY '.  mysql_real_escape_string($orden) : 'ORDER BY id DESC';
                        
            $listar = array();
            
if( ! $eliminados) $result=mysql_query("SELECT *, (SELECT COUNT(id) FROM ".  DB_PREFIX  ."usuario WHERE eliminado = 0) AS total FROM ".  DB_PREFIX  ."usuario WHERE eliminado = 0 $order $limit ");
else $result=mysql_query("SELECT *, (SELECT COUNT(id) FROM ".  DB_PREFIX  ."usuario) AS total FROM ".  DB_PREFIX  ."usuario  $order $limit ");
            
            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;

        }
        
        public static function seleccionar($id){
$row=mysql_fetch_assoc(mysql_query("SELECT * FROM ".  DB_PREFIX  ."usuario WHERE id='".  (int)$id  ."'"));
return $row;
}
        
        private static function verificar_usuario($usuario){
            
            $result = mysql_query("SELECT * FROM ".  DB_PREFIX  ."usuario WHERE usuario='".   mysql_real_escape_string($usuario)  ."'");
                
            if($row = mysql_fetch_assoc($result)){
return false;
            }else{
            return true;
            }
        }

        public static function listar_intranet(){

            mysql_select_db(DB_DATABASE_LOGIN);
            mysql_set_charset('utf8');
            $listar = array();
            $result=mysql_query("SELECT *, (SELECT COUNT(id) FROM int_usuario) AS total FROM int_usuario WHERE activo=1 AND eliminado=0");
            
            while($row=mysql_fetch_assoc($result)){
                array_push($listar, $row);
            }

            return $listar;

        }

        public static function seleccionar_intranet($usuario){
            mysql_select_db(DB_DATABASE_LOGIN);
            mysql_set_charset('utf8');
            $row=mysql_fetch_assoc(mysql_query("SELECT * FROM int_usuario WHERE usuario='".  $usuario ."'"));
            return $row;
        }

        
}

?>