<?php

class Tarea{

        public static function listar(){
            
            $listar = Array();
            $result = mysql_query("SELECT C.fecha, C.estado, C.id, A.nombre, A.capitulo, P.usuario, 
                                (SELECT COUNT(DISTINCT ".  DB_PREFIX  ."control.id) FROM ".  DB_PREFIX  ."control INNER JOIN ".  DB_PREFIX  ."privilegio ON ".  DB_PREFIX  ."privilegio.actividad = ".  DB_PREFIX  ."control.actividad WHERE ".  DB_PREFIX  ."privilegio.usuario='".  $_SESSION[SESSION_KEY.  'id']   ."') AS total,
                                (SELECT ".  DB_PREFIX  ."actividad.nombre FROM ".  DB_PREFIX  ."actividad WHERE ".  DB_PREFIX  ."actividad.capitulo = SUBSTRING_INDEX(A.capitulo, '.' , 1) LIMIT 1) AS capitulo_padre 
                                FROM ".  DB_PREFIX  ."control C
                                INNER JOIN ".  DB_PREFIX  ."actividad A
                                ON C.actividad=A.id
                                INNER JOIN ".  DB_PREFIX  ."privilegio P
                                ON P.actividad=A.id AND P.ano = YEAR(C.fecha)
                                WHERE P.usuario='".  $_SESSION[SESSION_KEY.  'id']  ."' 
                                GROUP BY C.id
                                ORDER BY C.fecha DESC");
            
            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;
        }
    }


?>