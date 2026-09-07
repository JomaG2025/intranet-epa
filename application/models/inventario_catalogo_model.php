<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Modelo generico para los catalogos simples del modulo Inventario:
| inv_gerencias, inv_areas, inv_ubicaciones, inv_categorias, inv_tipos,
| inv_estados e inv_responsables (todos comparten el mismo esqueleto
| id/nombre/activo/creado; inv_responsables ademas guarda "cargo", que
| se maneja igual porque solo viaja dentro del array $registro).
|
| inv_subcategorias NO usa este modelo porque necesita el join con
| inv_categorias para mostrarse (ver inventario_subcategoria_model.php).
|
| Uso:
|   $this->load->model('inventario_catalogo_model', 'catalogo');
|   $this->catalogo->set_tabla('inv_areas');
|   $this->catalogo->listar();
|
| BASE DE DATOS (revision 4): todas las tablas de este modelo viven en
| `intranet_inventario`. Usa SIEMPRE $this->db_inv (grupo de conexion
| 'inventario'), nunca $this->db. Ver docs/BASE_DATOS_INVENTARIO.md.
*/
class Inventario_catalogo_model extends CI_Model {

    protected $tabla = NULL;

    /*
    | Ver la nota equivalente en inventario_model.php: el constructor
    | abre su propia conexion por defecto; el controlador la reemplaza
    | por una compartida con set_conexion() para que las transacciones
    | cubran de verdad todas las consultas de una misma operacion.
    */
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

    public function set_tabla($tabla)
    {
        $this->tabla = $tabla;
    }

    public function listar($solo_activos = FALSE)
    {
        if ($solo_activos) $this->db_inv->where('activo', 1);
        $this->db_inv->order_by('nombre', 'ASC');
        $query = $this->db_inv->get($this->tabla);

        return $query->result_array();
    }

    public function seleccionar($id)
    {
        $this->db_inv->where('id', $id);
        $query = $this->db_inv->get($this->tabla);

        if ($query->num_rows() > 0) return $query->row_array();
    }

    /*
    | $campos_extra (opcional): columnas adicionales para verificar
    | unicidad COMPUESTA en vez de solo por nombre -- ej. array('subcategoria_id'
    | => 6) para replicar la UNIQUE KEY (subcategoria_id, nombre) real de
    | inv_tipos. Si no se pasa nada (uso por defecto, igual que siempre),
    | la verificacion sigue siendo global por nombre, que es lo correcto
    | para los catalogos que NO tienen relacion jerarquica (ubicaciones,
    | responsables, estados, gerencias) y tambien para inv_areas, que a
    | proposito sigue siendo unica de forma global por nombre (ver
    | sql/003_corregir_indices_catalogos.sql, seccion 2). La pantalla de
    | administracion de catalogos (admin/inventario_catalogos.php) no
    | pasa $campos_extra hoy porque administra Tipos/Areas como catalogos
    | planos (sin selector de subcategoria/gerencia); queda disponible
    | para si en el futuro se agrega ese selector.
    */
    public function verificar_nombre($nombre, $excluir_id = NULL, $campos_extra = array())
    {
        $this->db_inv->where('nombre', $nombre);
        foreach ($campos_extra as $columna => $valor) $this->db_inv->where($columna, $valor);
        if ($excluir_id) $this->db_inv->where('id !=', $excluir_id);
        $query = $this->db_inv->get($this->tabla);

        if ($query->num_rows() > 0) return $query->row_array();
    }

    public function insertar($array)
    {
        $array['creado'] = date('Y-m-d H:i:s');

        if ($this->db_inv->insert($this->tabla, $array)) return $this->db_inv->insert_id();
    }

    public function actualizar($id, $array)
    {
        $this->db_inv->where('id', $id);
        $this->db_inv->set($array);

        return $this->db_inv->update($this->tabla);
    }

    public function activo($id)
    {
        if ($fila = $this->seleccionar($id))
        {
            $nuevo = ($fila['activo'] == 1) ? 0 : 1;

            $this->db_inv->where('id', $id);
            $this->db_inv->set('activo', $nuevo);
            $this->db_inv->update($this->tabla);

            return TRUE;
        }
    }

    public function en_uso($id, $columna_fk)
    {
        $this->db_inv->where($columna_fk, $id);
        $this->db_inv->where('eliminado', 0);
        $query = $this->db_inv->get('inv_activos');

        return ($query->num_rows() > 0);
    }
}

/* End of file inventario_catalogo_model.php */
