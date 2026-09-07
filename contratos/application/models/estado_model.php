<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estado_model extends CI_Model {
	
	public function listar()
	{
		$query = $this->db->get('estado');
		return $query->result_array();
	}	
}