<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Usuario extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
        
        $this->load->model('usuario_model', 'usuario'); 
	}
    
    public function index_get()
	{
        if(( ! $this->get('rut')) AND ( ! $this->uri->segment(3))) $this->response(NULL, 400); // BAD REQUEST
            
        if($this->get('rut'))
        {
        	if($usuario = $this->usuario->seleccionar_por_rut($this->get('rut')))
            {
                unset($usuario['creado'],
                      $usuario['modificado'],
                      $usuario['id'], 
                      $usuario['privilegio'], 
                      $usuario['rut'], 
                      $usuario['dv'], 
                      $usuario['password'], 
                      $usuario['primer_inicio']
                );
                
                $this->response($usuario, 200); // OK 
            }
        	else $this->response(NULL, 204); //NO CONTENT
        }
        else if($this->uri->segment(3))
        {
	        if($usuario = $this->usuario->verificar($this->uri->segment(3)))
            {
                unset($usuario['creado'],
                      $usuario['modificado'],
                      $usuario['id'], 
                      $usuario['privilegio'], 
                      $usuario['rut'], 
                      $usuario['dv'], 
                      $usuario['password'], 
                      $usuario['primer_inicio']
                );
                
                $this->response($usuario, 200); // OK 
            }
        	else $this->response(NULL, 204); //NO CONTENT
        }
	}
}

/* End of file usuario.php */
/* Location: ./application/controllers/api/usuario.php */