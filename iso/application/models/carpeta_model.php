<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carpeta_model extends CI_Model {

	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->set($array);
		$query = $this->db->update('carpeta');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('carpeta');
			return TRUE;
		}
	}
		
	public function listar($proceso)
	{
		$query = $this->db->where('proceso', $proceso);
		$query = $this->db->order_by('nombre', 'ASC');
		$query = $this->db->get('carpeta');
		
		return $query->result_array();
	}	
	
	public function eliminar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('carpeta');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
	
	public function insertar($array)
	{	
		if($query = $this->db->insert('carpeta', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('carpeta');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
		
	public function contar($proceso)
	{
		$query = $this->db->where('proceso', $proceso);
		$query = $this->db->get('carpeta');
		return $query->num_rows();
	}
}