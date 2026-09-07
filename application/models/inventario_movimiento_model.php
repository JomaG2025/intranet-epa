<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Historial de movimientos de un activo: alta, cambio de ubicacion,
| cambio de responsable, cambio de estado, edicion general, baja y
| reactivacion.
|
| Es intencionalmente independiente de la tabla `auditoria` ya
| existente: `auditoria` registra QUE controlador/accion generica se
| ejecuto (para todo el sistema, en la base `intranet`), mientras que
| inv_movimientos guarda el detalle especifico del negocio (valor
| anterior/nuevo, motivo, observacion) para reconstruir la trazabilidad
| de UN activo (en la base `intranet_inventario`). Ambas se siguen
| usando en paralelo desde el controlador. Ver docs/BASE_DATOS_INVENTARIO.md.
|
| BASE DE DATOS (revision 4): inv_movimientos vive en
| `intranet_inventario` -- usa $this->db_inv. La columna usuario_id
| sigue guardando el id del usuario de la intranet
| ($this->session->userdata('id')), pero la tabla `usuario` en si vive
| en la OTRA base (`intranet`), asi que ya NO se puede resolver el
| nombre con un JOIN (no existe JOIN entre dos conexiones/bases de
| datos distintas). listar() ahora hace una segunda consulta, con
| $this->db (la conexion normal, grupo activo = intranet), para
| traducir los usuario_id a nombre.
*/
class Inventario_movimiento_model extends CI_Model {

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

    public function insertar($array)
    {
        $array['usuario_id'] = $this->session->userdata('id');
        $array['fecha'] = date('Y-m-d H:i:s');

        if ($this->db_inv->insert('inv_movimientos', $array)) return $this->db_inv->insert_id();
    }

    public function listar($activo_id)
    {
        $this->db_inv->where('activo_id', $activo_id);
        $this->db_inv->order_by('fecha', 'DESC');
        $query = $this->db_inv->get('inv_movimientos');

        return $this->_resolver_nombres_usuario($query->result_array());
    }

    /*
    | Completa 'usuario_nombre' en cada movimiento consultando la tabla
    | `usuario` de la base principal (intranet) por separado, ya que
    | vive en una conexion/base de datos distinta a inv_movimientos y
    | no se puede hacer un JOIN directo entre ambas.
    */
    private function _resolver_nombres_usuario($movimientos)
    {
        if (empty($movimientos)) return $movimientos;

        $ids = array();
        foreach ($movimientos as $mov)
        {
            if ($mov['usuario_id'] AND ( ! in_array($mov['usuario_id'], $ids)))
            {
                $ids[] = $mov['usuario_id'];
            }
        }

        $nombres = array();

        if ( ! empty($ids))
        {
            // $this->db aqui es la conexion normal (grupo activo por
            // defecto = 'int', base `intranet`), la misma que usan
            // login/usuario_model/auditoria_model. NO es $this->db_inv.
            $this->db->select('id, nombre');
            $this->db->where_in('id', $ids);
            $query = $this->db->get('usuario');

            foreach ($query->result_array() as $usuario)
            {
                $nombres[$usuario['id']] = $usuario['nombre'];
            }
        }

        foreach ($movimientos as &$mov)
        {
            $mov['usuario_nombre'] = ($mov['usuario_id'] AND isset($nombres[$mov['usuario_id']])) ? $nombres[$mov['usuario_id']] : NULL;
        }
        unset($mov);

        return $movimientos;
    }
}

/* End of file inventario_movimiento_model.php */
