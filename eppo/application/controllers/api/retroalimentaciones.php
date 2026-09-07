<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Retroalimentaciones extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
        
        $this->load->model('retroalimentacion_model', 'retroalimentacion');
        $this->load->model('usuario_model', 'usuario'); 
        $this->load->model('auditoria_model', 'auditoria'); 
	}

	public function index_get()
	{
        if(
            ( ! $this->uri->segment(3)) OR
            ( ! $this->uri->segment(4))
        ){
             $this->response(NULL, 400);
            // BAD REQUEST     
        }
        
        if($retroalimentaciones = $this->retroalimentacion->listar($this->uri->segment(3), $this->uri->segment(4)))
        {
            $usuarios = array();
            
            foreach($retroalimentaciones as $key => $retroalimentacion)
            {
                $retroalimentaciones[$key]['creado'] = formato_fecha_hora($retroalimentacion['creado']);
                
                if( ! array_key_exists($retroalimentacion['usuario'], $usuarios))
                {
                    $retroalimentaciones[$key]['usuario'] = $usuarios[$retroalimentacion['usuario']] = $this->usuario->seleccionar_intranet( $retroalimentacion['usuario']);
                }
                else
                {
                    $retroalimentaciones[$key]['usuario'] = $usuarios[$retroalimentacion['usuario']];
                }
            }
            
            $this->response($retroalimentaciones, 200);
            // OK 
        }
        else
        {
            $this->response(NULL, 204);
            //NO CONTENT
        } 
	}
}

/* End of file retroalimentaciones.php */
/* Location: ./application/controllers/api/retroalimentaciones.php */