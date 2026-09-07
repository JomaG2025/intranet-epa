<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Objetivoindividual_model extends CI_Model {
    
    public function listar($formulario, $mostrarcompetenciastransversales = NULL)
	{
        if( ! is_null($mostrarcompetenciastransversales))
        {
            $query = ($mostrarcompetenciastransversales) ? $this->db->where('objetivoindividual.competenciatransversal IS NOT NULL', NULL, FALSE) : $this->db->where('objetivoindividual.competenciatransversal IS NULL', NULL, FALSE);
        }
        
		$query = $this->db->select('objetivoindividual.*, COUNT('. $this->db->dbprefix .'retroalimentacion.id) AS retroalimentaciones, evaluacion.sigla AS evaluacion_sigla, evaluacion.puntaje AS evaluacion_puntaje,  evaluacion.observacion AS evaluacion_observacion');
		$query = $this->db->join('retroalimentacion', 'objetivoindividual.id = retroalimentacion.objetivoindividual', 'left');
        $query = $this->db->join('evaluacion', 'objetivoindividual.id = evaluacion.objetivoindividual', 'left');
		$query = $this->db->where('objetivoindividual.formulario', $formulario);
		$query = $this->db->group_by('objetivoindividual.id');
		$query = $this->db->get('objetivoindividual');
		
		return $query->result_array();
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('objetivoindividual');		
		
		if($query->num_rows() > 0) return $query->row_array(); 
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('objetivoindividual', $array))
		{
			return $this->db->insert_id();
		}
	}
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('objetivoindividual');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('objetivoindividual');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('objetivoindividual');
			return TRUE;
		}
	}
}