<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
    }

	public function index()
	{        
		$this->load->view('header');
		$this->load->view('inicio/index');
		$this->load->view('footer');
	}
}

/* End of file inicio.php */
/* Location: ./application/controllers/inicio.php */