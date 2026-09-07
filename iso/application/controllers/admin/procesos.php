<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Procesos extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');

        $this->load->model('proceso_model', 'proceso');
		$this->load->model('auditoria_model', 'auditoria');
    }
	
	public function index()
	{		
		$data['procesos'] = $this->proceso->listar();
			
		$this->load->view('header');
		$this->load->view('admin/procesos/index', $data);
		$this->load->view('footer');
	}
	
	public function nuevo()
	{
		$this->load->model('grupo_model', 'grupo');
		$data['grupos'] = $this->grupo->listar();
		
		$this->load->view('header');
		$this->load->view('admin/procesos/nuevo', $data);
		$this->load->view('footer');
	}
	
	public function grabar()
	{			
		if(
			( ! $this->input->post('nombre')) OR
			( ! $this->input->post('grupo'))
		){
			redirect('admin/procesos/nuevo', 'danger_01', TRUE);
		}

        $proceso['nombre'] = $this->input->post('nombre');
        $proceso['grupo'] = $this->input->post('grupo');
		$proceso['codigo'] = ($this->input->post('codigo')) ? $this->input->post('codigo') : NULL;
		$proceso['descripcion'] = ($this->input->post('descripcion')) ? $this->input->post('descripcion') : NULL;

		if($proceso = $this->proceso->insertar($proceso))
		{
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'proceso', 'instancia' => $proceso));
			redirect('admin/procesos', 'success_01');
		}
	}
	
	public function editar($id = NULL)
	{	
		if(empty($id)) redirect('admin/procesos', 'danger_01');

		if($data['proceso'] = $this->proceso->seleccionar($id))
		{
			$this->load->model('grupo_model', 'grupo');
			$data['grupos'] = $this->grupo->listar();
            
			$this->load->view('header');
			$this->load->view('admin/procesos/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('admin/procesos', 'danger_02', TRUE);
		}
	}
	
	public function actualizar()
	{	
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('nombre')) OR
			( ! $this->input->post('grupo'))
		){
			redirect('admin/procesos', 'danger_01', TRUE);
		}

		if($this->proceso->seleccionar($this->input->post('id')))
		{
			$proceso['nombre'] = $this->input->post('nombre');
			$proceso['grupo'] = $this->input->post('grupo');
			$proceso['codigo'] = ($this->input->post('codigo')) ? $this->input->post('codigo') : NULL;
            $proceso['descripcion'] = ($this->input->post('descripcion')) ? $this->input->post('descripcion') : NULL;

			if($this->proceso->actualizar($this->input->post('id'), $proceso))
			{
				$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'proceso', 'instancia' => $this->input->post('id')));
				redirect('admin/procesos', 'success_02');
			}
			else
			{
				redirect('admin/procesos', 'warning_01');
			}

		}
		else
		{
			redirect('admin/procesos', 'danger_02');
		}
	}
	
	public function activo($id = NULL)
	{	
		if(empty($id)) redirect('admin/procesos', 'danger_01');

		if($proceso = $this->proceso->seleccionar($id))
		{
			$this->proceso->actualizar($id, array('activo' => ($proceso['activo'] == 1) ? '0' : '1'));
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'proceso', 'instancia' => $id));
			redirect('admin/procesos', 'success_02');
		}
		else
		{
			redirect('admin/procesos', 'danger_02');
		}
	}
	
	public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/procesos', 'danger_01');

		if( ! $this->proceso->seleccionar($id)) redirect('admin/procesos', 'danger_02');
        
        $this->load->model('documento_model', 'documento');
        $documentos = $this->documento->listar($id);
		
        if($this->proceso->eliminar($id))
        {
            foreach($documentos as $documento)
            {
                if(is_file($documento['full_path'])) unlink($documento['full_path']);
                if(is_file($documento['full_path'].'.nocontrolado')) unlink($documento['full_path'].'.nocontrolado');
            }
            
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'proceso', 'instancia' => $id));
			redirect('admin/procesos', 'success_03');
		}
        else
        {
            redirect('admin/procesos', 'danger_05');
        }
	}
}

/* End of file procesos.php */
/* Location: ./application/controllers/admin/procesos.php */