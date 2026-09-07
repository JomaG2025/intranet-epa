<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contratos extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
        
        $this->load->model('contrato_model', 'contrato');
        $this->load->model('contraparte_model', 'contraparte');
        $this->load->model('tipoplazo_model', 'tipoplazo'); 
        $this->load->model('moneda_model', 'moneda'); 
        $this->load->model('tipopago_model', 'tipopago');
        $this->load->model('archivo_model', 'archivo'); 
        $this->load->model('estado_model', 'estado'); 
        $this->load->model('proveedor_model', 'proveedor');
        $this->load->model('archivo_model', 'archivo');
        $this->load->model('auditoria_model', 'auditoria');
        $this->load->model('hito_model', 'hito');
        $this->load->model('prorroga_model', 'prorroga');
        $this->load->model('historial_estado_model', 'historial_estado');
    }
	
	public function index()
	{
        $filtros = array(
            'estado'        => $this->input->get('estado'),
            'proveedor'     => $this->input->get('proveedor'),
            'termino_desde' => $this->input->get('termino_desde'),
            'termino_hasta' => $this->input->get('termino_hasta'),
            'responsable'   => $this->input->get('responsable'),
        );

        $data['contratos']    = $this->contrato->listar_filtrado($filtros);
        $data['estados']      = $this->estado->listar();
        $data['responsables'] = $this->contraparte->listar_responsables();
        $data['filtros']      = $filtros;

        $qs = http_build_query(array_filter($filtros));
        $this->session->set_userdata('referrer', 'contratos' . ($qs ? '?' . $qs : ''));

		$this->load->view('header');
		$this->load->view('contratos/index', $data);
		$this->load->view('footer');
	}

    public function alertados()
	{
        redirect('contratos?estado=ALE');
	}

    public function vencidos()
	{
        redirect('contratos?estado=VEN');
	}

    public function proveedor()
	{
        if( ! $this->input->get('rut')) redirect('inicio', 'danger_01', TRUE);

        $rut     = $this->input->get('rut');
        $filtros = array('estado' => '', 'proveedor' => '', 'termino_desde' => '', 'termino_hasta' => '', 'responsable' => '');

        $data['contratos']    = $this->contrato->listar(0, FALSE, NULL, str_replace('.', '', $rut));
        $data['estados']      = $this->estado->listar();
        $data['responsables'] = $this->contraparte->listar_responsables();
        $data['filtros']      = $filtros;

        $this->session->set_userdata('referrer', 'contratos/proveedor?rut=' . $rut);

		$this->load->view('header');
		$this->load->view('contratos/index', $data);
		$this->load->view('footer');
	}
    
    public function nuevo()
	{
		$data['monedas'] = $this->moneda->listar();
		$data['tipoplazos'] = $this->tipoplazo->listar();
		$data['tipopagos'] = $this->tipopago->listar();
        $data['usuarios'] = json_decode(file_get_contents('http://intranet.puertoarica.cl/index.php/api/usuarios'), TRUE);
		
		$this->load->view('header');
		$this->load->view('contratos/nuevo', $data);
		$this->load->view('footer');
	}
    
    public function grabar()
    {
        if(
			( ! $this->input->post('fecha')) OR
			( ! $this->input->post('rut')) OR
			( ! $this->input->post('razon')) OR
			( ! $this->input->post('inicio')) OR
			( ! $this->input->post('tipoplazo')) OR
			( ! $this->input->post('tipopago')) OR
			( ! $this->input->post('glosa')) OR
			( ! $this->input->post('moneda'))
		){
            redirect('contratos/nuevo', 'danger_01', TRUE);
		}
        
        if($this->input->post('termino'))
        {
            if(date($this->input->post('inicio')) >= date($this->input->post('termino'))) redirect('contratos/nuevo', 'danger_01', TRUE);
            if( ! $this->input->post('diasalerta')) redirect('contratos', 'danger_01', TRUE);
            $contrato['estado'] = $this->_estado($this->input->post('termino'), $this->input->post('diasalerta'));
        }
        
        $contrato['identificacion'] = ($this->input->post('identificacion')) ? $this->input->post('identificacion') : NULL;
        $contrato['fecha'] = $this->input->post('fecha');
        $contrato['proveedor'] = str_replace('.', '', $this->input->post('rut'));
        $contrato['inicio'] = $this->input->post('inicio');
        $contrato['termino'] = ($this->input->post('termino')) ? $this->input->post('termino') : NULL;
        $contrato['diasalerta'] = ($this->input->post('diasalerta')) ? $this->input->post('diasalerta') : NULL;
        $contrato['tipoplazo'] = $this->input->post('tipoplazo');
        $contrato['tipopago'] = $this->input->post('tipopago');
        $contrato['moneda'] = $this->input->post('moneda');
        $contrato['monto'] = $this->input->post('monto') ? str_replace(',', '.', $this->input->post('monto')) : 0;
        $contrato['glosa'] = $this->input->post('glosa');
        
        if($this->input->post('moneda') == 'CLP') $contrato['monto'] = intval($contrato['monto']);
        
        if( ! $proveedor = $this->proveedor->comprobar($contrato['proveedor']))
        {
            $proveedor = $this->proveedor->insertar(array('rut' => $contrato['proveedor'], 'dv' => (($this->input->post('dv')) ? $this->input->post('dv') : 0), 'razon' => $this->input->post('razon')));
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
        
        if($id = $this->contrato->insertar($contrato))
        {
            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'contrato', 'instancia' => $id));

            if (isset($contrato['estado'])) {
                $this->historial_estado->insertar(array(
                    'contrato'        => $id,
                    'estado_anterior' => NULL,
                    'estado_nuevo'    => $contrato['estado'],
                    'origen'          => 'manual',
                    'usuario'         => $this->session->userdata('usuario'),
                ));
            }

            if($this->input->post('contrapartes'))
            {
                $contrapartes = $this->input->post('contrapartes');
                
                foreach($contrapartes as $usuario)
                {
                    if($contraparte = $this->contraparte->insertar(array('contrato' => $id, 'usuario' => $usuario)))
                    {
                        $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'contraparte', 'instancia' => $contraparte));
                    }
                }
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

                        $archivo['contrato'] = $id;
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
                        redirect('contratos/editar/'. $id, 'danger_05', TRUE);   
                    }
                }
            }
            
            redirect('contratos/editar/'. $id, 'success_01'); 
        }
        else
        {
            redirect('contratos/nuevo', 'danger_01');
        }
    }
    
    public function editar($id = NULL)
	{
		if(empty($id)) redirect('contratos', 'danger_01', TRUE);

		if($data['contrato'] = $this->contrato->seleccionar($id))
		{
            $data['contrapartes'] = $this->contraparte->listar($data['contrato']['id']);
            $data['monedas'] = $this->moneda->listar();
            $data['tipoplazos'] = $this->tipoplazo->listar();
            $data['tipopagos'] = $this->tipopago->listar();
            $data['proveedor'] = $this->proveedor->comprobar($data['contrato']['proveedor']);
            $data['archivos'] = $this->archivo->listar($data['contrato']['id']);
            $data['estados'] = $this->estado->listar();
            $data['hitos']     = $this->hito->listar($data['contrato']['id']);
            $data['prorrogas']  = $this->prorroga->listar($data['contrato']['id']);

            $audit_eventos  = $this->auditoria->listar_por_contrato($data['contrato']['id']);
            $estado_eventos = $this->historial_estado->listar($data['contrato']['id']);
            $historial = array();
            foreach ($audit_eventos  as $e) $historial[] = array('tipo' => 'audit',  'fecha' => $e['fecha'],  'data' => $e);
            foreach ($estado_eventos as $e) $historial[] = array('tipo' => 'estado', 'fecha' => $e['creado'], 'data' => $e);
            usort($historial, function($a, $b) { return strcmp($b['fecha'], $a['fecha']); });
            $data['historial'] = $historial;
            $data['usuarios'] = json_decode(file_get_contents('http://intranet.puertoarica.cl/index.php/api/usuarios'), TRUE);
		
			$this->load->view('header');
			$this->load->view('contratos/editar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect('contratos', 'danger_02', TRUE);		
		}
	}
    
    public function actualizar()
	{        
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('fecha')) OR
			( ! $this->input->post('rut')) OR
			( ! $this->input->post('razon')) OR
			( ! $this->input->post('inicio')) OR
			( ! $this->input->post('tipoplazo')) OR
			( ! $this->input->post('tipopago')) OR
			( ! $this->input->post('glosa')) OR
			( ! $this->input->post('moneda'))
		){
			redirect('contratos', 'danger_01', TRUE);	
		}
        
        if($this->input->post('termino'))
        {
            if(date($this->input->post('inicio')) >= date($this->input->post('termino'))) redirect('contratos', 'danger_01', TRUE);
            if( ! $this->input->post('diasalerta')) redirect('contratos', 'danger_01', TRUE);
            $contrato['estado'] = $this->_estado($this->input->post('termino'), $this->input->post('diasalerta'));
        }
        
        if( ! $contrato_actual = $this->contrato->seleccionar($this->input->post('id'))) redirect('contratos', 'danger_02', TRUE);
        
        $contrato['identificacion'] = ($this->input->post('identificacion')) ? $this->input->post('identificacion') : NULL;
        $contrato['fecha'] = $this->input->post('fecha');
        $contrato['proveedor'] = str_replace('.', '', $this->input->post('rut'));
        $contrato['inicio'] = $this->input->post('inicio');
        $contrato['termino'] = ($this->input->post('termino')) ? $this->input->post('termino') : NULL;
        $contrato['diasalerta'] = ($this->input->post('diasalerta')) ? $this->input->post('diasalerta') : NULL;
        $contrato['tipoplazo'] = $this->input->post('tipoplazo');
        $contrato['tipopago'] = $this->input->post('tipopago');
        $contrato['moneda'] = $this->input->post('moneda');
        $contrato['monto'] = $this->input->post('monto') ? str_replace(',', '.', $this->input->post('monto')) : 0;
        $contrato['glosa'] = $this->input->post('glosa');
        $contrato['modificado'] = date('Y-m-d H:i:s');
            
        if($this->input->post('moneda') == 'CLP') $contrato['monto'] = intval($contrato['monto']);    
        if( ! $proveedor = $this->proveedor->comprobar($contrato['proveedor']))
        {
            $proveedor = $this->proveedor->insertar(array('rut' => $contrato['proveedor'], 'dv' => (($this->input->post('dv')) ? $this->input->post('dv') : 0), 'razon' => $this->input->post('razon')));
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
            
        if($this->contrato->actualizar($this->input->post('id'), $contrato))
        {
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'contrato', 'instancia' => $this->input->post('id')));

            if (isset($contrato['estado']) && $contrato['estado'] !== $contrato_actual['estado']) {
                $this->historial_estado->insertar(array(
                    'contrato'        => (int) $this->input->post('id'),
                    'estado_anterior' => $contrato_actual['estado'],
                    'estado_nuevo'    => $contrato['estado'],
                    'origen'          => 'manual',
                    'usuario'         => $this->session->userdata('usuario'),
                ));
            }

            $this->contraparte->eliminar_por_contrato($this->input->post('id'));
            
            if($this->input->post('contrapartes'))
            {
                $contrapartes = $this->input->post('contrapartes');
                
                foreach($contrapartes as $key => $usuario)
                {
                    if($contraparte = $this->contraparte->insertar(array('contrato' => $this->input->post('id'), 'usuario' => $usuario)))
                    {
                        $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'contraparte', 'instancia' => $contraparte));
                    }
                }
            }
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

                    $archivo['contrato'] = $this->input->post('id');
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
                    redirect('contratos/editar/' . $this->input->post('id'), 'danger_05', TRUE);   
                }
            }
        }

		redirect('contratos/editar/' . $this->input->post('id'), 'success_02', TRUE); 
	}
    
    public function eliminar($id = NULL)
	{
        if($this->session->userdata('privilegio') != 'ADM') redirect('contratos', 'danger_06', TRUE);
        
		if(empty($id)) redirect('contratos', 'danger_01', TRUE);
		
		if( ! $contrato = $this->contrato->seleccionar($id)) redirect('contratos', 'danger_02', TRUE); 
		
        $this->load->model('archivo_model', 'archivo');
        $archivos = $this->archivo->listar($id);
            
        foreach($archivos as $archivo)
        {
            if(is_file($archivo['full_path'])) unlink($archivo['full_path']); 
        }
            
		$this->contrato->eliminar($id);
        $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'contrato', 'instancia' => $id));
        redirect('contratos', 'success_03', TRUE);
	}
    
    private function _estado($termino, $dias)
    {      
        if(date($termino) <= date('Y-m-d'))
        {
            return 'VEN';
        }
        else if((date('Y-m-d', strtotime('-'. $dias .' day', strtotime($termino))) <= date('Y-m-d')) AND (date($termino) > date('Y-m-d')))
        {
            return 'ALE';
        }
        else
        {
            return 'ING';   
        }
    }
}

/* End of file contratos.php */
/* Location: ./application/controllers/contratos.php */