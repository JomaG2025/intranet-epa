<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluado_model extends CI_Model {
    
    public function listar($evaluador)
	{
        $query = $this->db->where('evaluador', $evaluador);
		$query = $this->db->get('evaluado');
		
		return $query->result_array();
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('evaluado');		
		
		if($query->num_rows() > 0) return $query->row_array(); 
	}
    
    public function listar_evaluadores($usuario, $periodo)
    {
        $query = $this->db->select('evaluador.*'); 
        $query = $this->db->join('evaluador', 'evaluador.id = evaluado.evaluador'); 
        $query = $this->db->where('evaluado.usuario', $usuario);
        $query = $this->db->where('evaluador.periodo', $periodo);
        $query = $this->db->get('evaluado');		

        return $query->result_array();
    }
    
    public function insertar($array)
	{	
		if($this->db->insert('evaluado', $array)) return $this->db->insert_id();
	}
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('evaluado');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
    
    public function eliminar_por_evaluador($evaluador)
    {
        $this->db->db_debug = FALSE;
		
		$this->db->where('evaluador', $evaluador);
		$this->db->delete('evaluado');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
    }
}
