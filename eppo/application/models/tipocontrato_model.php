<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipocontrato_model extends CI_Model {
	
	public function listar()
	{
		$query = $this->db->get('tipocontrato');
		return $query->result_array();
	}
    
    public function verificar($abrev)
	{
		$query = $this->db->where('abrev', $abrev);
		$query = $this->db->get('tipocontrato');		
		
		if($query->num_rows() > 0) return TRUE;
	}
}