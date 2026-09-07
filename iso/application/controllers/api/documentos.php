<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Documentos extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
	}

	public function index_get()
	{
		$this->load->model('documento_model', 'documento');
		$this->response($this->documento->listar_api($this->input->get('term')), 200);
		// OK 
	}
}

/* End of file documentos.php */
/* Location: ./application/controllers/api/documento.php */