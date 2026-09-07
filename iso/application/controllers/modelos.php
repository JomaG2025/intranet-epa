<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modelos extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        if( ! $this->session->userdata('id')) redirect('login');
        
        $this->load->model('modelo_model', 'modelo');
        $this->load->model('grupo_model', 'grupo');
        $this->load->model('proceso_model', 'proceso');
		$this->load->model('documento_model', 'documento');
		$this->load->model('carpeta_model', 'carpeta');
		$this->load->model('apoyo_model', 'apoyo');
    }
	
	public function index()
	{	
        $data['modelos'] = $this->modelo->listar_api();
        
        foreach($data['modelos'] AS $key => $modelo)
        {
            if( ! is_file(APPPATH . 'views/modelos/id/'. $modelo['id'] . '.php')) unset($data['modelos'][$key]);
        }
        
        if($this->uri->segment(3) == 'full') $this->session->set_userdata('full', TRUE);
        if($this->uri->segment(3) == 'simple') $this->session->unset_userdata('full');
        
        $this->load->view('header');
		$this->load->view('modelos/index', $data);
		$this->load->view('footer');
	}
	
	public function grupo($id = NULL)
	{	
		if(empty($id)) redirect('modelos', 'danger_01', TRUE);

		if($data['grupo'] = $this->grupo->seleccionar($id))
		{
            $data['modelo'] = $this->modelo->seleccionar($data['grupo']['modelo']);
            
            if(($data['modelo']['activo'] == 0) AND (($this->session->userdata('privilegio') != 'ADM') AND ($this->session->userdata('privilegio') != 'EDT'))) redirect('modelos', 'danger_06', TRUE);
            
			$data['apoyos'] = $this->apoyo->listar($id);
			$data['procesos'] = $this->proceso->listar($id);
            
            foreach($data['procesos'] as $key => $value)
            {
                $data['procesos'][$key]['carpetas'] = $this->carpeta->contar($value['id']);
                $data['procesos'][$key]['documentos'] = $this->documento->contar($value['id']);
            }

			$this->load->view('header');
			$this->load->view('modelos/grupo', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('modelos', 'danger_02', TRUE);
		}
	}
	
	public function proceso($id = NULL)
	{	
		if(empty($id)) redirect('modelos', 'danger_01', TRUE);
		
		if($data['proceso'] = $this->proceso->seleccionar($id))
		{
			if(($data['proceso']['activo'] == 0) AND (($this->session->userdata('privilegio') != 'ADM') AND ($this->session->userdata('privilegio') != 'EDT'))) redirect('modelos', 'danger_04', TRUE);
			
			$data['grupo'] = $this->grupo->seleccionar($data['proceso']['grupo']);
			$data['modelo'] = $this->modelo->seleccionar($data['grupo']['modelo']);
                
            if(($data['modelo']['activo'] == 0) AND (($this->session->userdata('privilegio') != 'ADM') AND ($this->session->userdata('privilegio') != 'EDT'))) redirect('modelos', 'danger_06', TRUE);
                
			$data['carpetas'] = $this->carpeta->listar($id);
			$data['apoyos'] = $this->apoyo->listar($data['proceso']['grupo']);
				
			foreach($data['carpetas'] as $key => $carpeta)
			{
				$data['documentacion'][$key] = $carpeta;
				$data['documentacion'][$key]['documentos'] = $this->documento->listar($id, $carpeta['id']);
			}
			
			if(isset($data['documentacion']))
			{
				if($tmp = $this->documento->listar($id))
				{
					array_unshift($data['documentacion'], array('documentos' => $tmp));
				}				
			}
			else if($tmp = $this->documento->listar($id))
			{
				$data['documentacion'][0]['documentos'] = $tmp;
			}
				
			$this->load->view('header');
			$this->load->view('modelos/proceso', $data);
			$this->load->view('footer');
			
		}
		else
		{
			redirect('modelos', 'danger_04', TRUE);
		}
	}	
}

/* End of file modelos.php */
/* Location: ./application/controllers/modelos.php */