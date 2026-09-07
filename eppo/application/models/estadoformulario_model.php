<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estadoformulario_model extends CI_Model {
	
	public function listar()
	{
		$query = $this->db->get('estadoformulario');
		return $query->result_array();
	}
    
    public function verificar($abrev)
	{
		$query = $this->db->where('abrev', $abrev);
		$query = $this->db->get('estadoformulario');		
		
		if($query->num_rows() > 0) return TRUE;
	}
}