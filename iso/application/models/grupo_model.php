<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grupo_model extends CI_Model {

	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->set($array);
		$query = $this->db->update('grupo');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('grupo');
			return TRUE;
		}
	}
		
	public function listar()
	{
		$query = $this->db->select('grupo.*, modelo.nombre AS modelo_nombre');
		$query = $this->db->join('modelo', 'modelo.id = grupo.modelo');
		$query = $this->db->get('grupo');
		
		return $query->result_array();
	}	
	
	public function eliminar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('grupo');
		
		if($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
	
	public function insertar($array)
	{	
		if($query = $this->db->insert('grupo', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('grupo');		
		
		if($query->num_rows() > 0) return $query->row_array(); 
	}
		
	public function contar($proceso)
	{
		$query = $this->db->where('proceso', $proceso);
		$query = $this->db->get('grupo');
		return $query->num_rows();
	}	
}