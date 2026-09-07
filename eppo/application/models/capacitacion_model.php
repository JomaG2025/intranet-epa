<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Capacitacion_model extends CI_Model {
    
    public function listar($formulario)
	{
		$query = $this->db->select('capacitacion.*, COUNT('. $this->db->dbprefix .'retroalimentacion.id) AS retroalimentaciones');
		$query = $this->db->join('retroalimentacion', 'capacitacion.id = retroalimentacion.capacitacion', 'left');
		$query = $this->db->where('capacitacion.formulario', $formulario);
		$query = $this->db->group_by('capacitacion.id');
		$query = $this->db->get('capacitacion');
		
		return $query->result_array();
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('capacitacion');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('capacitacion', $array)) return $this->db->insert_id();
	}
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('capacitacion');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('capacitacion');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('capacitacion');
			return TRUE;
		}
	}
}