<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Archivo_model extends CI_Model {

	public function actualizar($id, $array)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->set($array);
		$query = $this->db->update('archivo');
		
		if($this->db->affected_rows() > 0 ) return TRUE;
	}
		
	public function listar($contrato)
	{	
		$query = $this->db->where('contrato', $contrato);
		$query = $this->db->order_by('raw_name', 'ASC');
		$query = $this->db->get('archivo');
		
		return $query->result_array();
	}	
	
	public function eliminar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->delete('archivo');
		
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
		if($query = $this->db->insert('archivo', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('archivo');		
		
		if ($query->num_rows() > 0) return $query->row_array(); 
	}
		
	public function contar($garantia)
	{
		$query = $this->db->where('contrato', $contrato);
		$query = $this->db->get('archivo');
		return $query->num_rows();
	}	
}