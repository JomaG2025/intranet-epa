<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Evaluador extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
        
        $this->load->model('evaluador_model', 'evaluador');
	}

	public function verificar_get($periodo = NULL, $usuario = NULL)
	{
        if(
            (empty($periodo)) OR
            (empty($usuario))
        ){
             $this->response(NULL, 400);
            // BAD REQUEST     
        }
        
        if($evaluador = $this->evaluador->verificar($periodo, $usuario)) $this->response($evaluador, 200); // OK 
        else $this->response(NULL, 204); //NO CONTENT     
	}
}

/* End of file evaluador.php */
/* Location: ./application/controllers/api/evaluador.php */