<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "inicio";
$route['404_override'] = '';
$route['api/usuario/(:any)'] = "api/usuario/index";
$route['ciberconsejos'] = 'ciberconsejos/index';
$route['suseso'] = 'suseso/index';


/*
|--------------------------------------------------------------------------
| Modulo Inventario de Activos
|--------------------------------------------------------------------------
*/

$route['inventario/importar'] = 'inventario_importacion/subir';
$route['inventario/importar/subir'] = 'inventario_importacion/procesar_subida';
$route['inventario/importar/vista-previa'] = 'inventario_importacion/vista_previa';
$route['inventario/importar/confirmar'] = 'inventario_importacion/confirmar';
$route['inventario/importar/cancelar'] = 'inventario_importacion/cancelar';
$route['inventario/importaciones'] = 'inventario_importacion/historial';

$route['inventario/admin/usuarios'] = 'admin/inventario_usuarios/index';
$route['inventario/admin/usuarios/nuevo'] = 'admin/inventario_usuarios/nuevo';
$route['inventario/admin/usuarios/grabar'] = 'admin/inventario_usuarios/grabar';
$route['inventario/admin/usuarios/editar/(:num)'] = 'admin/inventario_usuarios/editar/$1';
$route['inventario/admin/usuarios/actualizar'] = 'admin/inventario_usuarios/actualizar';
$route['inventario/admin/usuarios/activo'] = 'admin/inventario_usuarios/activo';





/* End of file routes.php */
/* Location: ./application/config/routes.php */