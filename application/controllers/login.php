<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        $this->load->model('login_model', 'login');
        $this->load->model('usuario_model', 'usuario');
        $this->load->model('password_model', 'password');
        $this->load->model('auditoria_model', 'auditoria');
    }
	
	public function index()
	{   
		if($this->session->userdata('id')) redirect('inicio', 'info_01', TRUE);
		$this->load->view('login/index');
	}
	
	public function autentificar()
	{
		if($this->session->userdata('id')) redirect('inicio', 'info_01');
		if(( ! $this->input->post('usuario')) OR ( ! $this->input->post('password'))) redirect('login', 'danger_01');

		if( ! $usuario = $this->usuario->verificar(str_replace('@puertoarica.cl', '', $this->input->post('usuario')))) redirect('login', 'danger_02');
        if( ! $usuario['activo']) redirect('login', 'danger_11');
        if( ! $this->login->autentificar(str_replace('@puertoarica.cl', '', $this->input->post('usuario')), $this->input->post('password'))) redirect('login', 'danger_07');
        
        if($usuario['primer_inicio'])
        {
            $this->session->set_userdata('primer_inicio', $usuario['id']);
            redirect('login/primer_inicio');
        }
        else
        {
            $this->session->set_userdata('id', $usuario['id']);
            $this->session->set_userdata('nombre', $usuario['nombre']);
            $this->session->set_userdata('usuario', $usuario['usuario']);
            $this->session->set_userdata('privilegio', $usuario['privilegio']);
            $this->session->set_userdata('sexo', $usuario['sexo']);
        }
        
        $this->auditoria->insertar(array('evento' => 'LGN', 'recurso' => 'login', 'instancia' => $usuario['id']));
        redirect('');
	}
    
    public function primer_inicio()
    {
        if( ! $this->session->userdata('primer_inicio')) redirect('login', 'danger_06');
        $this->load->view('login/primer_inicio');
    }
    
    public function primer_cambio_password()
    {
        if( ! $this->session->userdata('primer_inicio')) redirect('login', 'danger_06');
        if( ! $usuario = $this->usuario->seleccionar($this->session->userdata('primer_inicio'))) redirect('login/salir');
        if( ! $usuario['primer_inicio']) redirect('login/salir');
        if( ! $this->input->post('password')) redirect('login/primer_inicio', 'danger_01', TRUE);
        if($this->input->post('password') != $this->input->post('password2')) redirect('login/primer_inicio', 'danger_08', TRUE);
        if(( ! preg_match('/[a-zA-Z0-9]{8,}/', $this->input->post('password'))) OR (preg_match_all('/[0-9]/', $this->input->post('password'), $a) == 0) OR (preg_match_all('/[a-zA-Z]/', $this->input->post('password'), $a) == 0)) redirect('login/primer_inicio', 'danger_09', TRUE);
        if($this->password->verificar($usuario['usuario'], $this->input->post('password'))) redirect('login/primer_inicio', 'danger_12', TRUE);
    
        $this->usuario->actualizar($usuario['id'], array('password' => $this->input->post('password'), 'primer_inicio' => 0));
        $this->password->insertar(array('usuario' => $usuario['usuario'], 'password' => $this->input->post('password')));
        $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'usuario', 'instancia' => $usuario['id']));
        $this->session->unset_userdata('primer_inicio');
        redirect('login', 'success_05'); 
    }
	
	public function password()
	{        
		if( ! $this->session->userdata('id')) redirect('login', 'danger_06');
		if( ! $this->input->post('password')) redirect('', 'danger_01', TRUE);
		if($this->input->post('password') != $this->input->post('password2')) redirect('', 'danger_08', TRUE);
		if(( ! preg_match('/[a-zA-Z0-9]{8,}/', $this->input->post('password'))) OR (preg_match_all('/[0-9]/', $this->input->post('password'), $a) == 0) OR (preg_match_all('/[a-zA-Z]/', $this->input->post('password'), $a) == 0)) redirect('', 'danger_09', TRUE);
        if($this->password->verificar($this->session->userdata('usuario'), $this->input->post('password'))) redirect('', 'danger_12', TRUE);
        
		if($this->usuario->actualizar($this->session->userdata('id'), array('password' => $this->input->post('password'))))
        {
            $this->password->insertar(array('usuario' => $this->session->userdata('usuario'), 'password' => $this->input->post('password')));
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'usuario', 'instancia' => $this->session->userdata('id')));
            redirect('', 'success_04', TRUE);
        }
		else
        {
            redirect('', 'warning_01', TRUE);
        } 
	}
	
	public function salir()
	{	
		if($this->session->userdata('id')) $this->auditoria->insertar(array('evento' => 'LGO', 'recurso' => 'login', 'instancia' => $this->session->userdata('id')));
		$this->login->salir();

   		redirect('login');
   	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */