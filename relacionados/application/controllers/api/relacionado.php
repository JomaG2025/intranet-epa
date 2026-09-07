<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Relacionado extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
        
        $this->load->model('relacionado_model', 'relacionado');
	}
	
	public function index_get()
	{
		if( ! $this->uri->segment(3))
		{  
            $this->response(NULL, 400);
	        //BAD REQUEST
        }

        if($relacionado = $this->relacionado->verificar(str_replace('.', '', $this->uri->segment(3)), TRUE))
        {
        	$this->response($relacionado, 200);
			//OK
		}
		else
		{
			$this->response(NULL, 404);
			//NO CONTENT
		}
	}
}

/* End of file relacionado.php */
/* Location: ./application/controllers/api/relacionado.php */