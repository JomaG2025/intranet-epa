<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipopago_model extends CI_Model {
	
	public function listar()
	{
		$query = $this->db->get('tipopago');
		
		return $query->result_array();
	}
}