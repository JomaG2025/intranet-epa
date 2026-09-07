<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Periodos extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
        
        $this->load->model('periodo_model', 'periodo');  
        $this->load->model('auditoria_model', 'auditoria');  
    }
	
	public function index()
	{
		$data['periodos'] = $this->periodo->listar();
			
		$this->load->view('header');
		$this->load->view('admin/periodos/index', $data);
		$this->load->view('footer');
	}
    
    public function nuevo()
	{
		$this->load->view('header');
		$this->load->view('admin/periodos/nuevo');
		$this->load->view('footer');
	}
    
    public function grabar()
	{
		if(
			( ! $this->input->post('periodo')) OR
			( ! $this->input->post('ponderacionobjetivoarea')) OR
			( ! $this->input->post('ponderacionobjetivoindividual'))
		){
            redirect('admin/periodos/nuevo', 'danger_01', TRUE);
		}
        
        if(($this->input->post('ponderacionobjetivoarea') + $this->input->post('ponderacionobjetivoindividual')) != 100) redirect('admin/periodos/nuevo', 'danger_01', TRUE);
		
		if($this->periodo->verificar($this->input->post('periodo'))) redirect('admin/periodos/nuevo', 'danger_03', TRUE);
		
		if($periodo = $this->periodo->insertar($this->input->post()))
		{
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'periodo', 'instancia' => $periodo));
			redirect('admin/periodos', 'success_01');
		}
		else
		{
            redirect('admin/periodos', 'danger_01');
		}
	}
    
    public function editar($id = NULL)
	{
		if(empty($id)) redirect('admin/periodos', 'danger_01', TRUE);	

		if($data['periodo'] = $this->periodo->seleccionar($id))
		{
			$this->load->view('header');
			$this->load->view('admin/periodos/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('admin/periodos', 'danger_02', TRUE);
		}
	}
    
    public function actualizar()
	{
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('ponderacionobjetivoarea')) OR
			( ! $this->input->post('ponderacionobjetivoindividual'))
		){
            redirect('admin/periodos', 'danger_01', TRUE);
		}
        
        if( ! $this->periodo->seleccionar($this->input->post('id'))) redirect('admin/periodos', 'danger_02', TRUE);
        
        if(($this->input->post('ponderacionobjetivoarea') + $this->input->post('ponderacionobjetivoindividual')) != 100) redirect('admin/periodos/editar/' . $this->input->post('id'), 'danger_01');
        
        $periodo['ponderacionobjetivoarea'] = $this->input->post('ponderacionobjetivoarea');
        $periodo['ponderacionobjetivoindividual'] = $this->input->post('ponderacionobjetivoindividual');
		
		if($this->periodo->actualizar($this->input->post('id'), $periodo))
		{
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'periodo', 'instancia' => $this->input->post('id')));
            redirect('admin/periodos', 'success_02');
		}
		else
		{
            redirect('admin/periodos', 'warning_01');
		}
	}
    
    public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/periodos', 'danger_01', TRUE);
		
		if( ! $this->periodo->seleccionar($id)) redirect('admin/periodos', 'danger_02');
		
		if($this->periodo->eliminar($id))
		{
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'periodo', 'instancia' => $id));
			redirect('admin/periodos', 'success_03');
		}
        else
        {
            redirect('admin/periodos', 'danger_05');
        }
	}
}

/* End of file periodos.php */
/* Location: ./application/controllers/admin/periodos.php */