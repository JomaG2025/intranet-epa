<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
		if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
		
        $this->load->model('usuario_model', 'usuario');
        $this->load->model('password_model', 'password');
        $this->load->model('auditoria_model', 'auditoria');
    }
		
	public function index()
	{
		$data['usuarios'] = $this->usuario->listar();
		
		$this->load->view('header');
		$this->load->view('admin/usuarios/index', $data);
		$this->load->view('footer');
	}
	
	public function nuevo()
	{
		$data['privilegios'] = $this->usuario->listar_privilegio();
	
		$this->load->view('header');
		$this->load->view('admin/usuarios/nuevo', $data);
		$this->load->view('footer');
	}
	
	public function grabar()
	{
		if(
			( ! $this->input->post('usuario')) OR 
			( ! $this->input->post('nombre')) OR 
			( ! $this->input->post('privilegio')) OR 
			( ! $this->input->post('password')) OR
			( ! $this->input->post('password2'))
		)
		{
			redirect('admin/usuarios/nuevo', 'danger_01', TRUE);
		}
		
		if($this->usuario->verificar($this->input->post('usuario'))) redirect('admin/usuarios/nuevo', 'danger_03', TRUE);
		if($this->input->post('password') != $this->input->post('password2')) redirect('admin/usuarios/nuevo', 'danger_08', TRUE);
		if(( ! preg_match('/[a-zA-Z0-9]{8,}/', $this->input->post('password'))) OR (preg_match_all('/[0-9]/', $this->input->post('password'), $a) == 0) OR (preg_match_all('/[a-zA-Z]/', $this->input->post('password'), $a) == 0)) redirect('admin/usuarios/nuevo', 'danger_09', TRUE);
        
		$usuario['usuario'] = $this->input->post('usuario');
		$usuario['nombre'] = $this->input->post('nombre');
		$usuario['password'] = $this->input->post('password');
		$usuario['privilegio'] = $this->input->post('privilegio');
		$usuario['cargo'] = $this->input->post('cargo') ? $this->input->post('cargo') : NULL;
		$usuario['email'] = $this->input->post('email') ? $this->input->post('email') : NULL;
		$usuario['rut'] = $this->input->post('rut') ? $this->input->post('rut') : NULL;
		$usuario['dv'] = $this->input->post('dv');
		$usuario['sexo'] = (in_array($this->input->post('sexo'), array('M', 'F'))) ? $this->input->post('sexo') : NULL;
        $usuario['primer_inicio'] = ($this->input->post('primer_inicio')) ? 1 : 0;
        
		if($usuario = $this->usuario->insertar($usuario))
		{
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'usuario', 'instancia' => $usuario));
			redirect('admin/usuarios', 'success_01');
		}
        else
        {
            redirect('admin/usuarios/nuevo', 'danger_01', TRUE);
        }
	}

	public function editar($id = NULL)
	{	
		if(empty($id)) redirect('usuarios', 'danger_01');
	
		if($data['usuario'] = $this->usuario->seleccionar($id))
		{
			$data['privilegios'] = $this->usuario->listar_privilegio();
			
			$this->load->view('header');
			$this->load->view('admin/usuarios/editar', $data);
			$this->load->view('footer');					
		}
		else
		{
			redirect('admin/usuarios', 'danger_02', TRUE);
		}
	}
	
	public function actualizar()
	{
		if(
			( ! $this->input->post('nombre')) OR 
			( ! $this->input->post('privilegio'))
		)
		{
			redirect('admin/usuarios', 'danger_01', TRUE);
		}
	
		if($tmp = $this->usuario->seleccionar($this->input->post('id')))
		{
			if($this->input->post('password'))
			{
				if($this->input->post('password') != $this->input->post('password2')) redirect('admin/usuarios/editar/' . $this->input->post('id'), 'danger_08');	
				if(( ! preg_match('/[a-zA-Z0-9]{8,}/', $this->input->post('password'))) OR (preg_match_all('/[0-9]/', $this->input->post('password'), $a) == 0) OR (preg_match_all('/[a-zA-Z]/', $this->input->post('password'), $a) == 0)) redirect('admin/usuarios/editar/' . $this->input->post('id'), 'danger_09');
                $usuario['password'] = $this->input->post('password');
            }
			
			$usuario['nombre'] = $this->input->post('nombre');
			$usuario['privilegio'] = $this->input->post('privilegio');
			$usuario['cargo'] = $this->input->post('cargo') ? $this->input->post('cargo') : NULL;
			$usuario['email'] = $this->input->post('email') ? $this->input->post('email') : NULL;
			$usuario['rut'] = $this->input->post('rut') ? $this->input->post('rut') : NULL;
			$usuario['dv'] = $this->input->post('dv');
			$usuario['sexo'] = (in_array($this->input->post('sexo'), array('M', 'F'))) ? $this->input->post('sexo') : NULL;
            $usuario['primer_inicio'] = ($this->input->post('primer_inicio')) ? 1 : 0;
				
			if($this->usuario->actualizar($this->input->post('id'), $usuario))
			{
                if($this->input->post('password'))
                {
                    if( ! $this->password->verificar($tmp['usuario'], $this->input->post('password'))) $this->password->insertar(array('usuario' => $tmp['usuario'], 'password' => $this->input->post('password')));
                }      
                
				$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'usuario', 'instancia' => $this->input->post('id')));
				redirect('admin/usuarios', 'success_02');
			}
			else
			{
				redirect('admin/usuarios', 'warning_01');
			}	
		}
		else
		{
			redirect('admin/usuarios', 'danger_02');
		}
		
	}
	
	public function activo($id = NULL)
	{
		if(empty($id)) redirect('admin/usuarios', 'danger_01', TRUE);
	
		if($usuario = $this->usuario->seleccionar($id))
		{
			$activo['activo'] = ($usuario['activo'] == 1) ? '0' : '1';
	
			$this->usuario->actualizar($id, $activo);
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'usuario', 'instancia' => $id));
			redirect('admin/usuarios', 'success_02', TRUE);				
		}
		else
		{
			redirect('admin/usuarios', 'danger_02', TRUE);
		}
	}
	
	public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('usuarios', 'danger_01', TRUE);
	
		if($usuario = $this->usuario->seleccionar($id))
		{
			$this->usuario->eliminar($id);
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'usuario', 'instancia' => $id));
			redirect('admin/usuarios', 'success_03', TRUE);	
		}
		else
		{
			redirect('admin/usuarios', 'danger_02', TRUE);
		}
	}
}

/* End of file usuarios.php */
/* Location: ./application/controllers/admin/usuarios.php */