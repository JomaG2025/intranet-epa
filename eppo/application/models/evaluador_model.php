<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluador_model extends CI_Model {
    
    public function listar($periodo = NULL, $eliminados = NULL)
	{
        if($periodo) $query = $this->db->where('evaluador.periodo', $periodo);
        if( ! $eliminados) $query = $this->db->where('usuario.eliminado', 0);
        
		$query = $this->db->select('evaluador.*, COUNT('. $this->db->dbprefix .'evaluado.id) AS evaluados, usuario.eliminado AS usuario_eliminado');
		$query = $this->db->join('evaluado', 'evaluador.id = evaluado.evaluador', 'left');
		$query = $this->db->join('usuario', 'evaluador.usuario = usuario.usuario', 'left');
		$query = $this->db->group_by('evaluador.id');
        $query = $this->db->order_by('evaluador.periodo', 'DESC');
		$query = $this->db->get('evaluador');
		
		return $query->result_array();
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('evaluador');		
		
		if($query->num_rows() > 0) return $query->row_array(); 
	}
    
    public function verificar($usuario, $periodo = NULL, $evaluado = NULL)
	{
        if($periodo)
        {
            $query = $this->db->where('evaluador.periodo', $periodo);
        }
        
        if($evaluado)
        {
            $query = $this->db->join('evaluado', 'evaluador.id = evaluado.evaluador');
            $query = $this->db->where('evaluado.usuario', $evaluado);
        }
        
        $query = $this->db->where('evaluador.usuario', $usuario);
		$query = $this->db->get('evaluador');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('evaluador', $array)) return $this->db->insert_id();
	}
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('evaluador');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('evaluador');
			return TRUE;
		}
	}
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('evaluador');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
    
    public function contar_evaluados($evaluador)
	{
        $query = $this->db->where('evaluador', $evaluador);
		$query = $this->db->get('evaluado');
		return $query->num_rows();
	}
}