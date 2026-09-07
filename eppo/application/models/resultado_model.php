<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resultado_model extends CI_Model {
    
    public function verificar($formulario)
	{
		$query = $this->db->where('formulario', $formulario);
		$query = $this->db->get('resultado');		
		
		if($query->num_rows() > 0) return $query->row_array(); 
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('resultado', $array)) return $this->db->insert_id();
	}
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('resultado');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('resultado');
			return TRUE;
		}
	}
}