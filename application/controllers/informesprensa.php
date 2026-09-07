<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Informesprensa extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
        
        $this->load->model('informeprensa_model', 'informeprensa');
        $this->load->model('auditoria_model', 'auditoria');
    }
		
	public function index()
	{
		$data['informesprensa'] = $this->informeprensa->listar();
		
		$this->load->view('header');
		$this->load->view('informesprensa/index', $data);
		$this->load->view('footer');
	}
    
	public function grabar()
	{
        if(($this->session->userdata('privilegio') != 'ADM') AND ($this->session->userdata('privilegio') != 'COM')) redirect('', 'danger_06');
        
		if( ! $this->input->post('fecha')) redirect('informesprensa', 'danger_01', TRUE);
        
        if($this->informeprensa->verificar($this->input->post('fecha'))) redirect('informesprensa', 'danger_03', TRUE);

		$config['upload_path'] = $this->config->item('upload_path') . 'informesprensa/';
        $config['allowed_types'] = '*';
        $config['file_name'] = 'informe-prensa-epa-'. $this->input->post('fecha') .'.pdf';
        $config['max_size'] = 10240;
        $this->load->library('upload', $config);
        $this->load->helper('number');
        
        foreach($_FILES as $field => $file)
        {
            if($file['error'] == 0)
			{
				if($this->upload->do_upload($field))
			    {
                    $upload_data = $this->upload->data();
	
                    $informeprensa['file_type']= $upload_data['file_type'];
                    $informeprensa['full_path']= $upload_data['full_path'];
                    $informeprensa['file_name']= $upload_data['file_name'];
                    $informeprensa['raw_name']= substr($upload_data['raw_name'], 0, 64);
                    $informeprensa['file_ext']= $upload_data['file_ext'];
                    $informeprensa['file_size']= byte_format($upload_data['file_size']*1024);    
                    $informeprensa['fecha'] = $this->input->post('fecha');
                    
			            
			        if($informeprensa = $this->informeprensa->insertar($informeprensa))
			        {
			        	$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'informeprensa', 'instancia' => $informeprensa));
				        redirect('informesprensa', 'success_01', TRUE);
			        }
			        else
			        {
				        redirect('informesprensa', 'danger_01', TRUE);
                    }
		        }
                else
                {
           	        redirect('informesprensa', 'danger_10', TRUE);
                }
            }
        }

	}
    
    public function descargar($id = NULL)
    {
        if(empty($id)) redirect('informesprensa', 'danger_01', TRUE);
        
        if($informeprensa = $this->informeprensa->seleccionar($id))
		{   
            if( ! is_file($informeprensa['full_path'])) redirect('informesprensa', 'danger_02', TRUE);   
            
            $this->informeprensa->actualizar($informeprensa['id'], array('descarga' => $informeprensa['descarga']+1));            
            
            $this->load->helper('large_download');
            
            force_download($informeprensa['file_name'], $informeprensa['full_path']);
		}
		else
		{
			redirect('informesprensa', 'danger_02', TRUE);
        }
    }
	
	public function eliminar($id = NULL)
	{
        if(($this->session->userdata('privilegio') != 'ADM') AND ($this->session->userdata('privilegio') != 'COM')) redirect('', 'danger_06');
        
		if(empty($id)) redirect('informesprensa', 'danger_01', TRUE);
	
		if($informeprensa = $this->informeprensa->seleccionar($id))
		{
            if(is_file($informeprensa['full_path'])) unlink($informeprensa['full_path']);
            
			$this->informeprensa->eliminar($id);
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'informeprensa', 'instancia' => $id));
			redirect('informesprensa', 'success_03', TRUE);	
		}
		else
		{
			redirect('informesprensa', 'danger_02', TRUE);
		}
	}
}

/* End of file informesprensa.php */
/* Location: ./application/controllers/admin/informesprensa.php */