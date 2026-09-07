<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grupos extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
		
		$this->load->model('grupo_model', 'grupo');
		$this->load->model('auditoria_model', 'auditoria');
    }
	
	public function index()
	{		
		$data['grupos'] = $this->grupo->listar();
			
		$this->load->view('header');
		$this->load->view('admin/grupos/index', $data);
		$this->load->view('footer');
	}
	
	public function nuevo()
	{	
		$this->load->model('modelo_model', 'modelo');
		$data['modelos'] = $this->modelo->listar();
		
		$this->load->view('header');
		$this->load->view('admin/grupos/nuevo', $data);
		$this->load->view('footer');
	}
	
	public function grabar()
	{			
		if(
			( ! $this->input->post('nombre')) OR
			( ! $this->input->post('modelo'))
		){
			redirect('admin/grupos/nuevo', 'danger_01', TRUE);
		}

        $grupo['nombre'] = $this->input->post('nombre');
        $grupo['modelo'] = $this->input->post('modelo');
		$grupo['descripcion'] = ($this->input->post('descripcion')) ? $this->input->post('descripcion') : NULL;

		if($grupo = $this->grupo->insertar($grupo))
		{
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'grupo', 'instancia' => $grupo));
			redirect('admin/grupos', 'success_01');
		}
	}
	
	public function editar($id = NULL)
	{	
		if(empty($id)) redirect('admin/grupos', 'danger_01');

		if($data['grupo'] = $this->grupo->seleccionar($id))
		{
			$this->load->model('modelo_model', 'modelo');
			$data['modelos'] = $this->modelo->listar();

			$this->load->view('header');
			$this->load->view('admin/grupos/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('admin/grupos', 'danger_02');
		}
	}
	
	public function actualizar()
	{	
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('nombre')) OR
			( ! $this->input->post('modelo'))
		){
			redirect('admin/grupos', 'danger_01', TRUE);
		}

		if($this->grupo->seleccionar($this->input->post('id')))
		{
			$grupo['nombre'] = $this->input->post('nombre');
			$grupo['modelo'] = $this->input->post('modelo');
			$grupo['descripcion'] = ($this->input->post('descripcion')) ? $this->input->post('descripcion') : NULL;

			if($this->grupo->actualizar($this->input->post('id'), $grupo))
			{
				$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'grupo', 'instancia' => $this->input->post('id')));
				redirect('admin/grupos', 'success_02');
			}
			else
			{
				redirect('admin/grupos', 'warning_01');
			}
		}
		else
		{
			redirect('admin/grupos', 'danger_02');
		}
	}
	
	public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/grupos', 'danger_01');

		if( ! $this->grupo->seleccionar($id)) redirect('admin/grupos', 'danger_02');
		
        if($this->grupo->eliminar($id))
        {
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'grupo', 'instancia' => $id));
			redirect('admin/grupos', 'success_03');
		}
        else
        {
            redirect('admin/grupos', 'danger_05');
        }
	}
}

/* End of file grupos.php */
/* Location: ./application/controllers/admin/grupos.php */