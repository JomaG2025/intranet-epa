<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Password_model extends CI_Model {
	
	public function insertar($array)
	{
        $array['password'] = MD5($array['password']);
        
		if($query = $this->db->insert('password', $array))
        {
            $this->_eliminar_antiguas($array['usuario']);
            return $this->db->insert_id();
        }
	}
	
	public function verificar($usuario, $password)
    {
		$query = $this->db->where('usuario', $usuario);
		$query = $this->db->where('password', MD5($password));
		$query = $this->db->get('password');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    private function _listar_ultimas_passwords($usuario)
    {
        $query = $this->db->where('usuario', $usuario);
        $query = $this->db->limit(5);
        $query = $this->db->order_by('creado', 'DESC');
        $query = $this->db->get('password');
		
		return $query->result_array();	
    }
    
    private function _eliminar_antiguas($usuario)
	{
        $ultimas = $this->_listar_ultimas_passwords($usuario);
        
        if($ultimas)
        {
            $this->db->query('DELETE FROM '.  $this->db->dbprefix  .'password WHERE usuario = "'. $usuario .'" AND id NOT IN ('. implode(',', array_column($ultimas, 'id')) .')');
            return TRUE;
        }
    }
}