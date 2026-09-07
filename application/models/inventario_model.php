<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Modelo principal del modulo: tabla inv_activos.
|
| Sigue la misma convencion de metodos que usuario_model.php:
| listar/seleccionar/insertar/actualizar/eliminar/verificar/contar.
|
| Los activos NUNCA se borran fisicamente. `eliminar()` solo oculta el
| registro (columna `eliminado`, igual que usuario_model->eliminar()).
| La baja formal de un bien (columna `dado_baja`) es un concepto de
| negocio distinto y se maneja con dar_baja()/reactivar().
|
| BASE DE DATOS (revision 4): este modelo vive en `intranet_inventario`,
| una base fisica separada de `intranet`. Usa SIEMPRE $this->db_inv
| (grupo de conexion 'inventario', ver application/config/database.php),
| NUNCA $this->db (que sigue apuntando a `intranet`, la base principal
| que usan login/sesion/auditoria/usuarios). Detalle completo en
| docs/BASE_DATOS_INVENTARIO.md.
*/
class Inventario_model extends CI_Model {

    /*
    | El constructor abre su propia conexion al grupo 'inventario' para
    | que el modelo funcione de forma autonoma si se usa fuera del
    | controlador. En la practica, Inventario::__construct() reemplaza
    | esta conexion por una compartida justo despues de cargar el
    | modelo (ver set_conexion()), para que $this->db_inv->trans_start()/
    | trans_complete() en el controlador cubran realmente las consultas
    | que hace este modelo (todas deben compartir la MISMA conexion
    | fisica para que una transaccion tenga efecto sobre ellas).
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

    protected function _joins()
    {
        $this->db_inv->select('
            inv_activos.*,
            inv_gerencias.nombre AS gerencia_nombre,
            inv_areas.nombre AS area_nombre,
            inv_categorias.nombre AS categoria_nombre,
            inv_subcategorias.nombre AS subcategoria_nombre,
            inv_tipos.nombre AS tipo_nombre,
            inv_ubicaciones.nombre AS ubicacion_nombre,
            inv_responsables.nombre AS responsable_nombre,
            inv_estados.nombre AS estado_nombre
        ');
        $this->db_inv->join('inv_gerencias', 'inv_gerencias.id = inv_activos.gerencia_id', 'left');
        $this->db_inv->join('inv_areas', 'inv_areas.id = inv_activos.area_id', 'left');
        $this->db_inv->join('inv_categorias', 'inv_categorias.id = inv_activos.categoria_id', 'left');
        $this->db_inv->join('inv_subcategorias', 'inv_subcategorias.id = inv_activos.subcategoria_id', 'left');
        $this->db_inv->join('inv_tipos', 'inv_tipos.id = inv_activos.tipo_id', 'left');
        $this->db_inv->join('inv_ubicaciones', 'inv_ubicaciones.id = inv_activos.ubicacion_id', 'left');
        $this->db_inv->join('inv_responsables', 'inv_responsables.id = inv_activos.responsable_id', 'left');
        $this->db_inv->join('inv_estados', 'inv_estados.id = inv_activos.estado_id', 'left');
    }

    protected function _filtros($filtros)
    {
        $this->db_inv->where('inv_activos.eliminado', 0);

        if ( ! empty($filtros['buscar']))
        {
            // CodeIgniter 2 no tiene group_start()/group_end() (se agregaron
            // recien en CI3). Para agrupar un OR de varias columnas sin
            // concatenar el texto del usuario sin escapar, se arma la
            // condicion completa a mano usando $this->db_inv->escape_like_str()
            // (escapa los comodines % y _ de LIKE) + $this->db_inv->escape()
            // (agrega comillas y escapa comillas/backslashes), y se pasa
            // como condicion literal con $escape = FALSE para que Active
            // Record no intente tratarla como nombre de columna.
            $buscar = '%' . $this->db_inv->escape_like_str($filtros['buscar']) . '%';
            $buscar = $this->db_inv->escape($buscar);

            $condicion = "(inv_activos.codigo_unico LIKE {$buscar} ESCAPE '!'"
                . " OR inv_activos.descripcion LIKE {$buscar} ESCAPE '!'"
                . " OR inv_activos.numero_serie LIKE {$buscar} ESCAPE '!'"
                . " OR inv_activos.marca LIKE {$buscar} ESCAPE '!'"
                . " OR inv_activos.modelo LIKE {$buscar} ESCAPE '!')";

            $this->db_inv->where($condicion, NULL, FALSE);
        }

        if ( ! empty($filtros['area_id']))         $this->db_inv->where('inv_activos.area_id', $filtros['area_id']);
        if ( ! empty($filtros['gerencia_id']))      $this->db_inv->where('inv_activos.gerencia_id', $filtros['gerencia_id']);
        if ( ! empty($filtros['categoria_id']))     $this->db_inv->where('inv_activos.categoria_id', $filtros['categoria_id']);
        if ( ! empty($filtros['subcategoria_id']))  $this->db_inv->where('inv_activos.subcategoria_id', $filtros['subcategoria_id']);
        if ( ! empty($filtros['tipo_id']))          $this->db_inv->where('inv_activos.tipo_id', $filtros['tipo_id']);
        if ( ! empty($filtros['ubicacion_id']))     $this->db_inv->where('inv_activos.ubicacion_id', $filtros['ubicacion_id']);
        if ( ! empty($filtros['estado_id']))        $this->db_inv->where('inv_activos.estado_id', $filtros['estado_id']);

        if ( ! empty($filtros['dado_baja']) AND ($filtros['dado_baja'] === '1'))
        {
            $this->db_inv->where('inv_activos.dado_baja', 1);
        }
        elseif ( ! empty($filtros['dado_baja']) AND ($filtros['dado_baja'] === 'todos'))
        {
            // Sin condicion sobre dado_baja: se muestran vigentes y dados de baja.
        }
        else
        {
            // Por defecto, el listado solo muestra activos vigentes.
            $this->db_inv->where('inv_activos.dado_baja', 0);
        }
    }

    public function listar($filtros = array())
    {
        $this->_joins();
        $this->_filtros($filtros);
        $this->db_inv->order_by('inv_activos.id', 'DESC');
        $query = $this->db_inv->get('inv_activos');

        return $query->result_array();
    }

    public function contar($filtros = array())
    {
        $this->_filtros($filtros);
        $query = $this->db_inv->get('inv_activos');

        return $query->num_rows();
    }

    public function seleccionar($id)
    {
        $this->_joins();
        $this->db_inv->where('inv_activos.id', $id);
        $this->db_inv->where('inv_activos.eliminado', 0);
        $query = $this->db_inv->get('inv_activos');

        if ($query->num_rows() > 0) return $query->row_array();
    }

    public function verificar_codigo_unico($codigo_unico, $excluir_id = NULL)
    {
        // La unicidad de codigo_unico es GLOBAL (asi lo exige la restriccion
        // UNIQUE KEY de la tabla): un codigo que ya se uso no puede
        // reutilizarse aunque el activo original este oculto (eliminado=1)
        // o dado de baja. Por eso esta verificacion NO filtra por
        // eliminado ni por dado_baja.
        $this->db_inv->where('codigo_unico', $codigo_unico);
        if ($excluir_id) $this->db_inv->where('id !=', $excluir_id);
        $query = $this->db_inv->get('inv_activos');

        if ($query->num_rows() > 0) return $query->row_array();
    }

    /*
    | Usado por las acciones masivas del listado (dar de baja, reactivar,
    | cambiar ubicacion/responsable/estado seleccionados). SIEMPRE hay que
    | volver a leer de la base los ids que llegaron por POST antes de
    | operar sobre ellos -- nunca se confia en lo que el navegador dijo
    | que estaba seleccionado, ni siquiera para saber cuantos activos hay
    | o si de verdad existen/no estan eliminados.
    */
    public function seleccionar_multiples($ids)
    {
        if (empty($ids)) return array();

        $this->_joins();
        $this->db_inv->where_in('inv_activos.id', $ids);
        $this->db_inv->where('inv_activos.eliminado', 0);
        $query = $this->db_inv->get('inv_activos');

        return $query->result_array();
    }

    public function insertar($array)
    {
        // creado_por guarda el id del usuario autenticado de la intranet
        // ($this->session->userdata('id')) como INT simple, SIN clave
        // foranea hacia la tabla `usuario` (que vive en la otra base de
        // datos, `intranet` -- no se pueden crear FK entre bases
        // distintas). Ver docs/BASE_DATOS_INVENTARIO.md.
        $array['creado'] = date('Y-m-d H:i:s');
        $array['creado_por'] = $this->session->userdata('id');

        if ($this->db_inv->insert('inv_activos', $array)) return $this->db_inv->insert_id();
    }

    public function actualizar($id, $array)
    {
        $array['modificado'] = date('Y-m-d H:i:s');
        $array['modificado_por'] = $this->session->userdata('id');

        $this->db_inv->where('id', $id);
        $this->db_inv->set($array);

        return $this->db_inv->update('inv_activos');
    }

    public function dar_baja($id, $motivo)
    {
        $this->db_inv->where('id', $id);
        $this->db_inv->set(array(
            'dado_baja'      => 1,
            'fecha_baja'     => date('Y-m-d H:i:s'),
            'motivo_baja'    => $motivo,
            'modificado'     => date('Y-m-d H:i:s'),
            'modificado_por' => $this->session->userdata('id'),
        ));

        return $this->db_inv->update('inv_activos');
    }

    public function reactivar($id)
    {
        $this->db_inv->where('id', $id);
        $this->db_inv->set(array(
            'dado_baja'      => 0,
            'fecha_baja'     => NULL,
            'motivo_baja'    => NULL,
            'modificado'     => date('Y-m-d H:i:s'),
            'modificado_por' => $this->session->userdata('id'),
        ));

        return $this->db_inv->update('inv_activos');
    }

    public function eliminar($id)
    {
        $this->db_inv->where('id', $id);
        $this->db_inv->set('eliminado', 1);

        return $this->db_inv->update('inv_activos');
    }

    // ---------------------------------------------------------------
    // Dashboard
    // ---------------------------------------------------------------

    public function total($dado_baja = 0)
    {
        $this->db_inv->where('eliminado', 0);
        $this->db_inv->where('dado_baja', $dado_baja);
        $query = $this->db_inv->get('inv_activos');

        return $query->num_rows();
    }

    public function total_por_estado()
    {
        $this->db_inv->select('inv_estados.nombre AS estado, COUNT(inv_activos.id) AS total');
        $this->db_inv->join('inv_estados', 'inv_estados.id = inv_activos.estado_id', 'left');
        $this->db_inv->where('inv_activos.eliminado', 0);
        $this->db_inv->where('inv_activos.dado_baja', 0);
        $this->db_inv->group_by('inv_activos.estado_id');
        $this->db_inv->order_by('total', 'DESC');
        $query = $this->db_inv->get('inv_activos');

        return $query->result_array();
    }

    public function total_por_area()
    {
        $this->db_inv->select('inv_areas.nombre AS area, COUNT(inv_activos.id) AS total');
        $this->db_inv->join('inv_areas', 'inv_areas.id = inv_activos.area_id', 'left');
        $this->db_inv->where('inv_activos.eliminado', 0);
        $this->db_inv->where('inv_activos.dado_baja', 0);
        $this->db_inv->group_by('inv_activos.area_id');
        $this->db_inv->order_by('total', 'DESC');
        $this->db_inv->limit(8);
        $query = $this->db_inv->get('inv_activos');

        return $query->result_array();
    }
}

/* End of file inventario_model.php */
