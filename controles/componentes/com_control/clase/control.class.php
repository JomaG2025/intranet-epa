<?php

class Control{
                      
        public static function actualizar($id, $array){
        
        $values = array();
            
            foreach($array as $key => $value)
{
                array_push($values, mysql_real_escape_string($key)  ."=". (trim($value) == '' ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'"));
            }
            
mysql_query("UPDATE ".  DB_PREFIX  ."control SET ". implode(',', $values) ." WHERE id='".  (int)$id  ."'");
            
}
        
        public static function cambiar_estado($id, $estado){
            
            mysql_query("UPDATE ".  DB_PREFIX  ."control SET estado='$estado' WHERE id='".  (int)$id  ."'");
            
        }
        
        public static function descripcion($id, $descripcion){
            
            if($descripcion)
            {
                mysql_query("UPDATE ".  DB_PREFIX  ."actividad SET descripcion='".  htmlspecialchars(trim(mysql_real_escape_string($descripcion)))  ."' WHERE id='".  (int)$id  ."'");
            }
            else
            {
                mysql_query("UPDATE ".  DB_PREFIX  ."actividad SET descripcion = NULL WHERE id='".  (int)$id  ."'");
            }

        }
        
        public static function vista_normal($ano){
            
            $arbol = '<table id="listado" class="table table-condensed">
                        <thead>
                            <tr>
                                <th rowspan="2">item</th>
                                <th rowspan="2">actividad</th>
                                <th rowspan="2">responsable</th>
                                <th rowspan="2">grupo</th>
                                <th colspan="12">'.  $ano  .'</th>                         
                            </tr>
                            <tr>
                                <th>ENE</th>
                                <th>FEB</th>
                                <th>MAR</th>
                                <th>ABR</th>
                                <th>MAY</th>
                                <th>JUN</th>
                                <th>JUL</th>
                                <th>AGO</th>
                                <th>SEP</th>
                                <th>OCT</th>
                                <th>NOV</th>
                                <th>DIC</th>
                            </tr>
                        </thead>
                        <tbody id="treeTable">';
                        
            $sql =  "SELECT A.id, A.capitulo, A.nombre, A.padre, G.nombre AS grupo ";
            
            foreach($GLOBALS['array_meses'] as $key => $value){
                $sql .=  ", (SELECT GROUP_CONCAT(U.usuario SEPARATOR ', ') FROM ".  DB_PREFIX  ."usuario U LEFT OUTER JOIN ".  DB_PREFIX  ."privilegio P ON P.usuario = U.id WHERE U.eliminado=0 AND P.actividad=A.id AND P.ano=". $ano ." AND P.dig='1') AS responsable";
                $sql .=  ", IF(A.padre=0, (SELECT id FROM ".  DB_PREFIX  ."control WHERE actividad=A.id AND MONTH(fecha)=".  $key  ." AND YEAR(fecha)=".  $ano  ." LIMIT 0, 1), '') AS mes_".  $key;
                $sql .=  ", IF(A.padre=0, 
                                (SELECT estado FROM ".  DB_PREFIX  ."control WHERE actividad=A.id AND MONTH(fecha)=".  $key  ." AND YEAR(fecha)=".  $ano  ." LIMIT 0, 1), 
                                (SELECT ".  DB_PREFIX  ."control.estado FROM ".  DB_PREFIX  ."control INNER JOIN ".  DB_PREFIX  ."actividad ON ".  DB_PREFIX  ."actividad.id=".  DB_PREFIX  ."control.actividad WHERE ".  DB_PREFIX  ."actividad.capitulo LIKE CONCAT(A.capitulo, '.%' ) AND ".  DB_PREFIX  ."actividad.contrato = '".  $_SESSION[SESSION_KEY.'contrato']  ."' AND MONTH(".  DB_PREFIX  ."control.fecha)=".  $key  ." AND YEAR(".  DB_PREFIX  ."control.fecha)=".  $ano  ." AND (".  DB_PREFIX  ."control.estado='ATR' OR ".  DB_PREFIX  ."control.estado='PEN' OR ".  DB_PREFIX  ."control.estado='REC' ) ORDER BY ".  DB_PREFIX  ."control.estado LIMIT 0, 1)
                            ) AS estado_".  $key;
                $sql .=  ", IF(A.padre=0, (SELECT fecha FROM ".  DB_PREFIX  ."control WHERE actividad=A.id AND MONTH(fecha)=".  $key  ." AND YEAR(fecha)=".  $ano  ." LIMIT 0, 1), '') AS fecha_".  $key;
            }       
                                
            $sql .= " FROM ".  DB_PREFIX  ."actividad A
                     LEFT OUTER JOIN ".  DB_PREFIX  ."grupo G
                     ON A.grupo = G.id
                     WHERE A.activo = 1 AND A.contrato='".  $_SESSION[SESSION_KEY.'contrato']  ."'  
                     ORDER BY A.capitulo";           
                                                                            
            $result = mysql_query($sql);
            
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
                
                $arbol .= '<td style="width: 5%">'.  $row['capitulo']  .'</td>';
                                
                if (strlen($row['nombre']) > LONGITUD_ACTIVIDAD){
                  $row['nombre_abr'] = substr($row['nombre'],0,LONGITUD_ACTIVIDAD).'...';
                }else{
                  $row['nombre_abr'] = $row['nombre'];
                }
                
                $arbol .=  '<td><a href="#" class="capitulo" title="'. $row['nombre'] .'"';
                
                if($nivel == 1){
                    $arbol .= ' style="text-transform: uppercase; font-weight: bold;"';
                }
                
                $arbol .= '>'.  $row['nombre_abr']  .'</a>';
                
                if(($_SESSION[SESSION_KEY.  'tipo'] == 'ADM') AND ($row['padre'] != 1))
                {
                    $arbol .= '&nbsp;<a href="index.php?com=control&amp;accion=agregar&amp;actividad='. $row['id'] .'"><i class="fa fa-plus-square btn-warning"></i></a>';
                }
                
                $arbol .='</td>
                           <td class="text-center">'.  $row['responsable']  .'</td>
                           <td class="text-center">'.  $row['grupo']  .'</td>';
                    
                foreach($GLOBALS['array_meses'] as $key => $value){
                    if($row['estado_'.  $key]){
                        
                        if($row['padre']==0){
                            $arbol .= '<td class="text-center">
                                            <a href="index.php?com=control&amp;accion=detalle&amp;id='.  $row['mes_'.  $key]  .'" >
                                                <img src="template/imagenes/estado_'.  $row['estado_'.  $key].'.png" data-toggle="tooltip" data-html="true" title="'.  strtoupper($GLOBALS['array_estados'][$row['estado_'.  $key]])  .'<br />COMPROMISO: '.  $row['fecha_'.  $key]  .'" alt=""/>
                                            </a>
                                        </td>';
                        }else{
                            
                            $arbol .= '<td class="text-center">
                                            <img src="template/imagenes/estado_'.  $row['estado_'.  $key].'.gif"data-toggle="tooltip" data-html="true" title="CONTROLES '.  strtoupper($GLOBALS['array_estados'][$row['estado_'.  $key]])  .'S" class="alerta" alt=""/>
                                        </td>';
                            
                        }
                                    
                                
                    }else{
                    
                        $arbol .= '<td class="text-center"></td>';
                    
                    }
                }
                
                
                $i++;
                                 
                
                $arbol .= '</tr>';
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
        
        public static function vista_alternativa($ano, $mes){

            $meses_tmp[5] = $mes;
            $anos_tmp[5] = $ano;
                                    
            $i = 4;
            
            while($i>=0){
                
                $meses_tmp[$i] = $meses_tmp[$i+1]-1;
                $anos_tmp[$i] = $anos_tmp[$i+1];                
                
                if($meses_tmp[$i]<1){
                    
                    $meses_tmp[$i] = $meses_tmp[$i]+12;
                    $anos_tmp[$i] = $anos_tmp[$i+1]-1;
                
                }
                
                $i--;
            }
            
            $i = 6;
            
            while($i<=11){
                
                $meses_tmp[$i] = $meses_tmp[$i-1]+1;
                $anos_tmp[$i] = $anos_tmp[$i-1]; 
                
                if($meses_tmp[$i]>12){
                    
                    $meses_tmp[$i] = $meses_tmp[$i]-12;
                    $anos_tmp[$i] = $anos_tmp[$i-1]+1;
                
                }
                
                $i++;
            }
            
            ksort($meses_tmp);
            ksort($anos_tmp);
                        
            $span = count(array_keys($anos_tmp, $anos_tmp[0]));
                        
            $arbol = '<table id="listado" class="table table-condensed">
                        <thead>
                            <tr>
                                <th rowspan="2">item</th>
                                <th rowspan="2">actividad</th>
                                <th rowspan="2">responsable</th>
                                <th rowspan="2">grupo</th>
                                <th colspan="'.  $span  .'">'.  $anos_tmp[0]  .'</th>';
            
            if($span != 12){
                
                $arbol .=       '<th colspan="'.  (12-$span)  .'">'.  $anos_tmp[11]  .'</th>';
                
            }
            
                                
            $arbol .= '     </tr>
                            <tr>';
                                
            foreach($meses_tmp as $key => $value){
                
                $arbol .= '<th ';
                
                if($key == 5){
                    
                    $arbol .= 'style="background-color: #009933; border-bottom: solid 1px #009933;"';
                    
                }
                
                $arbol .= '><a href="index.php?com=control&amp;ano='.  $anos_tmp[$key]  .'&amp;mes='.  $meses_tmp[$key]  .'">'.  $GLOBALS['array_meses'][$value]  .'</a></th>'; 
                
            }                    
                                
                                
            $arbol .=        '</tr>
                        </thead>
                        <tbody id="treeTable">';
                        
            $sql =  "SELECT A.id, A.capitulo, A.nombre, A.padre, G.nombre AS grupo ";
            
            foreach($meses_tmp as $key => $value){
                $sql .=  ", (SELECT GROUP_CONCAT(U.usuario SEPARATOR ', ') FROM ".  DB_PREFIX  ."usuario U LEFT OUTER JOIN ".  DB_PREFIX  ."privilegio P ON P.usuario = U.id WHERE U.eliminado=0 AND P.actividad=A.id AND P.ano =". $ano ." AND P.dig='1'  LIMIT 0, 1) AS responsable";                
                $sql .=  ", IF(A.padre=0, (SELECT id FROM ".  DB_PREFIX  ."control WHERE actividad=A.id AND MONTH(fecha)=".  $value  ." AND YEAR(fecha)=".  $anos_tmp[$key]  ." LIMIT 0, 1), '') AS mes_".  $value;
                $sql .=  ", IF(A.padre=0, 
                                (SELECT estado FROM ".  DB_PREFIX  ."control WHERE actividad=A.id AND MONTH(fecha)=".  $value  ." AND YEAR(fecha)=".  $anos_tmp[$key]  ." LIMIT 0, 1), 
                                (SELECT ".  DB_PREFIX  ."control.estado FROM ".  DB_PREFIX  ."control INNER JOIN ".  DB_PREFIX  ."actividad ON ".  DB_PREFIX  ."actividad.id=".  DB_PREFIX  ."control.actividad WHERE ".  DB_PREFIX  ."actividad.capitulo LIKE CONCAT(A.capitulo, '.%' ) AND ".  DB_PREFIX  ."actividad.contrato = '".  $_SESSION[SESSION_KEY.'contrato']  ."' AND MONTH(".  DB_PREFIX  ."control.fecha)=".  $value  ." AND YEAR(".  DB_PREFIX  ."control.fecha)=".  $anos_tmp[$key]  ." AND (".  DB_PREFIX  ."control.estado='ATR' OR ".  DB_PREFIX  ."control.estado='PEN' OR ".  DB_PREFIX  ."control.estado='REC' ) ORDER BY ".  DB_PREFIX  ."control.estado LIMIT 0, 1)
                            ) AS estado_".  $value;
                $sql .=  ", IF(A.padre=0, (SELECT fecha FROM ".  DB_PREFIX  ."control WHERE actividad=A.id AND MONTH(fecha)=".  $value  ." AND YEAR(fecha)=".  $anos_tmp[$key]  ." LIMIT 0, 1), '') AS fecha_".  $value;
            
            }                
                                
            $sql .= " FROM ".  DB_PREFIX  ."actividad A
                     LEFT OUTER JOIN ".  DB_PREFIX  ."grupo G
                     ON A.grupo = G.id
                     WHERE A.activo = 1 AND A.contrato='".  $_SESSION[SESSION_KEY.'contrato']  ."'  
                     ORDER BY A.capitulo";           
                                                                            
            $result = mysql_query($sql);
            
            //INICIALIZO LAS VARIABLES PARA DETERMINAR HIJOS Y PADRES
            $i = 1;
            $map_capitulo[0] = 0;
            $pass = false;
            
            while($row = mysql_fetch_assoc($result)){
            
            $pass = true;
                
                $tmp_nivel = explode('.', $row['capitulo']);
                
                $nivel = count($tmp_nivel);
                                
                if($nivel > 1){
                    
                    //QUITO EL ULTIMO ELEMENTO DEL CAPITULO PARA DETERMINAR EL PADRE
                    array_pop($tmp_nivel);
                    
                    //ASIGNO EL PADRE AL INDICE DEL ELEMENTO ACTUAL
                    $map_padre[$i]= implode('.', $tmp_nivel);
                    
                    $arbol .= "<tr style=\"background: #B2DFF5;\" >\n";
                
                }else{
                    
                    //ASIGNO EL PADRE 0 AL INDICE DEL ELEMENTO ACTUAL
                    $map_padre[$i] = 0;
                    
                    $arbol .= "<tr>\n";
                                        
                }
                                   
                
                //ASIGNO EL CAPITULO AL INIDICE EL ELEMENTO ACTUAL
                $map_capitulo[$i] = $row['capitulo'];   
                    
                $arbol .= '<td style="width: 5%;">'.  $row['capitulo']  .'</td>
                           <td ';
                
                if($nivel == 1){
                    
                    $arbol .= 'style="text-transform: uppercase; font-weight: bold;"';
                    
                }
                
                if (strlen($row['nombre']) > LONGITUD_ACTIVIDAD){
                  $row['nombre_abr'] = substr($row['nombre'],0,LONGITUD_ACTIVIDAD).'...';
                }else{
                  $row['nombre_abr'] = $row['nombre'];
                }
                
                $arbol .=  '><a href="#" class="capitulo" title="'. $row['nombre'] .'">'.  $row['nombre_abr']  .'</a></td>
                           <td class="text-center">'.  $row['responsable']  .'</td>
                           <td class="text-center">'.  $row['grupo']  .'</td>';
                    
                foreach($meses_tmp as $key => $value){
                    if($row['estado_'.  $value]){
                        
                        if($row['padre']==0){
                            
                            $arbol .= '<td class="text-center"';
                            
                            if($key == 5){
                        
                                $arbol .= ' style="background-color: #D9EAB0;"';
                                
                            }
                            
                            $arbol .=  '>
                                            <a href="index.php?com=control&amp;accion=detalle&amp;id='.  $row['mes_'.  $value]  .'">
                                                <img src="template/imagenes/estado_'.  $row['estado_'.  $value].'.png" alt="'.  $GLOBALS['array_estados'][$row['estado_'.  $value]]  .'" data-toggle="tooltip" data-html="true" title="'.  strtoupper($GLOBALS['array_estados'][$row['estado_'.  $value]])  .'<br />COMPROMISO: '.  $row['fecha_'.  $value]  .'" alt=""/>
                                            </a>
                                        </td>';
                                        
                        }else{
                            
                            
                            $arbol .= '<td class="text-center"';
                            
                            if($key == 5){
                        
                                $arbol .= ' style="background-color: #D9EAB0;"';
                                
                            }
                            
                            $arbol .=  '>
                                            <img src="template/imagenes/estado_'.  $row['estado_'.  $value].'.gif" alt="'.  $GLOBALS['array_estados'][$row['estado_'.  $value]]  .'" data-toggle="tooltip" title="CONTROLES '.  strtoupper($GLOBALS['array_estados'][$row['estado_'.  $value]])  .'S" alt=""/>
                                        </td>';
                            
                        }
                    }else{
                        $arbol .= '<td class="text-center"';
                        
                        if($key == 5){
                    
                            $arbol .= ' style="background-color: #D9EAB0;"';
                            
                        }
                        
                        $arbol .= '></td>';
                    }
                }
                                 
                
                $arbol .= '</tr>';
                
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

        public static function detalle($id){
$row=mysql_fetch_assoc(mysql_query("SELECT *, MONTH(fecha) AS mes, YEAR(fecha) AS ano, fecha FROM ".  DB_PREFIX  ."control WHERE id='".  (int)$id  ."'"));
return $row;
}
        
        public static function eliminar($id){
            
            $result = mysql_query("SELECT archivo FROM ".  DB_PREFIX  ."adjunto WHERE control='".  (int)$id  ."' ");
            $row = mysql_fetch_row($result);
            
            if(is_file(HTTP_UPLOAD  .  $row[0])){     
                unlink(HTTP_UPLOAD  .  $row[0]);
            }
            
            mysql_query("DELETE FROM ".  DB_PREFIX  ."control WHERE id='".  (int)$id  ."'");
                        
        }    
        
        public static function insertar($array){

            if(self::verificar($array['fecha'], $array['actividad'])){
                                        
                $keys = array();
$values = array();

foreach($array as $key => $value)
{
array_push($keys, mysql_real_escape_string($key));
array_push($values, (trim($value) == '') ? 'NULL' : "'".  htmlspecialchars(mysql_real_escape_string($value))  ."'");
}

mysql_query("INSERT INTO ".  DB_PREFIX  ."control (". implode(',', $keys) .") VALUES (". implode(',', $values) .")");
                
            return mysql_insert_id();
                
}
          
            
        }
        
        public static function listar_anos($contrato = NULL){
            
            $listar_anos = array();
            
$result=mysql_query("SELECT YEAR(fecha) AS ano FROM ".  DB_PREFIX  ."control INNER JOIN scc_actividad ON scc_actividad.id = scc_control.actividad WHERE scc_actividad.contrato = '".  $contrato  ."' GROUP BY YEAR(fecha) ORDER BY fecha DESC");

            while($row=mysql_fetch_assoc($result)){
array_push($listar_anos, $row['ano']);
}

            return $listar_anos;

        }
        
        public static function planificacion($actividad , $ano){
            
            $planificacion = array();
            
$result=mysql_query("SELECT MONTH(fecha) AS mes, id FROM ".  DB_PREFIX  ."control WHERE actividad='".  (int)$actividad  ."' AND YEAR(fecha)='".  $ano  ."'");

            while($row=mysql_fetch_assoc($result)){
array_push($planificacion, $row);
}

            return $planificacion;
            
        }
        
        public static function seleccionar($id){
$row=mysql_fetch_assoc(mysql_query("SELECT * FROM ".  DB_PREFIX  ."control WHERE id='".  (int)$id  ."'"));
return $row;
}
        
        public static function verificar($fecha, $actividad){
        
        $fecha  = explode('-', $fecha);
            
            $result = mysql_query("SELECT * FROM ".  DB_PREFIX  ."control WHERE actividad=".  (int)$actividad  ." AND YEAR(fecha)='". $fecha[0]. "' AND MONTH(fecha)='". $fecha[1]. "'");
            
            if($row=mysql_fetch_assoc($result)){
                return false;
            }else{
                return true;
            }
            
        }
        
        public static function copiar($contrato = NULL){
            
            $copiar = array();
            $nuevo = array();
            $ids = array();
            
$result=mysql_query("SELECT * FROM ".  DB_PREFIX  ."control WHERE YEAR(fecha) = ".  (date('Y') -1 )  ." AND actividad IN (SELECT ".  DB_PREFIX  ."actividad.id FROM ".  DB_PREFIX  ."actividad LEFT OUTER JOIN ".  DB_PREFIX  ."contrato ON ".  DB_PREFIX  ."actividad.contrato = ".  DB_PREFIX  ."contrato.id WHERE ".  DB_PREFIX  ."contrato.id = '". $contrato ."' AND ".  DB_PREFIX  ."contrato.activo = 1)");

            while($row = mysql_fetch_assoc($result)){
array_push($copiar, $row);
}

            foreach($copiar as $value){
            
            $control = array();
            $fecha = explode('-', $value['fecha']);
            $fecha[0] = (int)$fecha[0] + 1;
                
                if(($fecha[1] == 2) AND ($fecha[2] >= 28))
                {
                    $fecha[2] = 28;   
                }
                
            $control['fecha'] = implode('-', $fecha);
            $control['actividad'] = $value['actividad'];
            $control['alerta'] = $value['alerta'];
            $control['alertado'] = 0;
            $control['adjuntos'] = $value['adjuntos'];
            
            array_push($nuevo, $control);
            
            }
            
            foreach($nuevo as $value){
            $id = self::insertar($value);
            array_push($ids, $id);
            }
            
            return $ids;

        }
}

?>