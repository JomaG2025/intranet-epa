<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comite_model extends CI_Model {
    
    public function listar($periodo = NULL)
	{
        if($periodo) $query = $this->db->where('periodo', $periodo);
        
		$query = $this->db->select('comite.*');
		$query = $this->db->join('usuario', 'comite.usuario = usuario.usuario', 'left');
		$query = $this->db->where('usuario.eliminado', 0);
		$query = $this->db->order_by('periodo', 'DESC');
		$query = $this->db->get('comite');
		
		return $query->result_array();
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('comite');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function verificar($usuario, $periodo)
	{
		$query = $this->db->where('periodo', $periodo);
		$query = $this->db->where('usuario', $usuario);
		$query = $this->db->get('comite');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('comite', $array)) return $this->db->insert_id();
	}
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('comite');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
}
