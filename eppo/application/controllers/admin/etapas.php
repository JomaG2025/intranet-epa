<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Etapas extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
        
        $this->load->model('etapa_model', 'etapa');  
        $this->load->model('auditoria_model', 'auditoria');  
    }
	
	public function index()
	{	        
		$data['etapas'] = $this->etapa->listar();
			
		$this->load->view('header');
		$this->load->view('admin/etapas/index', $data);
		$this->load->view('footer');
	}
    
    public function nueva()
    {
        $this->load->model('periodo_model', 'periodo');
        
        $data['periodos'] = $this->periodo->listar();
        
        $this->load->view('header');
		$this->load->view('admin/etapas/nueva', $data);
		$this->load->view('footer');
    }
    
    public function grabar()
    {
        $this->load->model('periodo_model', 'periodo');
        
        if(
			( ! $this->input->post('periodo')) OR
			( ! $this->input->post('nombre')) OR
			( ! $this->input->post('limite'))
		){
            redirect('admin/etapas/nueva', 'danger_01', TRUE);
		}
        
        if( ! $this->periodo->verificar($this->input->post('periodo'))) redirect('admin/etapas/nueva', 'danger_01', TRUE);
        
        if($this->etapa->verificar($this->input->post('periodo'), $this->input->post('limite'))) redirect('admin/etapas/nueva', 'danger_03', TRUE);
		
		$etapa['periodo'] = $this->input->post('periodo');
		$etapa['nombre'] = $this->input->post('nombre');
		$etapa['descripcion'] = ($this->input->post('descripcion')) ? $this->input->post('descripcion') : NULL;
        $etapa['limite'] = $this->input->post('limite');
        $etapa['puedeevaluar'] = ($this->input->post('puedeevaluar')) ? $this->input->post('puedeevaluar') : 0;
        $etapa['puedeapelar'] = ($this->input->post('puedeapelar')) ? $this->input->post('puedeapelar') : 0;
		
		if($etapa = $this->etapa->insertar($etapa))
		{
            $this->etapa->actualizar_correlativos();
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'etapa', 'instancia' => $etapa));
            redirect('admin/etapas', 'success_01');
		}
		else
		{
			redirect('admin/etapas', 'danger_01');
		}
		
		    
    }
    
    public function editar($id = NULL)
	{
        $this->load->model('periodo_model', 'periodo');
        
		if(empty($id)) redirect('admin/etapas', 'danger_01', TRUE);

		if($data['etapa'] = $this->etapa->seleccionar($id))
		{
            $data['periodos'] = $this->periodo->listar();
		
			$this->load->view('header');
			$this->load->view('admin/etapas/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('admin/etapas', 'danger_02', TRUE);
		}
	}
    
    public function actualizar()
	{
        $this->load->model('periodo_model', 'periodo');
        
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('periodo')) OR
			( ! $this->input->post('nombre')) OR
			( ! $this->input->post('limite'))
		){
			redirect('admin/etapas', 'danger_01', TRUE);
		}
        
        if( ! $this->etapa->seleccionar($this->input->post('id'))) redirect('admin/etapas', 'danger_02', TRUE); 
        
        if( ! $this->periodo->verificar($this->input->post('periodo'))) redirect('admin/etapas/editar/' . $this->input->post('id'), 'danger_01');
        
        if($tmp = $this->etapa->verificar($this->input->post('periodo'), $this->input->post('limite')))
        {
            if($tmp['id'] != $this->input->post('id')) redirect('admin/etapas/editar/' . $this->input->post('id'), 'danger_03');
        }
        
        $etapa['periodo'] = $this->input->post('periodo');
		$etapa['nombre'] = $this->input->post('nombre');
		$etapa['descripcion'] = ($this->input->post('descripcion')) ? $this->input->post('descripcion') : NULL;
        $etapa['limite'] = $this->input->post('limite');
        $etapa['puedeevaluar'] = ($this->input->post('puedeevaluar')) ? $this->input->post('puedeevaluar') : 0;
        $etapa['puedeapelar'] = ($this->input->post('puedeapelar')) ? $this->input->post('puedeapelar') : 0;
		
		if($this->etapa->actualizar($this->input->post('id'), $etapa))
		{
            $this->etapa->actualizar_correlativos();
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'etapa', 'instancia' => $this->input->post('id')));
            redirect('admin/etapas', 'success_02');
		}
		else
		{
			redirect('admin/etapas', 'warning_01');
		}		
	}
	
    public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/etapas', 'danger_01', TRUE);
		
		if( ! $this->etapa->seleccionar($id)) redirect('admin/etapas', 'danger_02', TRUE);
		
		if($this->etapa->eliminar($id))
		{
            $this->etapa->actualizar_correlativos();
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'etapa', 'instancia' => $id));
            redirect('admin/etapas', 'success_03');
		}
        else
        {
            redirect('admin/etapas', 'danger_05');
        }
        
        
	}	
}

/* End of file etapas.php */
/* Location: ./application/controllers/admin/etapas.php */