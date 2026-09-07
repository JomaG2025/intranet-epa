<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Informesprensa extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
        
        $this->load->model('informeprensa_model', 'informeprensa');
	}
    
    public function index_get()
	{
        $informesprensa = $this->informeprensa->listar_api($this->input->get('year'), $this->input->get('month'));
        $this->response($informesprensa, 200);
	    // OK 
	}
}

/* End of file usuarios.php */
/* Location: ./application/controllers/api/usuarios.php */