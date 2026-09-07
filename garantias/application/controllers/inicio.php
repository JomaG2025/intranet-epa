<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        if( ! $this->session->userdata('id')) redirect('login');
    }
	
	public function index()
	{	
        $this->load->model('garantia_model', 'garantia');
        $this->session->set_userdata('referrer', 'inicio');
        
        $data['ultimas'] = $this->garantia->listar(0, 5);
        $data['vencidas'] = $this->garantia->listar(0, 5, 'VEN');
        $data['devueltas'] = $this->garantia->listar(0, 5, 'DEV');
        $data['cobradas'] = $this->garantia->listar(0, 5, 'COB');
			
		$this->load->view('header');
		$this->load->view('inicio/index', $data);
		$this->load->view('footer');
	}
}

/* End of file inicio.php */
/* Location: ./application/controllers/inicio.php */