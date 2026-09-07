<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
        
        $this->load->model('usuario_model', 'usuario');  
        $this->load->model('auditoria_model', 'auditoria');  
    }
	
	public function index()
	{
		$data['usuarios'] = $this->usuario->listar();
			
		$this->load->view('header');
		$this->load->view('admin/usuarios/index', $data);
		$this->load->view('footer');
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
	
	public function nuevo()
	{
		$data['usuarios'] = json_decode(file_get_contents('http://intranet.puertoarica.cl/index.php/api/usuarios'), TRUE);
		$data['privilegios'] = $this->usuario->listar_privilegio();	
		
		$this->load->view('header');
		$this->load->view('admin/usuarios/nuevo', $data);
		$this->load->view('footer');
	}
	
	public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/usuarios', 'danger_01', TRUE);
		
		if( ! $this->usuario->seleccionar($id)) redirect('admin/usuarios', 'danger_02', TRUE);
		
		if($this->usuario->eliminar($id))
		{
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'usuario', 'instancia' => $id));
			redirect('admin/usuarios', 'success_03', TRUE);
		}	
	}
	
	public function editar($id = NULL)
	{
		if(empty($id)) redirect('admin/usuarios', 'danger_01', TRUE);

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
	
	public function grabar()
	{
		if(
			( ! $this->input->post('usuario')) OR
			( ! $this->input->post('privilegio'))
		){
            redirect('admin/usuarios/nuevo', 'danger_01', TRUE);
		}
		
		if($this->usuario->verificar($this->input->post('usuario'))) redirect('admin/usuarios/nuevo', 'danger_03', TRUE);
		
		if($usuario = $this->usuario->insertar($this->input->post()))
		{
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'usuario', 'instancia' => $usuario));
			redirect('admin/usuarios', 'success_01');
		}
		else
		{
            redirect('admin/usuarios', 'danger_01');
		}
		
		redirect('admin/usuarios');
	}
	
	public function actualizar()
	{
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('privilegio'))
		){
			redirect('admin/usuario', 'danger_01', TRUE);
		}
		
		$usuario['privilegio'] = $this->input->post('privilegio');
		
		if($this->usuario->actualizar($this->input->post('id'), $usuario))
		{
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'usuario', 'instancia' => $this->input->post('id')));
			redirect('admin/usuarios', 'success_02'); 
		}
		else
		{
            redirect('admin/usuarios', 'warning_01'); 
		}		
	}
}

/* End of file usuarios.php */
/* Location: ./application/controllers/admin/usuarios.php */