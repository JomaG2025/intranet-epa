<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {
	
	public function autentificar($session_id)
	{	
		$db_intranet = $this->load->database('int', TRUE);
		
		$query = $db_intranet->where('session_id', $session_id);
		$query = $db_intranet->get('sesion');	
		
		if ($query->num_rows() > 0)
		{
			$session = $query->row_array();
			
			if( ! empty($session['user_data']))
			{
				$user_data = unserialize($session['user_data']);
				$query->free_result();
				
				$query = $this->db->where('activo', 1);
				$query = $this->db->where('eliminado', 0);
				$query = $this->db->where('usuario', $user_data['usuario']);
				$query = $this->db->get('usuario');	
				
				if ($query->num_rows() > 0)
				{
					$row = $query->row_array(); 
				
					$this->session->set_userdata('id', $row['id']);
					$this->session->set_userdata('nombre', $user_data['nombre']);
					$this->session->set_userdata('usuario', $user_data['usuario']);
					$this->session->set_userdata('privilegio', $row['privilegio']);
					$this->session->set_userdata('sexo', $user_data['sexo']);
					
					return TRUE;
				}
			}
		}
	}
			
	public function salir()
	{
		$this->session->sess_destroy();	
	}	
}