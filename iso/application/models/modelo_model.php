<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelo_model extends CI_Model {
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->set($array);
		$query = $this->db->update('modelo');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('modelo');
			return TRUE;
		}
	}
    
    public function listar_api($inicio = 0, $limit = FALSE, $activos = FALSE)
	{	
		if($limit) $query = $this->db->limit($limit, $inicio);
        if($activos) $query = $this->db->where('activo', 1);
        $query = $this->db->order_by('creado', 'DESC');
		$query = $this->db->get('modelo');
		
		return $query->result_array();	
	}
	
	public function listar($inicio = 0, $limit = FALSE, $activos = FALSE)
	{	
		if($limit) $query = $this->db->limit($limit, $inicio);
        if($activos) $query = $this->db->where('activo', 1);
		$query = $this->db->get('modelo');
		
		return $query->result_array();	
	}
    
    public function eliminar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('modelo');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
		
	public function insertar($array)
	{	
		if($query = $this->db->insert('modelo', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('modelo');		
		
		if ($query->num_rows() > 0) return $query->row_array();
	}
			
	public function contar()
	{
		$query = $this->db->get('modelo');
		return $query->num_rows();
	}
}