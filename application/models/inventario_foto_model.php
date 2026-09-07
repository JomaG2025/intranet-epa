<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Fotografias asociadas a un activo. Soporta varias fotos por activo,
| con una marcada como "principal" (la que se usa en la ficha y en el
| listado).
|
| Las fotos NUNCA se borran fisicamente de la base de datos: eliminar()
| solo aplica baja logica (columnas eliminado/eliminado_por/
| eliminado_fecha), igual que se hace con inv_activos. El archivo
| fisico se conserva por ahora como evidencia (ver eliminar_foto() en
| el controlador). listar()/principal()/seleccionar() siempre excluyen
| las fotos eliminadas.
|
| BASE DE DATOS (revision 4): inv_fotografias vive en
| `intranet_inventario` -- usa SIEMPRE $this->db_inv, nunca $this->db.
| usuario_id / eliminado_por guardan el id del usuario de la intranet
| como INT simple (sin FK, la tabla `usuario` vive en la otra base). Ver
| docs/BASE_DATOS_INVENTARIO.md.
*/
class Inventario_foto_model extends CI_Model {

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

    public function listar($activo_id)
    {
        $this->db_inv->where('activo_id', $activo_id);
        $this->db_inv->where('eliminado', 0);
        $this->db_inv->order_by('principal', 'DESC');
        $this->db_inv->order_by('creado', 'DESC');
        $query = $this->db_inv->get('inv_fotografias');

        return $query->result_array();
    }

    public function principal($activo_id)
    {
        $this->db_inv->where('activo_id', $activo_id);
        $this->db_inv->where('principal', 1);
        $this->db_inv->where('eliminado', 0);
        $query = $this->db_inv->get('inv_fotografias');

        if ($query->num_rows() > 0) return $query->row_array();
    }

    public function seleccionar($id)
    {
        $this->db_inv->where('id', $id);
        $this->db_inv->where('eliminado', 0);
        $query = $this->db_inv->get('inv_fotografias');

        if ($query->num_rows() > 0) return $query->row_array();
    }

    public function insertar($array)
    {
        $array['creado'] = date('Y-m-d H:i:s');

        if ($this->db_inv->insert('inv_fotografias', $array)) return $this->db_inv->insert_id();
    }

    public function marcar_principal($activo_id, $foto_id)
    {
        // Primero se confirma que la foto exista, no este eliminada y
        // pertenezca realmente al activo indicado. Si no se cumple
        // cualquiera de las 3 condiciones, no se toca nada y se
        // devuelve FALSE (nunca se marca como principal una foto
        // eliminada o de otro activo).
        $this->db_inv->where('id', $foto_id);
        $this->db_inv->where('activo_id', $activo_id);
        $this->db_inv->where('eliminado', 0);
        $query = $this->db_inv->get('inv_fotografias');

        if ($query->num_rows() == 0) return FALSE;

        // Desmarca la principal anterior, solo entre las fotos vigentes
        // de ESE activo (nunca toca fotos ya eliminadas ni de otro activo).
        $this->db_inv->where('activo_id', $activo_id);
        $this->db_inv->where('eliminado', 0);
        $this->db_inv->set('principal', 0);
        $this->db_inv->update('inv_fotografias');

        // Marca la nueva principal, repitiendo las mismas 3 condiciones
        // (id + activo_id + eliminado=0) por seguridad.
        $this->db_inv->where('id', $foto_id);
        $this->db_inv->where('activo_id', $activo_id);
        $this->db_inv->where('eliminado', 0);
        $this->db_inv->set('principal', 1);
        $ok = $this->db_inv->update('inv_fotografias');

        return (bool) $ok;
    }

    public function eliminar($id, $usuario_id)
    {
        $this->db_inv->where('id', $id);
        $this->db_inv->set(array(
            'eliminado'       => 1,
            'eliminado_por'   => $usuario_id,
            'eliminado_fecha' => date('Y-m-d H:i:s'),
            'principal'       => 0,
        ));

        return $this->db_inv->update('inv_fotografias');
    }
}

/* End of file inventario_foto_model.php */
