<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluadores extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
        
        $this->load->model('evaluador_model', 'evaluador');  
        $this->load->model('evaluado_model', 'evaluado');  
        $this->load->model('auditoria_model', 'auditoria');  
    }
	
	public function index()
	{	        
		$data['evaluadores'] = $this->evaluador->listar();
			
		$this->load->view('header');
		$this->load->view('admin/evaluadores/index', $data);
		$this->load->view('footer');
	}
    
    public function nuevo()
    {
        $this->load->model('periodo_model', 'periodo');
        $this->load->model('usuario_model', 'usuario');
        
        $data['periodos'] = $this->periodo->listar();
        $data['usuarios'] = $this->_listar_usuarios();
        
        $this->load->view('header');
		$this->load->view('admin/evaluadores/nuevo', $data);
		$this->load->view('footer');
    }
    
    public function grabar()
    {
        $this->load->model('periodo_model', 'periodo');
        $this->load->model('usuario_model', 'usuario');
        
        if(
			( ! $this->input->post('periodo')) OR
			( ! $this->input->post('usuario'))
		){
            redirect('admin/evaluadores/nuevo', 'danger_01', TRUE);
		}
        
        if( ! $this->periodo->verificar($this->input->post('periodo'))) redirect('admin/evaluadores/nuevo', 'danger_01', TRUE);	
        
        if( ! $this->usuario->verificar($this->input->post('usuario'))) redirect('admin/evaluadores/nuevo', 'danger_01', TRUE);
        
        if($this->evaluador->verificar($this->input->post('usuario'), $this->input->post('periodo'))) redirect('admin/evaluadores/nuevo', 'danger_03', TRUE);
		
		$evaluador['periodo'] = $this->input->post('periodo');
		$evaluador['usuario'] = $this->input->post('usuario');

		if($evaluador = $this->evaluador->insertar($evaluador))
		{
            if($this->input->post('evaluados'))
            {
                foreach($this->input->post('evaluados') as $evaluado)
                {
                    if($evaluado != $this->input->post('usuario'))
                    {
                        if($evaluado = $this->evaluado->insertar(array('evaluador' => $evaluador, 'usuario' => $evaluado))) $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'evaluado', 'instancia' => $evaluado));
                    }
                }
            }
            
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'evaluador', 'instancia' => $evaluador));
            redirect('admin/evaluadores', 'success_01');
		}
		else
		{
            redirect('admin/evaluadores', 'danger_01');
		}
    }
    
    public function editar($id = NULL)
	{
        $this->load->model('periodo_model', 'periodo');
        $this->load->model('usuario_model', 'usuario');
        
		if(empty($id)) redirect('admin/evaluadores', 'danger_01', TRUE);
        
		if($data['evaluador'] = $this->evaluador->seleccionar($id))
		{
            $data['periodos'] = $this->periodo->listar();
            $data['evaluados'] = $this->evaluado->listar($id);
            $data['usuarios'] = $this->_listar_usuarios();
		
			$this->load->view('header');
			$this->load->view('admin/evaluadores/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('admin/evaluadores', 'danger_02', TRUE);
		}
	}
    
    public function actualizar()
	{
        $this->load->model('periodo_model', 'periodo');
        
		if( ! $this->input->post('id')) redirect('admin/evaluadores', 'danger_01', TRUE);
        
        if( ! $evaluador = $this->evaluador->seleccionar($this->input->post('id'))) redirect('admin/evaluadores', 'danger_02', TRUE);  
    
        $this->evaluado->eliminar_por_evaluador($this->input->post('id'));
        
        foreach($this->input->post('evaluados') as $evaluado)
        {
            if($evaluado != $evaluador['usuario']) $this->evaluado->insertar(array('evaluador' => $this->input->post('id'), 'usuario' => $evaluado));
        }
        
        $this->evaluador->actualizar($this->input->post('id'), array(), TRUE);
        $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'evaluador', 'instancia' => $this->input->post('id')));
		
		redirect('admin/evaluadores', 'success_02');
	}
	
    public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/evaluadores', 'danger_01', TRUE);
		
		if( ! $this->evaluador->seleccionar($id)) redirect('admin/evaluadores', 'danger_02');
		
		if($this->evaluador->eliminar($id))
		{
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'evaluador', 'instancia' => $id));
			redirect('admin/evaluadores', 'success_03');
		}
        else
        {
            redirect('admin/evaluadores', 'danger_05');
        }        
	}
    
    private function _listar_usuarios()
    {
        $this->load->model('usuario_model', 'usuario');
        
        $usuarios = $this->usuario->listar();
        $tmp = $this->usuario->listar_intranet();
        
        foreach($usuarios as $key => $usuario)
        {
            foreach($tmp as $item)
            {
                if($item['usuario'] == $usuario['usuario']){ $usuarios[$key]['nombre'] = $item['nombre']; }
            }
        }
        
        return $usuarios;
    }
}

/* End of file evaluadores.php */
/* Location: ./application/controllers/admin/evaluadores.php */