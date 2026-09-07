<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relacionados extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if(!$this->session->userdata('id'))
		{
			redirect('login');
		}
		
		$this->load->model('relacionado_model', 'relacionado');
		$this->load->model('auditoria_model', 'auditoria');
    }
	
	public function index()
	{
		$data['relacionados'] = $this->relacionado->listar();
			
		$this->load->view('header');
		$this->load->view('relacionados/index', $data);
		$this->load->view('footer');
	}
    
    public function nuevo()
	{
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('relacionados', 'danger_06', TRUE);
		}
		
		$data['tipos'] = $this->relacionado->listar_tipo();
        $data['funcionarios'] = $this->relacionado->listar(TRUE);	
		
		$this->load->view('header');
		$this->load->view('relacionados/nuevo', $data);
		$this->load->view('footer');
	}
    
    public function grabar()
	{
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('relacionados', 'danger_06', TRUE);
		}
		
		if(
			( ! $this->input->post('rut')) OR 
			( ! $this->input->post('nombre')) OR 
			( ! $this->input->post('tipo')) 
        ){
            redirect('relacionados/nuevo', 'danger_01', TRUE);
        }
        
        if($this->relacionado->verificar($this->input->post('rut'))) redirect('relacionados/nuevo', 'danger_03', TRUE);
        
        $relacionado['rut'] = str_replace('.', '', $this->input->post('rut'));
        $relacionado['dv'] = ($this->input->post('dv')) ? $this->input->post('dv') : 0;
        $relacionado['nombre'] = $this->input->post('nombre');
        $relacionado['tipo'] = $this->input->post('tipo');
        $relacionado['observacion'] = ($this->input->post('observacion')) ? $this->input->post('observacion') : NULL;
        $relacionado['relacionado'] = ($this->input->post('relacionado')) ? $this->input->post('relacionado') : NULL;
        
        if($relacionado['relacionado'])
        {            
            if($this->relacionado->verificar($this->input->post('relacionado')))
            {
                if($relacionado['tipo'] == 'FUN') $relacionado['relacionado'] = NULL;
                if($relacionado['relacionado'] == $relacionado['rut']) $relacionado['relacionado'] = NULL;
            }
            else
            {
                $relacionado['relacionado'] = NULL;
            }
        }
		
		if($relacionado = $this->relacionado->insertar($relacionado))
		{
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'relacionado', 'instancia' => $relacionado));
			redirect('relacionados', 'success_01');
		}
		else
		{
			redirect('relacionados/nuevo', 'danger_01');
        }
	}
    
    public function editar($id = NULL)
	{
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('relacionados', 'danger_06', TRUE);
		}
		
		if(empty($id)) redirect('relacionados', 'danger_01', TRUE);
		if( ! $data['relacionado'] = $this->relacionado->seleccionar($id)) redirect('relacionados', 'danger_02', TRUE);
		
        $data['tipos'] = $this->relacionado->listar_tipo();	
        $data['funcionarios'] = $this->relacionado->listar(TRUE);	
        
        $this->load->view('header');
		$this->load->view('relacionados/editar', $data);
		$this->load->view('footer');
	}
    
    public function actualizar()
	{
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('relacionados', 'danger_06', TRUE);
		}
		
		if(
			( ! $this->input->post('id')) OR 
			( ! $this->input->post('nombre')) OR 
			( ! $this->input->post('tipo')) 
        ){
            redirect('relacionados', 'danger_01', TRUE);
        }
        
        if( ! $tmp = $this->relacionado->seleccionar($this->input->post('id'))) redirect('relacionados', 'danger_02', TRUE);
        
        $relacionado['dv'] = ($this->input->post('dv')) ? $this->input->post('dv') : 0;
        $relacionado['nombre'] = $this->input->post('nombre');
        $relacionado['tipo'] = $this->input->post('tipo');
        $relacionado['observacion'] = ($this->input->post('observacion')) ? $this->input->post('observacion') : NULL;
        $relacionado['relacionado'] = ($this->input->post('relacionado')) ? $this->input->post('relacionado') : NULL;
        
        if($relacionado['relacionado'])
        {            
            if($this->relacionado->verificar($this->input->post('relacionado')))
            {
                if($relacionado['tipo'] == 'FUN') $relacionado['relacionado'] = NULL;
                if($relacionado['relacionado'] == $tmp['rut']) $relacionado['relacionado'] = NULL;
            }
            else
            {
                $relacionado['relacionado'] = NULL;
            }
        }

		if($this->relacionado->actualizar($this->input->post('id'), $relacionado))
		{
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'relacionado', 'instancia' => $this->input->post('id')));
			redirect('relacionados', 'success_02'); 
		}
		else
		{
			redirect('relacionados', 'warning_01');
		}
	}
    
    public function activo($id = NULL)
	{
		if(empty($id)) redirect('relacionados', 'danger_01', TRUE);
	
		if($relacionado = $this->relacionado->seleccionar($id))
		{
			$activo['activo'] = ($relacionado['activo'] == 1) ? '0' : '1';
			
			$this->relacionado->actualizar($id, $activo);
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'relacionado', 'instancia' => $id));
			redirect('relacionados', 'success_02', TRUE);
		}
		else
		{
			redirect('relacionados', 'danger_02', TRUE);
		}
	}
	
	public function eliminar($id = NULL)
	{
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('relacionados', 'danger_06', TRUE);
		}
		
		if(empty($id)) redirect('relacionados', 'danger_01', TRUE);
        if( ! $this->relacionado->seleccionar($id)) redirect('relacionados', 'danger_02', TRUE);
		
		$this->relacionado->eliminar($id);
		$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'relacionado', 'instancia' => $id));
		redirect('relacionados', 'success_03');
	}
}

/* End of file relacionados.php */
/* Location: ./application/controllers/relacionados.php */