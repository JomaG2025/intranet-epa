<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Idioma - Modulo Inventario
| -------------------------------------------------------------------
| Este archivo NO reemplaza application/language/spanish/alertas_lang.php,
| lo complementa. Se carga aparte con $this->lang->load('inventario',
| 'spanish') dentro de los controladores del modulo, por lo que no hace
| falta modificar application/config/autoload.php.
|
| El helper redirect() y la vista alertas.php ya existentes siguen
| funcionando igual: buscan la clave con $this->lang->line(...), y CI
| busca en TODOS los archivos de idioma cargados en la peticion actual.
| -------------------------------------------------------------------
*/

$lang['inv_success_01'] = 'Activo creado correctamente.';
$lang['inv_success_02'] = 'Registro actualizado.';
$lang['inv_success_03'] = 'Activo dado de baja.';
$lang['inv_success_04'] = 'Activo reactivado.';
$lang['inv_success_05'] = 'Movimiento registrado.';
$lang['inv_success_06'] = 'Fotografia subida correctamente.';
$lang['inv_success_07'] = 'Importacion confirmada correctamente.';
$lang['inv_success_08'] = 'Accion masiva aplicada correctamente.';

$lang['inv_danger_01'] = 'Datos incorrectos o incompletos.';
$lang['inv_danger_02'] = 'Ya existe un registro con ese codigo o nombre.';
$lang['inv_danger_03'] = 'El activo no existe.';
$lang['inv_danger_04'] = 'La imagen no cumple con el formato, tamano o dimensiones permitidas (JPG, JPEG o PNG, maximo 4 MB).';
$lang['inv_danger_05'] = 'La sesion de este formulario expiro o no es valida. Vuelve a intentarlo.';
$lang['inv_danger_06'] = 'El archivo no cumple con el formato permitido (.xlsx, maximo 10 MB) o no se pudo subir.';
$lang['inv_danger_07'] = 'No hay ningun archivo en proceso. Vuelve a subirlo.';

$lang['inv_warning_01'] = 'Ningun dato fue modificado.';
$lang['inv_warning_02'] = 'El activo ya se encuentra dado de baja.';

/* End of file inventario_lang.php */
