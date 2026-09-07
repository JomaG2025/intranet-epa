<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        $this->load->model('login_model', 'login');
        $this->load->model('periodo_model', 'periodo');
        $this->load->model('evaluador_model', 'evaluador');
        $this->load->model('comite_model', 'comite');
        $this->load->model('auditoria_model', 'auditoria');
    }
	
	public function index()
	{   
		if($this->session->userdata('id')) redirect('');
		
		if($this->input->cookie('puertoaricaintranet'))
		{
			$cookie = unserialize($this->input->cookie('puertoaricaintranet'));
			if ($this->login->autentificar($cookie['session_id']))
			{
                $periodo_actual = $this->periodo->seleccionar_actual();
        
                if($this->evaluador->verificar($this->session->userdata('usuario'))) $this->session->set_userdata('evaluador', TRUE);
                if($this->comite->verificar($this->session->userdata('usuario'), $periodo_actual['periodo'])) $this->session->set_userdata('comite', TRUE);
                
				$this->auditoria->insertar(array('evento' => 'LGN', 'recurso' => 'login', 'instancia' => $this->session->userdata('id')));
				redirect('');
			}
		}

		$this->load->view('login/index');
	}
	
	public function salir()
	{	
		if($this->session->userdata('id'))
		{
			$this->auditoria->insertar(array('evento' => 'LGO', 'recurso' => 'login', 'instancia' => $this->session->userdata('id')));
			$this->login->salir();
		}
		
   		$this->load->view('login/salir');
   	}	
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */