<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retroalimentacion_model extends CI_Model {
    
    public function listar($item, $id)
	{
        switch($item)
        {
            case 'objetivoarea':
                $query = $this->db->where('objetivoarea', $id);	
            break;
                
            case 'objetivoindividual':
                $query = $this->db->where('objetivoindividual', $id);	
            break;
                
            case 'capacitacion':
                $query = $this->db->where('capacitacion', $id);	
            break;
                
            default: 
                return FALSE;
                exit();
            break;
        }
        
		$query = $this->db->get('retroalimentacion');	
		return $query->result_array();
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('retroalimentacion');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('retroalimentacion', $array)) return $this->db->insert_id();
	}
}