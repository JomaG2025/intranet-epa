<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function formato_numero($numero)
{
	return (ereg_replace('[^0-9]', '', $numero));
}

function formato_monto($numero)
{
	return number_format($numero, 0, ',', '.');
}

function formato_fecha($fecha)
{
    $mes = array('Enero', 'Febrero', 'Marzo', 'Abril','Mayo','Junio','Julio',
           'Agosto', 'Septiembre','Octubre', 'Noviembre', 'Diciembre');
        
	return mdate('%d de '.  $mes[date('m',strtotime($fecha)) - 1]  .' de %Y', strtotime($fecha));
}

function formato_fecha_hora($fecha)
{
    $mes = array('Enero', 'Febrero', 'Marzo', 'Abril','Mayo','Junio','Julio',
           'Agosto', 'Septiembre','Octubre', 'Noviembre', 'Diciembre');
        
	return mdate('%d de '.  $mes[date('m',strtotime($fecha)) - 1]  .' de %Y a las %H:%i hrs.', strtotime($fecha));
}
	
function formato_fecha_atras($timestamp) 
{
	if ( ! is_int($timestamp)) $timestamp = strtotime($timestamp, 0);
			
	$diff = time() - $timestamp;
	if ($diff <= 0) return 'Ahora';
	else if ($diff < 60) return "Hace ".ConSoSinS(floor($diff), ' segundo(s) atrás');
	else if ($diff < 60*60) return "Hace ".ConSoSinS(floor($diff/60), ' minuto(s) atrás');
	else if ($diff < 60*60*24) return "Hace ".ConSoSinS(floor($diff/(60*60)), ' hora(s) atrás');
	else if ($diff < 60*60*24*30) return "Hace ".ConSoSinS(floor($diff/(60*60*24)), ' d&iacute;a(s) atrás');
	else if ($diff < 60*60*24*30*12) return "Hace ".ConSoSinS(floor($diff/(60*60*24*30)), ' mes(es) atrás');
	else return "Hace ".ConSoSinS(floor($diff/(60*60*24*30*12)), ' a&ntilde;o(s) atrás');
}
		 
function ConSoSinS($val, $sentence) 
{
	if ($val > 1) return $val.str_replace(array('(s)','(es)'),array('s','es'), $sentence); 
	else return $val.str_replace('(s)', '', $sentence);
}
	
function formato_usuario($cadena)
{
	return (ereg_replace('/[^A-Za-z0-9_-Ññ]/', '', $cadena));
}
    
function formato_documento($cadena)
{
	$cadena = str_replace(' ', '_', $cadena);
	return (preg_replace('/[^A-Za-z0-9_.-]/', '', $cadena));
}

function dias_diferencia($fecha_i, $fecha_f)
{
    $dias = (strtotime($fecha_i) - strtotime($fecha_f))/86400;
    $dias = abs($dias); 
    $dias = floor($dias);		
    return $dias;
}

if ( ! function_exists('array_column'))
{
    function array_column(array $input, $columnKey, $indexKey = null)
    {
        $array = array();
        foreach ($input as $value)
        {
            if( ! array_key_exists($columnKey, $value))
            {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return FALSE;
            }
            if(is_null($indexKey))
            {
                $array[] = $value[$columnKey];
            }
            else
            {
                if( ! array_key_exists($indexKey, $value)) 
                {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return FALSE;
                }
                if( ! is_scalar($value[$indexKey]))
                {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return FALSE;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

if ( ! function_exists('in_array_column'))
{
    function in_array_column($text, $column, $array)
    {
        if ( ! empty($array) AND is_array($array))
        {
            foreach($array as $value)
            {
                if ($value[$column] == $text || strcmp($value[$column], $text) == 0) return TRUE;
            }
        }
        return FALSE;
    }   
}
?>