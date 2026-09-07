<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Helper de utilidades - Modulo Inventario
| -------------------------------------------------------------------
*/

if ( ! function_exists('inv_e'))
{
    // Alias corto de htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') para no
    // repetir la llamada completa en cada vista. Sigue siendo exactamente
    // esa misma funcion nativa de PHP, solo mas breve de escribir. Usar
    // SIEMPRE para imprimir texto libre proveniente de la base de datos
    // (activos, catalogos, movimientos, motivos, observaciones, busquedas).
    function inv_e($valor)
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

if ( ! function_exists('inv_formato_moneda'))
{
    function inv_formato_moneda($valor)
    {
        if ($valor === NULL OR $valor === '') return '-';
        return '$ ' . number_format($valor, 0, ',', '.');
    }
}

if ( ! function_exists('inv_formato_fecha_corta'))
{
    function inv_formato_fecha_corta($fecha)
    {
        if (empty($fecha) OR $fecha == '0000-00-00') return '-';
        return date('d-m-Y', strtotime($fecha));
    }
}

if ( ! function_exists('inv_badge_estado'))
{
    function inv_badge_estado($activo)
    {
        if ($activo['dado_baja'] == 1)
        {
            return '<span class="label label-danger">Dado de baja</span>';
        }

        $nombre = ! empty($activo['estado_nombre']) ? $activo['estado_nombre'] : 'Sin estado';
        return '<span class="label label-success">' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '</span>';
    }
}

if ( ! function_exists('inv_tipo_movimiento_texto'))
{
    function inv_tipo_movimiento_texto($tipo)
    {
        $textos = array(
            'ALTA'         => 'Alta de activo',
            'UBICACION'    => 'Cambio de ubicacion',
            'RESPONSABLE'  => 'Cambio de responsable',
            'ESTADO'       => 'Cambio de estado',
            'BAJA'         => 'Baja de activo',
            'REACTIVACION' => 'Reactivacion de activo',
            'EDICION'      => 'Edicion de datos generales',
        );

        return isset($textos[$tipo]) ? $textos[$tipo] : inv_e($tipo);
    }
}

if ( ! function_exists('inv_badge_resultado_importacion'))
{
    // Usado en la vista previa de Inventario > Importar Excel (ver
    // application/libraries/Inv_importador.php), una fila por cada
    // resultado posible de analizar()/importar().
    function inv_badge_resultado_importacion($resultado)
    {
        $mapa = array(
            'LISTO_PARA_IMPORTAR' => array('success', 'Listo para importar'),
            'ADVERTENCIA'         => array('warning', 'Advertencia'),
            'YA_EXISTE'           => array('default', 'Ya existe'),
            'ERROR'               => array('danger', 'Error'),
        );

        $info = isset($mapa[$resultado]) ? $mapa[$resultado] : array('default', $resultado);

        return '<span class="label label-' . $info[0] . '">' . htmlspecialchars($info[1], ENT_QUOTES, 'UTF-8') . '</span>';
    }
}

if ( ! function_exists('inv_logo_disponible'))
{
    // Usado por ficha.php y etiqueta.php (paginas standalone, sin
    // header.php) para saber si el logo real ya esta copiado en el
    // servidor antes de intentar mostrarlo. FCPATH (constante nativa de
    // CodeIgniter, definida en index.php) apunta a la raiz web real
    // (/var/www/html/ en el servidor), el mismo lugar de donde
    // base_url() arma la URL publica -- por eso esta comprobacion y la
    // URL que se genera en la vista SIEMPRE se refieren al mismo
    // archivo. Si el logo todavia no fue copiado ahi, la vista debe
    // mostrar el texto "Puerto Arica" en vez del <img>, nunca un error.
    function inv_logo_disponible()
    {
        return is_file(FCPATH . 'imagenes/logoepa.png');
    }
}

/* End of file inventario_helper.php */
