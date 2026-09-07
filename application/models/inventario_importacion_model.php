<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Historial de importaciones desde Excel: un registro por cada archivo
| CONFIRMADO desde Inventario > Importar Excel (nunca se guarda nada
| aqui durante la vista previa, solo al confirmar). Ver
| application/libraries/Inv_importador.php.
|
| BASE DE DATOS (revision 5): inv_importaciones vive en
| `intranet_inventario`, usa $this->db_inv. La columna usuario_id
| guarda el id del usuario de `intranet` como INT simple (sin FK entre
| bases, igual que en inv_activos/inv_movimientos); listar() resuelve
| el nombre con una segunda consulta a $this->db, igual que hace
| Inventario_movimiento_model::_resolver_nombres_usuario().
*/
class Inventario_importacion_model extends CI_Model {

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
        $array['fecha'] = date('Y-m-d H:i:s');

        if ($this->db_inv->insert('inv_importaciones', $array)) return $this->db_inv->insert_id();
    }

    public function listar()
    {
        $this->db_inv->order_by('fecha', 'DESC');
        $query = $this->db_inv->get('inv_importaciones');

        return $this->_resolver_nombres_usuario($query->result_array());
    }

    public function seleccionar($id)
    {
        $this->db_inv->where('id', $id);
        $query = $this->db_inv->get('inv_importaciones');

        if ($query->num_rows() > 0)
        {
            $filas = $this->_resolver_nombres_usuario(array($query->row_array()));

            return $filas[0];
        }
    }

    /*
    | Misma logica que Inventario_movimiento_model::_resolver_nombres_usuario():
    | usuario_id apunta a la tabla `usuario` de la base `intranet`
    | (conexion $this->db, no $this->db_inv), asi que no se puede hacer
    | un JOIN directo -- se resuelve con una segunda consulta.
    */
    private function _resolver_nombres_usuario($importaciones)
    {
        if (empty($importaciones)) return $importaciones;

        $ids = array();
        foreach ($importaciones as $imp)
        {
            if ($imp['usuario_id'] AND ( ! in_array($imp['usuario_id'], $ids)))
            {
                $ids[] = $imp['usuario_id'];
            }
        }

        $nombres = array();

        if ( ! empty($ids))
        {
            $this->db->select('id, nombre');
            $this->db->where_in('id', $ids);
            $query = $this->db->get('usuario');

            foreach ($query->result_array() as $usuario)
            {
                $nombres[$usuario['id']] = $usuario['nombre'];
            }
        }

        foreach ($importaciones as &$imp)
        {
            $imp['usuario_nombre'] = ($imp['usuario_id'] AND isset($nombres[$imp['usuario_id']])) ? $nombres[$imp['usuario_id']] : NULL;
        }
        unset($imp);

        return $importaciones;
    }
}

/* End of file inventario_importacion_model.php */
