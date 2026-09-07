<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {
	
	public function autentificar($usuario, $password)
	{
		$query = $this->db->select('id');
		$query = $this->db->where('activo', 1);
		$query = $this->db->where('eliminado', 0);
		$query = $this->db->where('usuario', $usuario);
		$query = $this->db->where('password', MD5($password));
		$query = $this->db->get('usuario');		
		
		if ($query->num_rows() > 0) return TRUE;
	}
	
	public function salir()
	{
		$this->session->sess_destroy();	
	}
}