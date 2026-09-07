<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_model extends CI_Model {
	
	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('usuario');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('usuario');
			return TRUE;
		}
	}
	
	public function listar($inicio = 0, $limit = FALSE)
	{
		if($limit) $query = $this->db->limit($limit, $inicio);
		
		$query = $this->db->select('usuario.*, privilegio.nombre AS privilegio_nombre, privilegio.abrev AS privilegio_abrev');
        $query = $this->db->join('privilegio', 'privilegio.abrev = usuario.privilegio');
		$query = $this->db->get('usuario');
		
		return $query->result_array();
	}	
	
	public function eliminar($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('usuario');
		
		if ($this->db->_error_number() == 1451)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function listar_privilegio()
	{
		$query = $this->db->order_by('id', 'ASC');
		$query = $this->db->get('privilegio');
		
		return $query->result_array();
	}
	
	public function insertar($array)
	{	
		if($query = $this->db->insert('usuario', $array)) return $this->db->insert_id();
	}
	
	public function verificar($usuario)
	{
		$query = $this->db->where('usuario', $usuario);
		$query = $this->db->get('usuario');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('usuario');		
		
		if ($query->num_rows() > 0) return $query->row_array();
	}
		
	public function contar()
	{
		$query = $this->db->get('usuario');
		return $query->num_rows();
	}
}