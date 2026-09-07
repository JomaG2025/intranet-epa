<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proceso_model extends CI_Model {
	
	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->set($array);
		$query = $this->db->update('proceso');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('proceso');
			return TRUE;
		}
	}
	
	public function listar($grupo = FALSE)
	{
		if($grupo) $query = $this->db->where('proceso.grupo', $grupo);
		
		$query = $this->db->select('proceso.*, modelo.nombre AS modelo_nombre, grupo.nombre AS grupo_nombre');
		$query = $this->db->join('grupo', 'proceso.grupo = grupo.id');
		$query = $this->db->join('modelo', 'grupo.modelo = modelo.id');
		$query = $this->db->get('proceso');

		return $query->result_array();
	}	
	
	public function eliminar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('proceso');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
		
	public function insertar($array)
	{	
		if($query = $this->db->insert('proceso', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);	
		$query = $this->db->get('proceso');		
		
		if ($query->num_rows() > 0) return $query->row_array();
	}
			
	public function contar()
	{
		$query = $this->db->get('proceso');
		return $query->num_rows();
	}	
}