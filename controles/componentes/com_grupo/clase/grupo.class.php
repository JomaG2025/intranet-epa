<?php

class Grupo{

        public static function listar($pagina = ''){

            $listar = array();
$result=mysql_query("SELECT *, (SELECT COUNT(id) FROM ".  DB_PREFIX  ."grupo) AS total FROM ".  DB_PREFIX  ."grupo ORDER BY id DESC");

            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;
        }

        public static function seleccionar($id){
$row=mysql_fetch_assoc(mysql_query("SELECT * FROM ".  DB_PREFIX  ."grupo WHERE id='".  (int)$id  ."'"));
return $row;
}
       
}
?>