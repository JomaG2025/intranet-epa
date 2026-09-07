<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Institucion_model extends CI_Model {
	
	public function listar()
	{
		$query = $this->db->order_by('cod', 'ASC');
		$query = $this->db->get('institucion');
		
		return $query->result_array();
	}
}