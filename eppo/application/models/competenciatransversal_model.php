<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Competenciatransversal_model extends CI_Model {
	
	public function listar($activas = TRUE)
	{
        if( ! $activas) $query = $this->db->where('activo', 0);
        
		$query = $this->db->get('competenciatransversal');
		return $query->result_array();
	}
}