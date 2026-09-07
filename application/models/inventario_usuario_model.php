<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Autorizacion de usuarios al modulo Inventario (tabla inv_usuarios, en
| intranet_inventario). NO duplica password/nombre/correo/rut: esos datos
| siguen viviendo unicamente en la tabla `usuario` de la base `intranet`
| (conexion normal $this->db, NUNCA $this->db_inv). Este modelo solo
| guarda y consulta la AUTORIZACION en si (usuario_id, privilegio, activo).
|
| BASE DE DATOS: inv_usuarios vive en intranet_inventario -- usa SIEMPRE
| $this->db_inv. La tabla `usuario` real vive en intranet -- para leerla
| (listar candidatos, resolver nombre/login, verificar que exista) se usa
| $this->db (la conexion normal de este mismo controlador/app, grupo
| activo = intranet), nunca db_inv. Mismo patron que
| Inventario_movimiento_model::_resolver_nombres_usuario().
*/
class Inventario_usuario_model extends CI_Model {

    protected $db_inv;

    public function __construct()
    {
        parent::__construct();
        $this->db_inv = $this->load->database('inventario', TRUE);
    }

    public function set_conexion($db_inv)
    {
        $this->db_inv = $db_inv;
    }

    public function listar()
    {
        $this->db_inv->order_by('creado', 'DESC');
        $query = $this->db_inv->get('inv_usuarios');

        return $this->_resolver_datos_intranet($query->result_array());
    }

    public function seleccionar($id)
    {
        $this->db_inv->where('id', $id);
        $query = $this->db_inv->get('inv_usuarios');

        if ($query->num_rows() > 0)
        {
            $filas = $this->_resolver_datos_intranet(array($query->row_array()));

            return $filas[0];
        }
    }

    /*
    | Evita duplicados: si $usuario_id ya tiene una fila (activa o no),
    | esta devuelve esa fila -- el controlador la usa para rechazar el
    | alta con "ya esta registrado", sin importar si estaba activo o no.
    */
    public function verificar($usuario_id)
    {
        $this->db_inv->where('usuario_id', $usuario_id);
        $query = $this->db_inv->get('inv_usuarios');

        if ($query->num_rows() > 0) return $query->row_array();
    }

    public function insertar($array)
    {
        $array['creado'] = date('Y-m-d H:i:s');
        $array['creado_por'] = $this->session->userdata('id');

        if ($this->db_inv->insert('inv_usuarios', $array)) return $this->db_inv->insert_id();
    }

    public function actualizar($id, $array)
    {
        $array['modificado'] = date('Y-m-d H:i:s');
        $array['modificado_por'] = $this->session->userdata('id');

        $this->db_inv->where('id', $id);
        $this->db_inv->set($array);

        return $this->db_inv->update('inv_usuarios');
    }

    /*
    | Usuarios activos y no eliminados de la intranet que TODAVIA no
    | tienen fila en inv_usuarios -- son los unicos que tiene sentido
    | ofrecer en el <select> de "Nuevo Usuario" (no tiene sentido volver
    | a listar a alguien que ya esta autorizado).
    */
    public function listar_intranet_disponibles()
    {
        $this->db_inv->select('usuario_id');
        $query_actuales = $this->db_inv->get('inv_usuarios');

        $ya_autorizados = array();
        foreach ($query_actuales->result_array() as $fila) $ya_autorizados[] = $fila['usuario_id'];

        $this->db->select('id, usuario, nombre');
        $this->db->where('activo', 1);
        $this->db->where('eliminado', 0);
        if ( ! empty($ya_autorizados)) $this->db->where_not_in('id', $ya_autorizados);
        $this->db->order_by('nombre', 'ASC');
        $query = $this->db->get('usuario');

        return $query->result_array();
    }

    /*
    | Nunca se confia en el id que llega por POST del <select>: antes de
    | autorizar a alguien se confirma que de verdad exista y este activo
    | en la tabla real `usuario` de la intranet.
    */
    public function existe_en_intranet($usuario_id)
    {
        $this->db->where('id', $usuario_id);
        $this->db->where('activo', 1);
        $this->db->where('eliminado', 0);
        $query = $this->db->get('usuario');

        return ($query->num_rows() > 0);
    }

    /*
    | Completa 'usuario_login' (columna usuario, el nombre de inicio de
    | sesion) y 'usuario_nombre' de cada fila consultando la tabla real
    | `usuario` de la intranet por separado, ya que vive en otra base y
    | no se puede hacer un JOIN directo entre las dos conexiones.
    */
    private function _resolver_datos_intranet($filas)
    {
        if (empty($filas)) return $filas;

        $ids = array();
        foreach ($filas as $fila)
        {
            if ( ! in_array($fila['usuario_id'], $ids)) $ids[] = $fila['usuario_id'];
        }

        $datos = array();

        $this->db->select('id, usuario, nombre');
        $this->db->where_in('id', $ids);
        $query = $this->db->get('usuario');

        foreach ($query->result_array() as $u) $datos[$u['id']] = $u;

        foreach ($filas as &$fila)
        {
            $fila['usuario_login'] = isset($datos[$fila['usuario_id']]) ? $datos[$fila['usuario_id']]['usuario'] : NULL;
            $fila['usuario_nombre'] = isset($datos[$fila['usuario_id']]) ? $datos[$fila['usuario_id']]['nombre'] : NULL;
        }
        unset($fila);

        return $filas;
    }
}

/* End of file inventario_usuario_model.php */
