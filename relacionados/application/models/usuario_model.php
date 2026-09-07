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
	
	public function listar($inicio = 0, $limit = FALSE, $eliminados = FALSE)
	{
		if($limit) $query = $this->db->limit($limit, $inicio);
        if( ! $eliminados) $query = $this->db->where('eliminado', 0);
		
		$query = $this->db->select('usuario.*, privilegio.nombre AS privilegio_nombre, privilegio.abrev AS privilegio_abrev');
        $query = $this->db->join('privilegio', 'privilegio.abrev = usuario.privilegio');
		$query = $this->db->get('usuario');
		
		return $query->result_array();
	}

	public function listar_intranet()
    {   
        $db_intranet = $this->load->database('int', TRUE);

        $query = $db_intranet->select('usuario');
        $query = $db_intranet->select('nombre');
        $query = $db_intranet->where('activo', 1);
        $query = $db_intranet->get('usuario');
        
        return $query->result_array();
    }

    public function seleccionar_intranet($usuario)
    {
        $db_intranet = $this->load->database('int', TRUE);

        $query = $db_intranet->select('usuario');
        $query = $db_intranet->select('nombre');
        $query = $db_intranet->select('email');
        $query = $db_intranet->select('rut');
        $query = $db_intranet->select('dv');
        $query = $db_intranet->select('cargo');
        $query = $db_intranet->select('sexo');
        $query = $db_intranet->where('usuario', $usuario);
        $query = $db_intranet->get('usuario');  
        
        if ($query->num_rows() > 0) return $query->row_array();
    }
    
    public function eliminar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->set(array('eliminado' => 1));
		$query = $this->db->update('usuario');
        
        return TRUE;
	}
	
	public function purgar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('usuario');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
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
		
	public function contar($eliminados = FALSE)
	{
        if( ! $eliminados) $query = $this->db->where('eliminado', 0);
        
		$query = $this->db->get('usuario');
		return $query->num_rows();
	}
}