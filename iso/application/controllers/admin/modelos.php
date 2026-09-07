<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelos extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
		
		$this->load->model('modelo_model', 'modelo');
		$this->load->model('auditoria_model', 'auditoria');
    }
	
	public function index()
	{		
		$data['modelos'] = $this->modelo->listar();
			
		$this->load->view('header');
		$this->load->view('admin/modelos/index', $data);
		$this->load->view('footer');
	}
	
	public function nuevo()
	{	
		$this->load->model('modelo_model', 'modelo');
		$data['modelos'] = $this->modelo->listar();
		
		$this->load->view('header');
		$this->load->view('admin/modelos/nuevo', $data);
		$this->load->view('footer');
	}
	
	public function grabar()
	{			
		if( ! $this->input->post('nombre')) redirect('admin/modelos/nuevo', 'danger_01', TRUE);

        $modelo['nombre'] = $this->input->post('nombre');

		if($modelo = $this->modelo->insertar($modelo))
		{
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'modelo', 'instancia' => $modelo));
			redirect('admin/modelos', 'success_01');
		}
	}
	
	public function editar($id = NULL)
	{	
		if(empty($id)) redirect('admin/modelos', 'danger_01');

		if($data['modelo'] = $this->modelo->seleccionar($id))
		{
			$this->load->view('header');
			$this->load->view('admin/modelos/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('admin/modelos', 'danger_02');
		}
	}
	
	public function actualizar()
	{	
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('nombre'))
		){
			redirect('admin/modelos', 'danger_01', TRUE);
		}

		if($this->modelo->seleccionar($this->input->post('id')))
		{
			$modelo['nombre'] = $this->input->post('nombre');

			if($this->modelo->actualizar($this->input->post('id'), $modelo))
			{
				$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'modelo', 'instancia' => $this->input->post('id')));
				redirect('admin/modelos', 'success_02');
			}
			else
			{
				redirect('admin/modelos', 'warning_01');
			}
		}
		else
		{
			redirect('admin/modelos', 'danger_02');
		}
	}
	
	public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/modelos', 'danger_01');

		if( ! $this->modelo->seleccionar($id)) redirect('admin/modelos', 'danger_02');
		
        if($this->modelo->eliminar($id))
        {
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'modelo', 'instancia' => $id));
			redirect('admin/modelos', 'success_03');
		}
        else
        {
            redirect('admin/modelos', 'danger_05');
        }
	}
    
    public function activo($id = NULL)
	{
		if(empty($id)) redirect('admin/modelos', 'danger_01');
	
		if($modelo = $this->modelo->seleccionar($id))
		{
			$modelo['activo'] = ($modelo['activo'] == 1) ? '0' : '1';
			
			$this->modelo->actualizar($id, $modelo);
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'modelo', 'instancia' => $id));
			redirect('admin/modelos', 'success_02');				
		}
		else
		{
			redirect('admin/modelos', 'danger_02');
		}
	}
}

/* End of file modelos.php */
/* Location: ./application/controllers/admin/modelos.php */