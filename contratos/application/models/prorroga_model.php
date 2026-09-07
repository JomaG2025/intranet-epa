<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prorroga_model extends CI_Model {

    public function listar($contrato)
    {
        $this->db->where('contrato', $contrato);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('prorroga')->result_array();
    }

    public function insertar($array)
    {
        if ($this->db->insert('prorroga', $array)) return $this->db->insert_id();
    }
}

/* End of file prorroga_model.php */
/* Location: ./application/models/prorroga_model.php */
