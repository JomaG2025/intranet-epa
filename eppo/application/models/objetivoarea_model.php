<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Objetivoarea_model extends CI_Model {
    
    public function listar($formulario)
	{
		$query = $this->db->select('objetivoarea.*, COUNT('. $this->db->dbprefix .'retroalimentacion.id) AS retroalimentaciones, evaluacion.sigla AS evaluacion_sigla, evaluacion.puntaje AS evaluacion_puntaje, evaluacion.observacion AS evaluacion_observacion');
		$query = $this->db->join('retroalimentacion', 'objetivoarea.id = retroalimentacion.objetivoarea', 'left');
		$query = $this->db->join('evaluacion', 'objetivoarea.id = evaluacion.objetivoarea', 'left');
		$query = $this->db->where('objetivoarea.formulario', $formulario);
		$query = $this->db->group_by('objetivoarea.id');
		$query = $this->db->get('objetivoarea');
		
		return $query->result_array();
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('objetivoarea');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('objetivoarea', $array)) return $this->db->insert_id();
	}
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('objetivoarea');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('objetivoarea');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('objetivoarea');
			return TRUE;
		}
	}
}