<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Usuarios extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
        
        $this->load->model('usuario_model', 'usuario'); 
	}
    
    public function index_get($usuario = NULL)
	{
        $usuarios = $this->usuario->listar_rest();
        $this->response($usuarios, 200);
	    // OK 
	}
}

/* End of file usuarios.php */
/* Location: ./application/controllers/api/usuarios.php */