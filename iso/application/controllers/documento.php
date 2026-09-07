<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documento extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
		
		$this->load->model('documento_model', 'documento');
        $this->load->model('proceso_model', 'proceso');
        $this->load->model('grupo_model', 'grupo');
        $this->load->model('modelo_model', 'modelo');
		$this->load->model('carpeta_model', 'carpeta');
		$this->load->model('auditoria_model', 'auditoria');
        $this->load->helper('download');
    }
	
	public function grabar()
	{	
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('', 'danger_06', TRUE);
		}
		
		if(
			( ! $this->input->post('proceso')) OR
			( ! $this->input->post('nombre'))
		){
			redirect('modelos', 'danger_01', TRUE);
		}
		
		if($proceso = $this->proceso->seleccionar($this->input->post('proceso')))
		{
			if($proceso['activo'] == 0) redirect('modelos', 'danger_04', TRUE);
            
            $grupo = $this->grupo->seleccionar($proceso['grupo']);
            $modelo = $this->modelo->seleccionar($grupo['modelo']);
			
			if(($this->input->post('radio-carpeta') == 1) AND ( ! $this->input->post('nuevacarpeta'))) redirect('modelos/proceso/' . $proceso['id'], 'danger_01', TRUE);
			
			if( ! is_dir($this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE))) mkdir($this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE));
			
			if( ! is_dir($this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE) . '/' . $proceso['id'])) mkdir($this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE) . '/' . $proceso['id']);

			$config['upload_path'] = $this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE) . '/' . $proceso['id'];
			$config['allowed_types'] = '*';
			$config['encrypt_name'] = FALSE;
			$config['max_size'] = 20480;
			$this->load->library('upload', $config);
			$this->load->helper('number');
			
			foreach($_FILES as $field => $file)
		    {
			   	if($file['error'] == 0)
			    {
					if($this->upload->do_upload($field))
			        {
			        	$upload_data = $this->upload->data();
	
			            $documento['file_type']= $upload_data['file_type'];
			            $documento['full_path']= $upload_data['full_path'];
			            $documento['file_name']= $upload_data['file_name'];
			            $documento['raw_name']= substr($upload_data['raw_name'], 0, 64);
			            $documento['file_ext']= $upload_data['file_ext'];
			            $documento['file_size']= byte_format($upload_data['file_size']*1024);
			            
			            $documento['version'] = ($this->input->post('version')) ? $this->input->post('version') : NULL;
			            $documento['codigo'] = ($this->input->post('codigo')) ? $this->input->post('codigo') : NULL;
			            $documento['usuario'] = $this->session->userdata('id');
			            $documento['proceso'] = $this->input->post('proceso');
			            $documento['nombre'] = $this->input->post('nombre');
			            
			            if($this->input->post('radio-carpeta') == 1)
			            {
				            $documento['carpeta'] = $this->carpeta->insertar(array('proceso' => $proceso['id'], 'nombre' => $this->input->post('nuevacarpeta'), 'usuario' => $this->session->userdata('id')));
							$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'carpeta', 'instancia' => $documento['carpeta']));
			            }
			            else
			            {
				            $documento['carpeta'] = ($this->input->post('carpeta')) ? $this->input->post('carpeta') : NULL;
			            }
			            
			            if($documento = $this->documento->insertar($documento))
			            {
			            	$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'documento', 'instancia' => $documento));
				            redirect('modelos/proceso/'. $proceso['id'], 'success_04', TRUE);
			            }
			            else
			            {
				            redirect('modelos/proceso/'. $proceso['id'], 'danger_01', TRUE);
			            }
		            }
					else
			        {
			           	redirect('modelos/proceso/'. $proceso['id'], 'danger_07', TRUE);
			        }
	            }
		    }
		}
		else
		{
			redirect('modelos', 'danger_02', TRUE);
		}
	}
	
	public function eliminar($id = NULL)
	{
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('', 'danger_06', TRUE);
		}
		
		if(empty($id)) redirect('modelos', 'danger_01', TRUE);

		if($documento = $this->documento->seleccionar($id))
		{
			$this->documento->eliminar($id);
			if(is_file($documento['full_path'])) unlink($documento['full_path']);
			if(is_file($documento['full_path'].'.nocontrolado')) unlink($documento['full_path'].'.nocontrolado');
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'documento', 'instancia' => $id));
			redirect('modelos/proceso/' . $documento['proceso'], 'success_03', TRUE);	
		}
		else
		{
			redirect('modelos', 'danger_02', TRUE);
		}
	}
	
	public function mover()
	{
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('', 'danger_06', TRUE);
		}
		
		if( ! $this->input->post('documento')) redirect('modelos', 'danger_01', TRUE);

		if($documento = $this->documento->seleccionar($this->input->post('documento')))
		{
			$carpeta = ($this->input->post('carpeta')) ? $this->input->post('carpeta') : NULL;
		
			$this->documento->actualizar($documento['id'], array('carpeta' => $carpeta));
			$this->auditoria->insertar(array('evento' => 'MOV', 'recurso' => 'documento', 'instancia' => $this->input->post('documento')));
			redirect('modelos/proceso/' . $documento['proceso'], 'success_02', TRUE);	
		}
		else
		{
			redirect('modelos', 'danger_02', TRUE);
		}
	}
	
	public function actualizar()
	{
		if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('', 'danger_06', TRUE);
		}
		
		if(
			( ! $this->input->post('id')) OR
			( ! $this->input->post('nombre'))
		){
			 redirect('modelos', 'danger_01', TRUE);
		}

		if($documento = $this->documento->seleccionar($this->input->post('id')))
		{
			$array['nombre'] = $this->input->post('nombre');
			$array['codigo'] = ($this->input->post('codigo')) ? $this->input->post('codigo') : NULL;
			$array['version'] = ($this->input->post('version')) ? $this->input->post('version') : NULL;
			
			if($this->documento->actualizar($documento['id'], $array))
			{
				$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'documento', 'instancia' => $this->input->post('id')));
				redirect('modelos/proceso/' . $documento['proceso'], 'success_02', TRUE);
			}
			else
			{
				redirect('modelos/proceso/' . $documento['proceso'], 'warning_01', TRUE);
			}	
		}
		else
		{
			redirect('modelos', 'danger_02', TRUE);
		}
	}
	
	public function descargar($id = NULL)
	{	
        if(empty($id)) redirect('', 'danger_01', TRUE);
        if( ! $documento = $this->documento->seleccionar($id)) redirect('', 'danger_02', TRUE);
        if( ! is_file($documento['full_path'])) redirect('', 'danger_02', TRUE);
         
        $this->documento->actualizar($id, array('descarga' => $documento['descarga'] +1));
        $this->auditoria->insertar(array('evento' => 'DWN', 'recurso' => 'documento', 'instancia' => $id));
        
        if($carpeta = $this->carpeta->seleccionar($documento['carpeta']))
        {
            if($carpeta['privada'] AND ($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')) redirect('', 'danger_06', TRUE);
        }
        
        if(strtolower($documento['file_ext']) != '.pdf')
        {
            force_download(url_title(urldecode($documento['nombre']), '_').$documento['file_ext'], $documento['full_path']); 
            exit();
        }
        
        if(is_file($documento['full_path'].'.nocontrolado'))
        {
            force_download(url_title(urldecode($documento['nombre']), '_').$documento['file_ext'], $documento['full_path'].'.nocontrolado');
            exit();
        }
        else
        {
            require_once(APPPATH.'third_party/fpdf/fpdf.php');
            require_once(APPPATH.'third_party/fpdi/fpdi.php');

            $pdf = new FPDI();

            try
            {
                $cantidad = $pdf->setSourceFile($documento['full_path']);

                $i = 1;

                while($i< $cantidad + 1)
                {
                    $original = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($original);
                    
                    $pdf->AddPage($size['h'] > $size['w'] ? 'P' : 'L');
                    $pdf->useTemplate($original, NULL, NULL, $size['w'], $size['h'], FALSE);
                    $pdf->SetMargins(20, 10);
                    $pdf->SetAutoPageBreak(FALSE);

                    if($i == 1)
                    {
                        $pdf->SetFont('Helvetica', '', 7);
                        $pdf->SetTextColor(100);
                        
                        if($size['h'] > $size['w']) $pdf->SetXY(20, -30);
                        else $pdf->SetXY(20, -10);
                        
                        $pdf->Write(3, utf8_decode("Este documento y la información contenida en el son de exclusiva propiedad de Empresa Portuaria Arica. No debe ser reproducido el todo, parte o de otra forma expuesto fuera de la Empresa sin la autorización de la Gerencia."));
                    }
                    else
                    {
                        $pdf->SetAlpha(0.15);
                        $pdf->SetFont('Helvetica', 'B', 18);
                        $pdf->SetTextColor(0);
                        $pdf->SetXY(20, intval($size['h']/2)-10);
                        $pdf->Write(8, utf8_decode("Documento válido para consulta sólo desde Intranet, no controlado en caso de ser impreso o descargado."));
                    }

                    $i++;
                }

                $pdf->Output('F', $documento['full_path'].'.nocontrolado'); 
                force_download(url_title(urldecode($documento['nombre']), '_').$documento['file_ext'], $documento['full_path'].'.nocontrolado');
                exit();
            }
            catch(Exception $e)
            {
                force_download(url_title(urldecode($documento['nombre']), '_').$documento['file_ext'], $documento['full_path']);
                exit();
            }
        }
	}
    
    public function descargar_original($id = NULL)
	{	
        if(
			($this->session->userdata('privilegio') != 'ADM') AND
			($this->session->userdata('privilegio') != 'EDT')
		){
			redirect('', 'danger_06', TRUE);
		}
        
        if(empty($id)) redirect('', 'danger_01', TRUE);
        if( ! $documento = $this->documento->seleccionar($id)) redirect('', 'danger_02', TRUE);
        if( ! is_file($documento['full_path'])) redirect('', 'danger_02', TRUE);
         
        $this->documento->actualizar($id, array('descarga' => $documento['descarga'] +1));
        $this->auditoria->insertar(array('evento' => 'DWN', 'recurso' => 'documento', 'instancia' => $id));
        
        force_download(url_title(urldecode($documento['nombre']), '_').$documento['file_ext'], $documento['full_path']); 
        exit();
	}
}

/* End of file documento.php */
/* Location: ./application/controllers/documento.php */