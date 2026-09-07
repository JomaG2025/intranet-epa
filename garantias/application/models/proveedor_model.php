<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proveedor_model extends CI_Model {
	
	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('proveedor');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('proveedor');
			return TRUE;
		}
	}
	
	public function listar($inicio = 0, $limit = FALSE, $term = NULL)
	{
		if($limit) $query = $this->db->limit($limit, $inicio);
        if($term) $query = $this->db->like('razon', $term);
		
		$query = $this->db->order_by('razon', 'ASC');
		$query = $this->db->get('proveedor');
		
		return $query->result_array();
	}	
	
	public function eliminar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('proveedor');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
	
	public function insertar($array)
	{	
		if($query = $this->db->insert('proveedor', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('proveedor');		
		
		if ($query->num_rows() > 0) return $query->row_array(); 
	}
    
    public function comprobar($rut)
	{
		$query = $this->db->where('rut', $rut);
		$query = $this->db->get('proveedor');		
		
		if ($query->num_rows() > 0) return $query->row_array(); 
	}
    
    public function seleccionar_por_razon($razon)
	{
		$query = $this->db->where('razon', $razon);
		$query = $this->db->limit(1);		
		$query = $this->db->get('proveedor');		
		
		if ($query->num_rows() > 0) return $query->row_array(); 
	}
		
	public function contar()
	{
		$query = $this->db->get('proveedor');
		return $query->num_rows();
	}	
}