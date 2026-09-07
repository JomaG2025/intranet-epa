<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| QR Helper - Modulo Inventario
| -------------------------------------------------------------------
| Genera el codigo QR de un activo usando la libreria phpqrcode.
|
| ESTA LIBRERIA NO VIENE INCLUIDA EN ESTA ENTREGA porque el entorno
| donde se desarrollo este modulo no tiene acceso a internet para
| descargarla. Debes copiarla manualmente en el servidor:
|
|   application/third_party/phpqrcode/qrlib.php
|   (y los demas archivos .php que trae la libreria)
|
| Descarga: libreria "PHP QR Code" (Dominik Dzienia), pura PHP, sin
| dependencias, compatible con PHP 5.x. Es la misma que se referencio
| en el diagnostico previo (seccion H, "Dependencias que faltan").
|
| Mientras la libreria no este instalada, inv_qr_lib_disponible()
| devuelve FALSE y las vistas de ficha/etiqueta muestran un aviso en
| vez de un QR roto, y Inventario::qr() sirve un PNG transparente de
| 1x1 en vez de fallar.
| -------------------------------------------------------------------
*/

if ( ! function_exists('inv_qr_lib_disponible'))
{
    function inv_qr_lib_disponible()
    {
        return is_file(APPPATH . 'third_party/phpqrcode/qrlib.php');
    }
}

if ( ! function_exists('inv_qr_generar'))
{
    function inv_qr_generar($contenido, $ruta_archivo, $tamano = 6)
    {
        if ( ! inv_qr_lib_disponible()) return FALSE;

        require_once(APPPATH . 'third_party/phpqrcode/qrlib.php');

        $carpeta = dirname($ruta_archivo);
        if ( ! is_dir($carpeta)) @mkdir($carpeta, DIR_WRITE_MODE, TRUE);

        QRcode::png($contenido, $ruta_archivo, QR_ECLEVEL_L, $tamano, 2);

        return is_file($ruta_archivo);
    }
}

/* End of file qr_helper.php */
