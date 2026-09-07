<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formularios extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
        
        $this->load->model('formulario_model', 'formulario');
        $this->load->model('estadoformulario_model', 'estadoformulario');
        $this->load->model('objetivoarea_model', 'objetivoarea');  
        $this->load->model('objetivoindividual_model', 'objetivoindividual');  
        $this->load->model('capacitacion_model', 'capacitacion');  
        $this->load->model('usuario_model', 'usuario');  
        $this->load->model('auditoria_model', 'auditoria');  
    }
	
	public function index()
	{	        
		$data['formularios'] = $this->formulario->listar();
			
		$this->load->view('header');
		$this->load->view('admin/formularios/index', $data);
		$this->load->view('footer');
	}
    
    public function editar($id = NULL)
	{
        $this->load->model('evaluado_model', 'evaluado');
        $this->load->model('tiponivel_model', 'tiponivel');
        $this->load->model('tipocontrato_model', 'tipocontrato');
        $this->load->model('objetivoarea_model', 'objetivoarea');
        $this->load->model('objetivoindividual_model', 'objetivoindividual');
        $this->load->model('capacitacion_model', 'capacitacion');        
        
		if(empty($id)) redirect('admin/formularios', 'danger_01', TRUE);

		if($data['formulario'] = $this->formulario->seleccionar($id))
		{
            $data['evaluadores'] = $this->evaluado->listar_evaluadores($data['formulario']['usuario'], $data['formulario']['periodo']);
            $data['estadoformularios'] = $this->estadoformulario->listar();
            $data['tipocontratos'] = $this->tipocontrato->listar();
            $data['tiponiveles'] = $this->tiponivel->listar();
            $data['objetivosarea'] = $this->objetivoarea->listar($data['formulario']['id']);
            $data['objetivosindividuales']['transversales'] = $this->objetivoindividual->listar($data['formulario']['id'], TRUE);
            $data['objetivosindividuales']['notransversales'] = $this->objetivoindividual->listar($data['formulario']['id'], FALSE);
            $data['capacitaciones'] = $this->capacitacion->listar($data['formulario']['id']);

            foreach($data['evaluadores'] as $key => $evaluador)
            {
                $data['evaluadores'][$key] = $this->usuario->seleccionar_intranet($evaluador['usuario']);
            }	
		
			$this->load->view('header');
			$this->load->view('admin/formularios/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('admin/formularios', 'danger_02', TRUE);
		}
	}
    
    public function actualizar()
    {
        if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('nombre')) OR
			( ! $this->input->post('estadoformulario'))
		){
			redirect('admin/formularios', 'danger_01');
		}
        
        if( ! $this->formulario->seleccionar($this->input->post('id'))) redirect('admin/formularios', 'danger_02', TRUE);
        
        if( ! $this->estadoformulario->verificar($this->input->post('estadoformulario'))) redirect('admin/formularios', 'danger_01', TRUE);
        
        $formulario['nombre'] = $this->input->post('nombre');
        $formulario['estadoformulario'] = $this->input->post('estadoformulario');
        $formulario['tipocontrato'] = ($this->input->post('tipocontrato')) ? $this->input->post('tipocontrato') : NULL;
        $formulario['tiponivel'] = ($this->input->post('tiponivel')) ? $this->input->post('tiponivel') : NULL;
        
        if($this->formulario->actualizar($this->input->post('id'), $formulario))
        {
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'formulario', 'instancia' => $this->input->post('id')));
            redirect('admin/formularios', 'success_02');
        }
        else
        {
            redirect('admin/formularios', 'warning_01');
        }
    }
	
    public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/formularios', 'danger_01', TRUE);
		
		if( ! $this->formulario->seleccionar($id)) redirect('admin/formularios', 'danger_02', TRUE);
        
        $objetivosarea = $this->objetivoarea->listar($id);
        $objetivosindividuales = $this->objetivoindividual->listar($id);
        $capacitaciones = $this->capacitacion->listar($id);
        
        foreach($objetivosarea as $objetivoarea)
        {
            if($this->objetivoarea->eliminar($objetivoarea['id'])) $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'objetivoarea', 'instancia' => $objetivoarea['id']));
        }
        
        foreach($objetivosindividuales as $objetivoindividual)
        {
            if($this->objetivoindividual->eliminar($objetivoindividual['id'])) $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'objetivoindividual', 'instancia' => $objetivoindividual['id']));
        }
        
        foreach($capacitaciones as $capacitacion)
        {
            if($this->capacitacion->eliminar($capacitacion['id'])) $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'capacitacion', 'instancia' => $capacitacion['id']));
        }
		
		if($this->formulario->eliminar($id))
		{
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'formulario', 'instancia' => $id));
            redirect('admin/formularios', 'success_03');
		}
        else
        {
            redirect('admin/formularios', 'danger_05');
        }
	}	
}

/* End of file formularios.php */
/* Location: ./application/controllers/admin/formularios.php */