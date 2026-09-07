<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relacionado_model extends CI_Model {

	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('relacionado');
		
		if ($query->num_rows() > 0) return $query->row_array();
	}
	
	public function verificar($rut, $activo = FALSE, $eliminado = FALSE)
	{
        if($activo) $this->db->where('activo', 1);
        if( ! $eliminado) $this->db->where('eliminado', 0);
        
		$query = $this->db->where('rut', $rut);
		$query = $this->db->get('relacionado');
		
		if ($query->num_rows() > 0) return $query->row_array();
	}
	
	public function insertar($array)
	{	
		if($this->db->insert('relacionado', $array)) return $this->db->insert_id();
	}	
	
	public function listar($funcionarios = FALSE, $eliminados = FALSE)
	{	
        if($funcionarios) $query = $this->db->where("((". $this->db->dbprefix ."relacionado.tipo = 'FUN') OR (". $this->db->dbprefix ."relacionado.tipo = 'DIR'))");
        if( ! $eliminados) $query = $this->db->where('relacionado.eliminado', '0');
        
		$query = $this->db->select('relacionado.*, tipo.descripcion AS tipo, relacion.nombre AS relacion');
		$query = $this->db->join('tipo', 'tipo.abrev = relacionado.tipo');
		$query = $this->db->join('relacionado relacion', 'relacionado.relacionado = relacion.rut', 'left');
		$query = $this->db->get('relacionado');
		
		return $query->result_array();
	}
	
	public function contar($eliminados = FALSE)
	{
        if( ! $eliminados) $query = $this->db->where('eliminado', 0);
        
		$query = $this->db->get('relacionado');
		return $query->num_rows();
	}
	
	public function listar_tipo()
	{
		$query = $this->db->get('tipo');
		return $query->result_array();		
	}
	
	public function eliminar($id)
	{
		$query = $this->db->where('id', $id);
        $this->db->set('eliminado', 1);
        $this->db->set('modificado', date('Y-m-d H:i:s'));
		$query = $this->db->update('relacionado');
        
        return TRUE;
	}
	
	public function purgar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('relacionado');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
	
	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('relacionado');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('relacionado');
			return TRUE;
		}
	}			
}