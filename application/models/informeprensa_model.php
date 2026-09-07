<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Informeprensa_model extends CI_Model {
    
    public function actualizar($id, $array)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->set($array);
		$query = $this->db->update('informeprensa');
	}
	
	public function listar($inicio = 0, $limit = false)
	{
		if($limit) $query = $this->db->limit($limit, $inicio);
		
        $query = $this->db->order_by('fecha', 'DESC');
		$query = $this->db->get('informeprensa');
		
		return $query->result_array();	
	}
    
    public function listar_api($ano = NULL, $mes = NULL)
	{
		if($ano) $query = $this->db->where('YEAR(fecha)', $ano);
		if($mes) $query = $this->db->where('MONTH(fecha)', $mes);
		
        $query = $this->db->select("id, fecha AS date, 'true' AS badge", false);
        $query = $this->db->order_by('fecha', 'DESC');
		$query = $this->db->get('informeprensa');
		
		return $query->result_array();	
	}
	
	public function eliminar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->delete('informeprensa');
		
		if ($this->db->_error_number() == 1451)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function insertar($array)
	{		
		if($query = $this->db->insert('informeprensa', $array)) return $this->db->insert_id();
	}
	
	public function verificar($fecha)
	{
		$query = $this->db->where('fecha', $fecha);
		$query = $this->db->get('informeprensa');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('informeprensa');		
		
		if ($query->num_rows() > 0) return $query->row_array(); 
	}
		
	public function contar()
	{
		$query = $this->db->get('informeprensa');
		return $query->num_rows();
	}
}