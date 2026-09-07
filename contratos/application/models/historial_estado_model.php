<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historial_estado_model extends CI_Model {

    public function insertar($array)
    {
        if ($this->db->insert('historial_estado', $array)) return $this->db->insert_id();
    }

    public function listar($contrato)
    {
        $this->db->where('contrato', $contrato);
        $this->db->order_by('creado', 'DESC');
        return $this->db->get('historial_estado')->result_array();
    }
}

/* End of file historial_estado_model.php */
/* Location: ./application/models/historial_estado_model.php */
