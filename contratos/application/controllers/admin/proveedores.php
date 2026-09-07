<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proveedores extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
        
        $this->load->model('proveedor_model', 'proveedor');  
        $this->load->model('auditoria_model', 'auditoria');  
    }
	
	public function index()
	{
		$data['proveedores'] = $this->proveedor->listar();
			
		$this->load->view('header');
		$this->load->view('admin/proveedores/index', $data);
		$this->load->view('footer');	
	}
	
	public function editar($id = NULL)
	{
		if(empty($id)) redirect('admin/proveedores', 'danger_01', TRUE);

		if( ! $data['proveedor'] = $this->proveedor->seleccionar($id)) redirect('admin/proveedores', 'danger_02', TRUE);
        
        $this->load->view('header');
        $this->load->view('admin/proveedores/editar', $data);
        $this->load->view('footer');
	}
	
	public function actualizar()
	{
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('razon'))
		){
			redirect('admin/proveedores', 'danger_01', TRUE); 
		}
        
        if( ! $this->proveedor->seleccionar($this->input->post('id'))) redirect('admin/proveedores', 'danger_02', TRUE);
        
        $proveedor['dv'] = ($this->input->post('dv')) ? $this->input->post('dv') : 0;
        $proveedor['razon'] = $this->input->post('razon');

        if($this->proveedor->actualizar($this->input->post('id'), $proveedor))
        {
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'proveedor', 'instancia' => $this->input->post('id')));
            redirect('admin/proveedores', 'success_02'); 
        }
        else
        {
            redirect('admin/proveedores', 'warning_01');
        }
	}
}

/* End of file proveedores.php */
/* Location: ./application/controllers/proveedores.php */