<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Archivo extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
        
        $this->load->model('archivo_model', 'archivo');
        $this->load->model('garantia_model', 'garantia');
        $this->load->model('auditoria_model', 'auditoria');  
    }
    
    public function index()
    {
          die();
    }
    
    public function eliminar($id = NULL)
    {
        if(empty($id)) redirect('', 'danger_01', TRUE);
        if( ! ($archivo = $this->archivo->seleccionar($id))) redirect('', 'danger_02', TRUE);
		
        if($this->archivo->eliminar($id))
        {
            if(is_file($archivo['full_path'])) unlink($archivo['full_path']);	
        }
        
        redirect('', 'success_03', TRUE);
    }
	
	public function descargar($id = NULL)
	{	
        if(empty($id)) redirect('', 'danger_01', TRUE);
        if( ! $archivo = $this->archivo->seleccionar($id)) if(empty($id)) redirect('', 'danger_02', TRUE);
        if( ! is_file($archivo['full_path'])) if(empty($id)) redirect('', 'danger_02', TRUE);
        $garantia = $this->garantia->seleccionar($archivo['garantia']); 
        
        $this->archivo->actualizar($id, array('descarga' => $archivo['descarga'] +1));
        $this->auditoria->insertar(array('evento' => 'DWN', 'recurso' => 'archivo', 'instancia' => $id));

        $this->load->helper('large_download');
        force_download('garantia-epa-'.$garantia['serie'].$archivo['file_ext'], $archivo['full_path']);
	}
}

/* End of file archivo.php */
/* Location: ./application/controllers/archivo.php */