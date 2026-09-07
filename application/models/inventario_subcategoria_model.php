<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Modelo dedicado para inv_subcategorias porque, a diferencia de los
| demas catalogos, siempre necesita mostrarse junto al nombre de su
| categoria (join) y filtrarse por categoria_id para los combos
| dependientes del formulario de activos.
|
| BASE DE DATOS (revision 4): inv_subcategorias e inv_categorias viven
| ambas en `intranet_inventario`, asi que el join entre ellas sigue
| siendo un join normal dentro de la MISMA conexion/base de datos --
| solo cambia que ahora se hace con $this->db_inv en vez de $this->db.
*/
class Inventario_subcategoria_model extends CI_Model {

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

    public function listar($solo_activos = FALSE)
    {
        $this->db_inv->select('inv_subcategorias.*, inv_categorias.nombre AS categoria_nombre');
        $this->db_inv->join('inv_categorias', 'inv_categorias.id = inv_subcategorias.categoria_id');
        if ($solo_activos) $this->db_inv->where('inv_subcategorias.activo', 1);
        $this->db_inv->order_by('inv_categorias.nombre, inv_subcategorias.nombre', 'ASC');
        $query = $this->db_inv->get('inv_subcategorias');

        return $query->result_array();
    }

    public function listar_por_categoria($categoria_id, $solo_activos = TRUE)
    {
        $this->db_inv->where('categoria_id', $categoria_id);
        if ($solo_activos) $this->db_inv->where('activo', 1);
        $this->db_inv->order_by('nombre', 'ASC');
        $query = $this->db_inv->get('inv_subcategorias');

        return $query->result_array();
    }

    public function seleccionar($id)
    {
        $this->db_inv->where('id', $id);
        $query = $this->db_inv->get('inv_subcategorias');

        if ($query->num_rows() > 0) return $query->row_array();
    }

    public function verificar_nombre($categoria_id, $nombre, $excluir_id = NULL)
    {
        $this->db_inv->where('categoria_id', $categoria_id);
        $this->db_inv->where('nombre', $nombre);
        if ($excluir_id) $this->db_inv->where('id !=', $excluir_id);
        $query = $this->db_inv->get('inv_subcategorias');

        if ($query->num_rows() > 0) return $query->row_array();
    }

    public function insertar($array)
    {
        $array['creado'] = date('Y-m-d H:i:s');

        if ($this->db_inv->insert('inv_subcategorias', $array)) return $this->db_inv->insert_id();
    }

    public function actualizar($id, $array)
    {
        $this->db_inv->where('id', $id);
        $this->db_inv->set($array);

        return $this->db_inv->update('inv_subcategorias');
    }

    public function activo($id)
    {
        if ($fila = $this->seleccionar($id))
        {
            $nuevo = ($fila['activo'] == 1) ? 0 : 1;

            $this->db_inv->where('id', $id);
            $this->db_inv->set('activo', $nuevo);
            $this->db_inv->update('inv_subcategorias');

            return TRUE;
        }
    }
}

/* End of file inventario_subcategoria_model.php */
