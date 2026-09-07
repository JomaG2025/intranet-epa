<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carpeta extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('', 'danger_06', TRUE);
		}
		
		$this->load->model('carpeta_model', 'carpeta');
        $this->load->model('proceso_model', 'proceso');
        $this->load->model('documento_model', 'documento');
		$this->load->model('auditoria_model', 'auditoria');
    }
    
    public function renombrar()
	{		
		if(
			( ! $this->input->post('carpeta')) OR
			( ! $this->input->post('nombre'))
		){
			redirect('', 'danger_01', TRUE);
		}

		if($carpeta = $this->carpeta->seleccionar($this->input->post('carpeta')))
		{		
			$this->carpeta->actualizar($this->input->post('carpeta'), array('nombre' => $this->input->post('nombre')));
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'carpeta', 'instancia' => $this->input->post('carpeta')));
			redirect('modelos/proceso/' . $carpeta['proceso'], 'success_02', TRUE);	
		}
		else
		{
			redirect('modelos', 'danger_02', TRUE);
		}
	}
	
	public function grabar()
	{		
		if(
			( ! $this->input->post('nombre')) OR
			( ! $this->input->post('proceso'))
		){
			redirect('', 'danger_01', TRUE);
		}

		if($this->proceso->seleccionar($this->input->post('proceso')))
		{		
			$carpeta = $this->carpeta->insertar(array('nombre' => $this->input->post('nombre'), 'proceso' => $this->input->post('proceso'), 'usuario' => $this->session->userdata('id')));
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'carpeta', 'instancia' => $carpeta));
            redirect('modelos/proceso/' . $this->input->post('proceso'), 'success_01', TRUE);	
		}
		else
		{
			redirect('', 'danger_01', TRUE);
		}
	}
    
    public function convertir($id = NULL)
	{
		if(empty($id)) redirect('', 'danger_01', TRUE);
		
		if($carpeta = $this->carpeta->seleccionar($id))
		{
			$this->carpeta->actualizar($id, array('privada' => (($carpeta['privada'] == 1) ? 0 : 1)));
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'carpeta', 'instancia' => $id));
			redirect('modelo/proceso/' . $carpeta['proceso'], 'success_02', TRUE);	
		}
		else
		{
			redirect('', 'danger_02', TRUE);
		}
	}
	
	public function eliminar($id = NULL)
	{
		if(empty($id)) redirect('', 'danger_01', TRUE);
		
		if($carpeta = $this->carpeta->seleccionar($id))
		{
			$this->carpeta->eliminar($id);
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'carpeta', 'instancia' => $id));
			redirect('modelo/proceso/' . $carpeta['proceso'], 'success_03', TRUE);	
		}
		else
		{
			redirect('', 'danger_02', TRUE);
		}
	}
}

/* End of file carpeta.php */
/* Location: ./application/controllers/carpeta.php */