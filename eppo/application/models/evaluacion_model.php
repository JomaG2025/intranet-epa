<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluacion_model extends CI_Model {
        
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('evaluacion');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function verificar($objetivoarea = NULL, $objetivoindividual = NULL)
	{
        if(
            ( ! $objetivoarea) AND
            ( ! $objetivoindividual)
        ){
            return NULL;   
        }
        
        $query = ($objetivoarea) ? $this->db->where('objetivoarea', $objetivoarea) : $this->db->where('objetivoindividual', $objetivoindividual);
		$query = $this->db->get('evaluacion');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('evaluacion', $array)) return $this->db->insert_id();
	}
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('evaluacion');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('evaluacion');
			return TRUE;
		}
	}
}