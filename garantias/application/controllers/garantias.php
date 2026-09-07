<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Garantias extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
        
        $this->load->model('garantia_model', 'garantia');  
        $this->load->model('auditoria_model', 'auditoria');  
    }
	
	public function index()
	{	
        $data['garantias'] = $this->garantia->listar();
        $this->session->set_userdata('referrer', 'garantias/index');
			
		$this->load->view('header');
		$this->load->view('garantias/index', $data);
		$this->load->view('footer');
	}
    
    public function alertadas()
	{	
        $data['garantias'] = $this->garantia->listar(0, FALSE, 'ALE');
        $this->session->set_userdata('referrer', 'garantias/alertadas');
			
		$this->load->view('header');
		$this->load->view('garantias/index', $data);
		$this->load->view('footer');
	}
    
    public function vencidas()
	{	
        $data['garantias'] = $this->garantia->listar(0, FALSE, 'VEN');
        $this->session->set_userdata('referrer', 'garantias/vencidas');
			
		$this->load->view('header');
		$this->load->view('garantias/index', $data);
		$this->load->view('footer');
	}
    
    public function devueltas()
	{	
        $data['garantias'] = $this->garantia->listar(0, FALSE, 'DEV');
        $this->session->set_userdata('referrer', 'garantias/devueltas');
			
		$this->load->view('header');
		$this->load->view('garantias/index', $data);
		$this->load->view('footer');
	}
    
     public function cobradas()
	{	
        $data['garantias'] = $this->garantia->listar(0, FALSE, 'COB');
        $this->session->set_userdata('referrer', 'garantias/cobradas');
			
		$this->load->view('header');
		$this->load->view('garantias/index', $data);
		$this->load->view('footer');
	}
    
    public function proveedor()
	{	
        if( ! $this->input->get('rut')) redirect('inicio', 'danger_01', TRUE);
        
        $proveedor = $this->input->get('rut');
        $data['garantias'] = $this->garantia->listar(0, FALSE, NULL, str_replace('.', '', $proveedor));
        $this->session->set_userdata('referrer', 'garantias/proveedor?rut='. $proveedor);
			
		$this->load->view('header');
		$this->load->view('garantias/index', $data);
		$this->load->view('footer');
	}
    
    public function nueva()
	{
        if( ! in_array($this->session->userdata('privilegio'), array('ADM', 'RES'))) redirect('', 'danger_06');
            
		$this->load->model('institucion_model', 'institucion'); 
		$this->load->model('moneda_model', 'moneda'); 
        
		$data['instituciones'] = $this->institucion->listar();
		$data['monedas'] = $this->moneda->listar();
		
		$this->load->view('header');
		$this->load->view('garantias/nueva', $data);
		$this->load->view('footer');
	}
    
    public function grabar()
    {
        if( ! in_array($this->session->userdata('privilegio'), array('ADM', 'RES'))) redirect('', 'danger_06');
        
        if(
			( ! $this->input->post('rut')) OR
			( ! $this->input->post('razon')) OR
			( ! $this->input->post('inicio')) OR
			( ! $this->input->post('termino')) OR
			( ! $this->input->post('serie')) OR
			( ! $this->input->post('glosa')) OR
			( ! $this->input->post('moneda')) OR
            (date($this->input->post('inicio')) >= date($this->input->post('termino')))
		){
            redirect('garantias/nueva', 'danger_01', TRUE);
		}
        
        $this->load->model('proveedor_model', 'proveedor');
        $this->load->model('archivo_model', 'archivo');
        
        $garantia['proveedor'] = str_replace('.', '', $this->input->post('rut'));
        $garantia['institucion'] = ($this->input->post('institucion')) ? $this->input->post('institucion') : 0;
        $garantia['inicio'] = $this->input->post('inicio');
        $garantia['termino'] = $this->input->post('termino');
        $garantia['serie'] = $this->input->post('serie');
        $garantia['moneda'] = $this->input->post('moneda');
        $garantia['monto'] = $this->input->post('monto') ? str_replace(',', '.', $this->input->post('monto')) : 0;
        $garantia['glosa'] = $this->input->post('glosa');
        $garantia['estado'] = $this->_estado($garantia['termino']);
        
        if($this->input->post('moneda') == 'CLP') $garantia['monto'] = intval($garantia['monto']);
        
        if( ! $proveedor = $this->proveedor->comprobar($garantia['proveedor']))
        {
            $proveedor = $this->proveedor->insertar(array('rut' => $garantia['proveedor'], 'dv' => (($this->input->post('dv')) ? $this->input->post('dv') : 0), 'razon' => $this->input->post('razon')));
            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'proveedor', 'instancia' => $proveedor));
        }
        else
        {
            if($proveedor['razon'] !== $this->input->post('razon'))
            {
                $this->proveedor->actualizar($proveedor['id'], array('razon' => $this->input->post('razon'), 'modificado' => date('Y-m-d H:i:s')));
                $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'proveedor', 'instancia' => $proveedor['id']));
            }
        }
        
        if($id = $this->garantia->insertar($garantia))
        {
            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'garantia', 'instancia' => $id));
            
            if( ! is_dir($this->config->item('upload_path') . date('Y'))) mkdir($this->config->item('upload_path') . date('Y'));

            $config['upload_path'] = $this->config->item('upload_path') . date('Y');
            $config['allowed_types'] = '*';
            $config['encrypt_name'] = TRUE;
            $config['max_size'] = 4096;
            $this->load->library('upload', $config);
            $this->load->helper('number');

            foreach($_FILES as $field => $file)
            {
                if($file['error'] == 0)
                {
                    if($this->upload->do_upload($field))
                    {
                        $upload_data = $this->upload->data();

                        $archivo['garantia'] = $id;
                        $archivo['file_type'] = $upload_data['file_type'];
                        $archivo['full_path'] = $upload_data['full_path'];
                        $archivo['file_name'] = $upload_data['file_name'];
                        $archivo['raw_name'] = substr($upload_data['raw_name'], 0, 64);
                        $archivo['file_ext'] = $upload_data['file_ext'];
                        $archivo['file_size'] = byte_format($upload_data['file_size']*1024);

                        $this->archivo->insertar($archivo);
                    }
                    else
                    {
                        redirect('garantias/editar/'. $id, 'danger_05', TRUE);   
                    }
                }
            }
            
            redirect('garantias/editar/'. $id, 'success_01'); 
        }
        else
        {
            redirect('garantias/nueva', 'danger_01');
        }
    }
    
    public function editar($id = NULL)
	{
		if(empty($id)) redirect('garantias', 'danger_01', TRUE);

		if($data['garantia'] = $this->garantia->seleccionar($id))
		{
            $this->load->model('institucion_model', 'institucion'); 
            $this->load->model('moneda_model', 'moneda'); 
            $this->load->model('proveedor_model', 'proveedor'); 
            $this->load->model('archivo_model', 'archivo'); 
            $this->load->model('estado_model', 'estado'); 
            
            $data['instituciones'] = $this->institucion->listar();
            $data['monedas'] = $this->moneda->listar();
            $data['proveedor'] = $this->proveedor->comprobar($data['garantia']['proveedor']);
            $data['archivos'] = $this->archivo->listar($data['garantia']['id']);
            $data['estados'] = $this->estado->listar();
		
			$this->load->view('header');
			$this->load->view('garantias/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('garantias', 'danger_02', TRUE);		
		}
	}
    
    public function actualizar()
	{    
        if( ! in_array($this->session->userdata('privilegio'), array('ADM', 'RES'))) redirect('', 'danger_06');
        
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('rut')) OR
			( ! $this->input->post('razon')) OR
			( ! $this->input->post('inicio')) OR
			( ! $this->input->post('termino')) OR
			( ! $this->input->post('serie')) OR
			( ! $this->input->post('glosa')) OR
			( ! $this->input->post('moneda')) OR
			( ! $this->input->post('estado')) OR
            (date($this->input->post('inicio')) >= date($this->input->post('termino')))
		){
			redirect('garantias', 'danger_01', TRUE);	
		}
        
        if( ! $this->garantia->seleccionar($this->input->post('id'))) redirect('garantias', 'danger_02', TRUE);	
        
        $this->load->model('proveedor_model', 'proveedor');
        $this->load->model('archivo_model', 'archivo');
        
        $garantia['proveedor'] = str_replace('.', '', $this->input->post('rut'));
        $garantia['institucion'] = ($this->input->post('institucion')) ? $this->input->post('institucion') : 0;
        $garantia['inicio'] = $this->input->post('inicio');
        $garantia['termino'] = $this->input->post('termino');
        $garantia['serie'] = $this->input->post('serie');
        $garantia['moneda'] = $this->input->post('moneda');
        $garantia['monto'] = $this->input->post('monto') ? str_replace(',', '.', $this->input->post('monto')) : 0;
        $garantia['glosa'] = $this->input->post('glosa');
        $garantia['estado'] = (($this->input->post('estado') == 'DEV') OR ($this->input->post('estado') == 'COB')) ? $this->input->post('estado') : $this->_estado($garantia['termino']);
        $garantia['modificado'] = date('Y-m-d H:i:s');
            
        if($this->input->post('moneda') == 'CLP') $garantia['monto'] = intval($garantia['monto']);    
        if( ! $proveedor = $this->proveedor->comprobar($garantia['proveedor']))
        {
            $proveedor = $this->proveedor->insertar(array('rut' => $garantia['proveedor'], 'dv' => (($this->input->post('dv')) ? $this->input->post('dv') : 0), 'razon' => $this->input->post('razon')));
            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'proveedor', 'instancia' => $proveedor));
        }
        else
        {
            if($proveedor['razon'] !== $this->input->post('razon'))
            {
                $this->proveedor->actualizar($proveedor['id'], array('razon' => $this->input->post('razon'), 'modificado' => date('Y-m-d H:i:s')));
                $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'proveedor', 'instancia' => $proveedor['id']));
            }
        }
            
        if($this->garantia->actualizar($this->input->post('id'), $garantia))
        {
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'garantia', 'instancia' => $this->input->post('id')));
        }
            
        if( ! is_dir($this->config->item('upload_path') . date('Y'))) mkdir($this->config->item('upload_path') . date('Y'));

        $config['upload_path'] = $this->config->item('upload_path') . date('Y');
        $config['allowed_types'] = '*';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 4096;
        $this->load->library('upload', $config);
        $this->load->helper('number');

        foreach($_FILES as $field => $file)
        {
            if($file['error'] == 0)
            {
                if($this->upload->do_upload($field))
                {
                    $upload_data = $this->upload->data();

                    $archivo['garantia'] = $this->input->post('id');
                    $archivo['file_type'] = $upload_data['file_type'];
                    $archivo['full_path'] = $upload_data['full_path'];
                    $archivo['file_name'] = $upload_data['file_name'];
                    $archivo['raw_name'] = substr($upload_data['raw_name'], 0, 64);
                    $archivo['file_ext'] = $upload_data['file_ext'];
                    $archivo['file_size'] = byte_format($upload_data['file_size']*1024);

                    $this->archivo->insertar($archivo);
                }
                else
                {
                    redirect('garantias/editar/' . $this->input->post('id'), 'danger_05', TRUE);   
                }
            }
        }

		redirect('garantias/editar/' . $this->input->post('id'), 'success_02', TRUE); 
	}
    
    public function eliminar($id = NULL)
	{
        if($this->session->userdata('privilegio') != 'ADM') redirect('garantias', 'danger_06', TRUE);
        
		if(empty($id)) redirect('garantias', 'danger_01', TRUE);
		
		if( ! $garantia = $this->garantia->seleccionar($id)) redirect('garantias', 'danger_02', TRUE); 
		
        $this->load->model('archivo_model', 'archivo');
        $archivos = $this->archivo->listar($id);
            
        foreach($archivos as $archivo)
        {
            if(is_file($archivo['full_path'])) unlink($archivo['full_path']); 
        }
            
		$this->garantia->eliminar($id);
        $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'garantia', 'instancia' => $id));
        redirect('garantias', 'success_03', TRUE);
	}
    
    private function _estado($termino)
    {      
        if(date($termino) <= date('Y-m-d')) return 'VEN';
        else if((date('Y-m-d', strtotime('-10 day', strtotime($termino))) <= date('Y-m-d')) AND (date($termino) > date('Y-m-d'))) return 'ALE';
        else return 'ING'; 
    }
}

/* End of file garantias.php */
/* Location: ./application/controllers/garantias.php */