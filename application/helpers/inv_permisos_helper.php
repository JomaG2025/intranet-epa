<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Helper de control de acceso propio del modulo Inventario
| -------------------------------------------------------------------
| Consulta la tabla inv_usuarios (base intranet_inventario, conexion
| 'inventario') para saber si el usuario CON SESION INICIADA en la
| intranet tiene ademas autorizacion dentro del modulo Inventario, y con
| que privilegio (ADM/EDI/CON) -- independiente del privilegio general
| de la intranet ($this->session->userdata('privilegio')), que ya NO se
| usa para decidir nada dentro de este modulo. Ver
| sql/004_control_acceso_inventario.sql para el porque de este cambio.
|
| Se implementa como helper (funciones sueltas), no como metodo de
| controlador, para poder llamarse igual desde cualquier controlador del
| modulo Y desde las vistas (ej. application/views/inventario/header.php,
| que arma el menu segun estos mismos permisos). Cada funcion resuelve
| el usuario actual desde la sesion via $CI =& get_instance(), el modismo
| estandar de CodeIgniter 2 para acceder al superobjeto desde un helper.
|
| Funciones publicas:
|   inv_usuario_actual() -> array|NULL   fila de inv_usuarios del usuario en sesion, o NULL
|   inv_tiene_acceso()   -> bool         existe fila Y activo = 1 (cualquier privilegio)
|   inv_es_admin()       -> bool         privilegio == 'ADM' Y activo
|   inv_puede_editar()   -> bool         privilegio IN ('ADM','EDI') Y activo
|   inv_puede_consultar()-> bool         igual que inv_tiene_acceso() (alias con nombre
|                                        mas claro para usarlo en las pantallas de solo lectura)
|
| Todas usan su PROPIA conexion cacheada al grupo 'inventario' (no
| $this->db_inv del controlador que llama): son solo lecturas, nunca
| necesitan participar de una transaccion, y asi funcionan igual sin
| importar desde donde se llamen (controlador, vista, en cualquier orden).
| -------------------------------------------------------------------
*/

if ( ! function_exists('inv_usuario_actual'))
{
    function inv_usuario_actual()
    {
        static $ya_consultado = FALSE;
        static $usuario_cache = NULL;

        if ($ya_consultado) return $usuario_cache;
        $ya_consultado = TRUE;

        $CI =& get_instance();

        $usuario_id = $CI->session->userdata('id');
        if ( ! $usuario_id) return NULL;

        $db_inv = _inv_permisos_conexion();

        $db_inv->where('usuario_id', $usuario_id);
        $query = $db_inv->get('inv_usuarios');

        if ($query->num_rows() > 0) $usuario_cache = $query->row_array();

        return $usuario_cache;
    }
}

if ( ! function_exists('inv_tiene_acceso'))
{
    function inv_tiene_acceso()
    {
        $usuario = inv_usuario_actual();

        return ($usuario AND ($usuario['activo'] == 1));
    }
}

if ( ! function_exists('inv_es_admin'))
{
    function inv_es_admin()
    {
        $usuario = inv_usuario_actual();

        return ($usuario AND ($usuario['activo'] == 1) AND ($usuario['privilegio'] == 'ADM'));
    }
}

if ( ! function_exists('inv_puede_editar'))
{
    function inv_puede_editar()
    {
        $usuario = inv_usuario_actual();

        return ($usuario AND ($usuario['activo'] == 1) AND in_array($usuario['privilegio'], array('ADM', 'EDI'), TRUE));
    }
}

if ( ! function_exists('inv_puede_consultar'))
{
    function inv_puede_consultar()
    {
        // Con los 3 privilegios actuales (ADM/EDI/CON), "puede consultar"
        // es lo mismo que "tiene acceso al modulo" -- se deja como
        // funcion propia (no un simple alias en las llamadas) para que
        // las pantallas de solo lectura digan explicitamente que permiso
        // estan pidiendo, y para poder acotarlo despues sin tocar cada
        // controlador si algun dia existiera un privilegio que no
        // alcance ni para consultar.
        return inv_tiene_acceso();
    }
}

if ( ! function_exists('_inv_permisos_conexion'))
{
    function _inv_permisos_conexion()
    {
        static $db_inv = NULL;

        if ($db_inv === NULL)
        {
            $CI =& get_instance();
            $db_inv = $CI->load->database('inventario', TRUE);
        }

        return $db_inv;
    }
}

/* End of file inv_permisos_helper.php */
