<?php

class Actividad{

        public static function activo($id){
            
            $row=mysql_fetch_assoc(mysql_query("SELECT activo FROM ".  DB_PREFIX  ."actividad WHERE id='".  (int)$id  ."'"));
            $activo = ($row['activo']==0) ? 1 : 0;
            mysql_query("UPDATE ".  DB_PREFIX  ."actividad SET activo='$activo' WHERE id='".  (int)$id  ."'");
            
            $result = mysql_query("SELECT capitulo FROM ". DB_PREFIX  ."actividad WHERE id='".  (int)$id  ."'");
            $capitulo = mysql_fetch_row($result);
            
            $result = mysql_query("SELECT id FROM ".  DB_PREFIX  ."actividad WHERE capitulo LIKE '".  $capitulo[0]  .".%'");
while($row = mysql_fetch_assoc($result)){

mysql_query("UPDATE ".  DB_PREFIX  ."actividad SET activo='$activo' WHERE id='".  (int)$row['id']  ."'");

}

        }
        
        private static function actualizar_hijos($id, $capitulo){
            
            $result = mysql_query("SELECT capitulo FROM ".  DB_PREFIX  ."actividad WHERE id='".  (int)$id  ."'");
            $row = mysql_fetch_row($result);
            
            $tmp = strlen($row[0]);
            
            if($row[0] != $capitulo){
            
                $result = mysql_query("SELECT id, capitulo FROM ".  DB_PREFIX  ."actividad WHERE capitulo LIKE '".  $row[0]  .".%'");
                
                while($row = mysql_fetch_assoc($result)){
                    
                    
                    $nuevo_capitulo = $capitulo  .  substr($row['capitulo'], $tmp);
                    
                    mysql_query("UPDATE ".  DB_PREFIX  ."actividad SET capitulo='".  $nuevo_capitulo  ."' WHERE id='".  $row['id']  ."'");
                }
            }
        }
        
        public static function crear_tabla(){
            
            $arbol = '<table id="listado" class="table table-condensed">
                        <thead>
                            <tr>
                                <th>item</th>
                                <th>actividad</th>
                                <th>grupo</th>
                                <th>id</th>
                                <th>manual</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="treeTableActividad">';
                                                                            
            $result = mysql_query("SELECT A.nombre, A.capitulo, A.id, A.activo, A.manual, G.nombre AS grupo
                                   FROM ".  DB_PREFIX  ."actividad A
                                   LEFT OUTER JOIN ".  DB_PREFIX  ."grupo G
                                   ON A.grupo = G.id
                                   WHERE A.contrato='".  $_SESSION[SESSION_KEY.'contrato']  ."'                                  
                                   ORDER BY capitulo");
                                   
            //INICIALIZO LAS VARIABLES PARA DETERMINAR HIJOS Y PADRES
            $i = 1;
            $map_capitulo[0] = 0;
            $pass = false;
            
            while($row = mysql_fetch_assoc($result)){
            
            $pass = true;
                
                //CREO UN ARREGLO CON EL CAPITULO
                $tmp_nivel = explode('.', $row['capitulo']);       
                
                //CUENTO EL NIVEL                                
                $nivel = count($tmp_nivel);
                                
                if($nivel > 1){
                    
                    //QUITO EL ULTIMO ELEMENTO DEL CAPITULO PARA DETERMINAR EL PADRE
                    array_pop($tmp_nivel);
                                        
                    $arbol .= "<tr style=\"background: #B2DFF5;\" >\n";
                    
                    //ASIGNO EL PADRE AL INDICE DEL ELEMENTO ACTUAL
                    $map_padre[$i]= implode('.', $tmp_nivel);
                
                }else{
                    
                    $arbol .= "<tr>\n";
                    
                    //ASIGNO EL PADRE 0 AL INDICE DEL ELEMENTO ACTUAL
                    $map_padre[$i] = 0;
                                        
                }
                
                //ASIGNO EL CAPITULO AL INIDICE EL ELEMENTO ACTUAL
                $map_capitulo[$i] = $row['capitulo'];
                    
                $arbol .= '<td style="width: 40px;">'.  $row['capitulo']  .'</td>
                           <td ';
                
                if($nivel == 1){
                    
                    $arbol .= 'style="text-transform: uppercase; font-weight: bold;"';
                    
                }
                
                if (strlen($row['nombre']) > LONGITUD_ACTIVIDAD+30){
                  $row['nombre_abr'] = substr($row['nombre'],0,LONGITUD_ACTIVIDAD+30).'...';
                }else{
                  $row['nombre_abr'] = substr($row['nombre'],0,LONGITUD_ACTIVIDAD+30);
                }
                
                $arbol .=  '><a href="#" class="capitulo">'.  $row['nombre_abr']  .'</a></td>
                           <td style="width: 80px; text-align: center;">'.  $row['grupo']  .'</td>
                           <td style="width: 40px; text-align: center;">'.  $row['id']  .'</td>';
                           
                if($row['manual']){
                    
                    $arbol .= '<td style="text-align: center; width: 10%;"><a href="index.php?com=manual&amp;accion=descargar&amp;id='.  $row['manual']  .'" class="btn btn-sm btn-block btn-default"><i class="fa fa-file"></i> Manual</a></td>';
                
                }else{
                 
                    $arbol .= '<td style="text-align: center; width: 10%;"></td>';
                
                }           
                
                $arbol .= '<td style="text-align: center; width: 10%;">';
                
                if($row['activo'] == 1)
                {
                $arbol .= '<a href="index.php?com=actividad&amp;accion=activo&amp;id='.  $row['id']  .'" class="btn btn-sm btn-block btn-success"><i class="fa fa-check"></i> Activo</a>';
                }
                else
                {
               $arbol .= '<a href="index.php?com=actividad&amp;accion=activo&amp;id='.  $row['id']  .'" class="btn btn-sm btn-block btn-danger"><i class="fa fa-times"></i> Inactivo</a>'; 
                }
                
                
                $arbol .= '</td>
                           <td style="text-align: center; width: 10%;"><a href="index.php?com=actividad&amp;accion=editar&amp;id='.  $row['id']  .'" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
                           <td style="text-align: center; width: 10%;"><a href="index.php?com=actividad&amp;accion=eliminar&amp;id='.  $row['id']  .'" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
                           </tr>';
                           
                $i++;            
                           
            }
            
                        
            $arbol .= '</tbody>
                    </table>';
            
            if($pass){
            
            // POR CADA PADRE BUSCO EL INDICE DE CADA CAPITULO                    
            foreach($map_padre as $key=> $value){
                $map[$key] = array_search($value, $map_capitulo, true);
            }
            
            
                                                
            $array[0] = $arbol;
            $array[1] = implode(',', $map);
                                          
            return $array;
            
        }                                 
            

        }
        
        public static function eliminar($id){
            
            $result = mysql_query("SELECT capitulo FROM ". DB_PREFIX  ."actividad WHERE id='".  (int)$id  ."'");
            $capitulo = mysql_fetch_row($result);
            
            $result = mysql_query("SELECT id FROM ".  DB_PREFIX  ."actividad WHERE capitulo LIKE '".  $capitulo[0]  .".%'");
while($row = mysql_fetch_assoc($result)){

mysql_query("DELETE FROM ".  DB_PREFIX  ."actividad WHERE id='".  (int)$row['id']  ."'");

}

mysql_query("DELETE FROM ".  DB_PREFIX  ."actividad WHERE id='".  (int)$id  ."'"); 
        }
         
        public static function insertar($array){
            
            $keys = array();
$values = array();

foreach($array as $key => $value)
{
array_push($keys, mysql_real_escape_string($key));
array_push($values, (trim($value) == '') ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'");
}

mysql_query("INSERT INTO ".  DB_PREFIX  ."actividad (". implode(',', $keys) .") VALUES (". implode(',', $values) .")");
            return mysql_insert_id();

        }
        
        public static function actualizar($id, $array){
            
            self::actualizar_hijos($id, $array['capitulo']);
                                    
$values = array();

foreach($array as $key => $value)
{
                array_push($values, mysql_real_escape_string($key)  ."=". (trim($value) == '' ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'"));
            }
            
mysql_query("UPDATE ".  DB_PREFIX  ."actividad SET ". implode(',', $values) ." WHERE id='".  (int)$id  ."'");
}
        
        public static function listar(){
            
            $listar = array();
            
$result=mysql_query("SELECT * FROM ".  DB_PREFIX  ."actividad WHERE contrato = '".  $_SESSION[SESSION_KEY.'contrato']  ."' AND padre=0 ORDER BY capitulo");

            while($row=mysql_fetch_assoc($result)){
array_push($listar, $row);
}

            return $listar;

        }      
        
        public static function seleccionar($id){
$row=mysql_fetch_assoc(mysql_query("SELECT * FROM ".  DB_PREFIX  ."actividad WHERE id='".  (int)$id  ."'"));
return $row;
}
               
       
}


?>