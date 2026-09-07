<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aplicaciones extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
    }

	public function index()
	{		
		$this->load->view('header');
		$this->load->view('aplicaciones/index');
		$this->load->view('footer');
	}
}

/* End of file aplicacion.php */
/* Location: ./application/controllers/aplicacion.php */