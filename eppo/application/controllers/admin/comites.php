<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comites extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
        
        $this->load->model('comite_model', 'comite');  
        $this->load->model('auditoria_model', 'auditoria');  
    }
	
	public function index()
	{	        
		$data['comites'] = $this->comite->listar();

		$this->load->view('header');
		$this->load->view('admin/comites/index', $data);
		$this->load->view('footer');
	}
    
    public function nuevo()
    {
        $this->load->model('periodo_model', 'periodo');
        $this->load->model('usuario_model', 'usuario');
        
        $data['periodos'] = $this->periodo->listar();
        $data['usuarios'] = $this->usuario->listar();
        
        $tmp = $this->usuario->listar_intranet();
        
        foreach($data['usuarios'] as $key => $usuario)
        {
            foreach($tmp as $item)
            {
                if($item['usuario'] == $usuario['usuario']) $data['usuarios'][$key]['nombre'] = $item['nombre']; 
            }
        }
        
        $this->load->view('header');
		$this->load->view('admin/comites/nuevo', $data);
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
            redirect('admin/comites/nuevo', 'danger_01', TRUE);
		}
        
        if( ! $this->periodo->verificar($this->input->post('periodo'))) redirect('admin/comites/nuevo', 'danger_01', TRUE);
        
        if( ! $this->usuario->verificar($this->input->post('usuario'))) redirect('admin/comites/nuevo', 'danger_01', TRUE);
        
        if($this->comite->verificar($this->input->post('usuario'), $this->input->post('periodo'))) redirect('admin/comites/nuevo', 'danger_03', TRUE);
		
		$comite['periodo'] = $this->input->post('periodo');
		$comite['usuario'] = $this->input->post('usuario');
		
		if($comite = $this->comite->insertar($comite))
		{
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'comite', 'instancia' => $comite));
			redirect('admin/comites', 'success_01');
		}
		else
		{
			redirect('admin/comites', 'danger_01');
		} 
    }
	
    public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('admin/comites', 'danger_01', TRUE);
		
		if( ! $this->comite->seleccionar($id)) redirect('admin/comites', 'danger_02', TRUE);
		
		if($this->comite->eliminar($id))
		{
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'comite', 'instancia' => $id));
			redirect('admin/comites', 'success_03', TRUE);
		}
        else
        {
            redirect('admin/comites', 'danger_05', TRUE);
        }
	}	
}

/* End of file comites.php */
/* Location: ./application/controllers/admin/comites.php */