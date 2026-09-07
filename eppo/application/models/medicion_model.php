<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicion_model extends CI_Model {
	
	public function listar()
	{
		$query = $this->db->get('medicion');
		return $query->result_array();
	}
    
    public function verificar($sigla)
	{
		$query = $this->db->where('sigla', $sigla);
		$query = $this->db->get('medicion');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function rango()
	{
		$query = $this->db->query('SELECT MIN( puntaje ) AS minimo, MAX( puntaje ) AS maximo FROM '. $this->db->dbprefix .'medicion');	
		
		if($query->num_rows() > 0) return $query->row_array();
	}
}