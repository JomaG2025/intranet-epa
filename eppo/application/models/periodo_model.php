<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Periodo_model extends CI_Model {
    
    public function listar()
	{
		$query = $this->db->order_by('periodo', 'DESC');	
		$query = $this->db->get('periodo');	
		return $query->result_array();
	}
    
    public function seleccionar_actual()
	{
		$query = $this->db->limit(1);		
		$query = $this->db->order_by('periodo', 'DESC');		
		$query = $this->db->get('periodo');		
		
		if($query->num_rows() > 0)
		{
			 return $query->row_array(); 
		}
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('periodo');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('periodo', $array))
		{
			return $this->db->insert_id();
		}
	}
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('periodo');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('periodo');
			return TRUE;
		}
	}
    
    public function verificar($periodo)
	{
		$query = $this->db->where('periodo', $periodo);
		$query = $this->db->get('periodo');		
		
		if($query->num_rows() > 0) return TRUE;
	}
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('periodo');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
}