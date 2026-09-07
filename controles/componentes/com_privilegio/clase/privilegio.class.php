<?php

class Privilegio{
        
        public static function actualizar($array , $usuario, $contrato, $ano){
            
            unset($array['usuario']);
            unset($array['contrato']);
            unset($array['ano']);
                
        mysql_query("DELETE FROM ".  DB_PREFIX  ."privilegio WHERE usuario='".  (int)$usuario  ."' AND ano = '". (int)$ano ."' AND actividad IN (SELECT id FROM ".  DB_PREFIX  ."actividad WHERE contrato = '".  (int)$contrato  ."')");
            
            foreach($array as $actividad => $privilegios){
            
            $apr = $dig = $ale = 0;
            
            foreach($privilegios as $privilegio){
            
            $apr = ($privilegio == 'apr') ? 1 : $apr;
            $dig = ($privilegio == 'dig') ? 1 : $dig;
            $ale = ($privilegio == 'ale') ? 1 : $ale;
            
            }
            
                mysql_query("INSERT INTO ".  DB_PREFIX  ."privilegio (usuario, actividad, ano, apr, dig, ale) VALUES ('".  (int)$usuario  ."', '".  (int)$actividad  ."', '".  (int)$ano  ."', '".  $apr  ."', '".  $dig  ."', '".  $ale  ."') ON DUPLICATE KEY UPDATE apr='".  $apr  ."', dig='".  $dig  ."', ale='".  $ale  ."'");
     
            }
        }
        
        public static function comprobar($usuario, $actividad, $ano){
            
            $result = mysql_query("SELECT * FROM ".  DB_PREFIX  ."privilegio WHERE usuario='".  (int)$usuario  ."' AND actividad='".  (int)$actividad  ."' AND ano='". (int)$ano ."' AND apr ='1'");            
            if($row = mysql_fetch_assoc($result)){
                return true;
            }else{
                return false;
            }
        }
        
        public static function listar($usuario, $contrato, $ano){
            
            $listar = array();
            
$result=mysql_query("SELECT A.id, A.capitulo, A.nombre, P.apr, P.dig, P.ale
                                FROM ".  DB_PREFIX  ."actividad A
                                LEFT OUTER JOIN ".  DB_PREFIX  ."privilegio P
                                ON P.actividad = A.id AND P.usuario='".  (int)$usuario  ."' AND P.ano = '".  (int)$ano  ."'
                                WHERE A.contrato = '".  (int)$contrato  ."' 
                                ORDER BY A.capitulo");

            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;
        }
        
        public static function seleccionar($actividad, $ano){
            
            $seleccionar = array();
            
$result=mysql_query("SELECT U.usuario AS nombre, P.usuario, P.apr, P.dig, P.ale  
                                FROM ".  DB_PREFIX  ."privilegio P
                                LEFT OUTER JOIN ".  DB_PREFIX  ."usuario U
                                ON P.usuario = U.id
                                WHERE U.eliminado = 0 AND P.actividad='".  (int)$actividad  ."' AND P.ano='". (int)$ano ."'");


            while($row=mysql_fetch_assoc($result)){
array_push($seleccionar, $row);
}

            return $seleccionar;
            
        }      
}

?>