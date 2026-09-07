<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documento_model extends CI_Model {

	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->set($array);
		$query = $this->db->update('documento');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('documento');
			return TRUE;
		}
	}
		
	public function listar($proceso, $carpeta = NULL)
	{
		if($carpeta) $query = $this->db->where('carpeta', $carpeta);
		else $query = $this->db->where('carpeta IS NULL', NULL);
	
		$query = $this->db->where('proceso', $proceso);
		$query = $this->db->order_by('nombre', 'ASC');
		$query = $this->db->get('documento');
		
		return $query->result_array();
	}
    
    public function listar_api($term = NULL)
	{
		if($term) $query = $this->db->like('documento.nombre', $term);
	
		$query = $this->db->select('documento.id, documento.nombre AS value, proceso.id AS proceso_id, proceso.nombre AS proceso_nombre, proceso.codigo AS proceso_codigo');
		$query = $this->db->join('proceso', 'proceso.id = documento.proceso');
		$query = $this->db->join('grupo', 'grupo.id = proceso.grupo');
		$query = $this->db->join('modelo', 'modelo.id = grupo.modelo');
		$query = $this->db->where('modelo.activo', 1);
		$query = $this->db->order_by('documento.nombre', 'ASC');
		$query = $this->db->get('documento');
		
		return $query->result_array();
	}
	
	public function eliminar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('documento');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
	
	public function insertar($array)
	{	
		if($query = $this->db->insert('documento', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('documento');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
		
	public function contar($proceso)
	{
		$query = $this->db->where('proceso', $proceso);
		$query = $this->db->get('documento');
		return $query->num_rows();
	}
}