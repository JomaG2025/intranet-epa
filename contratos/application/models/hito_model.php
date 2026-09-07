<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hito_model extends CI_Model {

    public function listar($contrato)
    {
        $this->db->where('contrato', $contrato);
        $this->db->order_by('fecha_comprometida', 'ASC');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('hito');
        return $query->result_array();
    }

    public function seleccionar($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('hito');
        if ($query->num_rows() > 0) return $query->row_array();
    }

    public function insertar($array)
    {
        if ($this->db->insert('hito', $array)) return $this->db->insert_id();
    }

    public function actualizar($id, $array)
    {
        $array['modificado'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('hito', $array);
        return ($this->db->affected_rows() > 0);
    }

    public function eliminar($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('hito');
        return ($this->db->affected_rows() > 0);
    }

    public function suma_montos($contrato, $excluir_id = NULL)
    {
        $this->db->select_sum('monto');
        $this->db->where('contrato', $contrato);
        $this->db->where('monto IS NOT NULL', NULL, FALSE);
        if ($excluir_id) $this->db->where('id !=', $excluir_id);
        $row = $this->db->get('hito')->row_array();
        return (isset($row['monto']) && $row['monto'] !== NULL) ? floatval($row['monto']) : 0.0;
    }
}

/* End of file hito_model.php */
/* Location: ./application/models/hito_model.php */
