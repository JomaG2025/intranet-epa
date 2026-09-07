<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Etapa extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
        
        $this->load->model('etapa_model', 'etapa');
	}

	public function verificar_get($periodo = NULL, $limite = NULL)
	{
        if(
            (empty($periodo)) OR
            (empty($limite))
        ){
             $this->response(NULL, 400);
            // BAD REQUEST     
        }
        
        if($etapa = $this->etapa->verificar($periodo, $limite)) $this->response($etapa, 200); // OK 
        else $this->response(NULL, 204); //NO CONTENT        
	}
}

/* End of file etapa.php */
/* Location: ./application/controllers/api/etapa.php */