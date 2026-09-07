<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH. 'libraries/REST_Controller.php'); 

class Api extends REST_Controller {
	
	public function __construct($config = 'rest')
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	    parent::__construct();
	}

	public function proveedores_get()
	{
		$this->load->model('proveedor_model', 'proveedor');

        $proveedores = $this->proveedor->listar(0, FALSE, $this->input->get('term'));
        $this->response(array_column($proveedores, 'razon'), 200);
	    // OK 
	}
	
	public function proveedor_get()
	{
		if(( ! $this->input->get('rut')) AND ( ! $this->input->get('razon')))
        {  
            $this->response(NULL, 400);
            // BAD REQUEST  
        }
        
        $this->load->model('proveedor_model', 'proveedor');
        
        if($this->input->get('rut'))
        {
            if($proveedor = $this->proveedor->comprobar($this->input->get('rut')))
            {
                $this->response($proveedor);
                // OK 
            }
            else
            {
                $this->response(NULL, 204);
                //NO CONTENT
            }
        }
    
        if($this->input->get('razon'))
        {
            if($proveedor = $this->proveedor->seleccionar_por_razon($this->input->get('razon')))
            {
                $this->response($proveedor);
                // OK 
            }
            else
            {
                $this->response(NULL, 204);
                //NO CONTENT
            }
        }
	}
}

/* End of file api.php */
/* Location: ./application/controllers/api.php */