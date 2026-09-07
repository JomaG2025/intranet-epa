<?php

class Auditoria{       
        
        public static function insertar($objeto, $metodo, $instancia){
            
            require_once("includes/ip.inc.php");
            
            $usuario = $_SESSION[SESSION_KEY.  'usuario'];
            $ip = ip();
                     
            mysql_query("INSERT INTO ".  DB_PREFIX  ."auditoria (usuario, ip, objeto, metodo, instancia) 
                        VALUES ('$usuario', '$ip', '$objeto', '$metodo', '$instancia')");
        }         
}

?>