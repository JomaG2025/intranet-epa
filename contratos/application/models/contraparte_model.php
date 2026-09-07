<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contraparte_model extends CI_Model {
	
	public function listar($contrato)
	{
		$query = $this->db->where('contrato', $contrato);
		$query = $this->db->get('contraparte');
		
		return $query->result_array();
	}
    public function listar_por_contratos($ids = array())
	{
    if(empty($ids)) return array();

    $query = $this->db->select('contrato, usuario')
                      ->where_in('contrato', $ids)
                      ->order_by('contrato', 'ASC')
                      ->get('contraparte');

    return $query->result_array();
	}
    public function insertar($array)
    {
        if($query = $this->db->insert('contraparte', $array)) return $this->db->insert_id();
    }
    
    public function verificar($contrato, $usuario)
	{
		$query = $this->db->where('contrato', $contrato);
		$query = $this->db->where('usuario', $usuario);
		$query = $this->db->get('contraparte');		
		
		if ($query->num_rows() > 0) return $query->row_array();
	}
    
    public function eliminar($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('contraparte');
		
		if ($this->db->_error_number() == 1451)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
    
    public function listar_responsables()
    {
        $this->db->distinct();
        $this->db->select('usuario');
        $this->db->order_by('usuario', 'ASC');
        return $this->db->get('contraparte')->result_array();
    }

    public function eliminar_por_contrato($contrato)
	{
		$this->db->where('contrato', $contrato);
		$this->db->delete('contraparte');
		
		if ($this->db->_error_number() == 1451)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}