<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipoplazo_model extends CI_Model {
	
	public function listar()
	{
		$query = $this->db->get('tipoplazo');
		
		return $query->result_array();
	}
}