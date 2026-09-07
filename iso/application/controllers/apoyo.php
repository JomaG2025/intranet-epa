<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apoyo extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
		
		$this->load->model('apoyo_model', 'apoyo');
        $this->load->model('grupo_model', 'grupo');
        $this->load->model('modelo_model', 'modelo');
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
		
		if( ! $this->input->post('grupo')) redirect('modelos', 'danger_01', TRUE);
        
		if($grupo = $this->grupo->seleccionar($this->input->post('grupo')))
		{	
            $modelo = $this->modelo->seleccionar($grupo['modelo']);
            
            if( ! is_dir($this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE))) mkdir($this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE));
			
			if( ! is_dir($this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE) . '/apoyo')) mkdir($this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE) . '/apoyo');

			$config['upload_path'] = $this->config->item('upload_path') . url_title(urldecode($modelo['nombre']), '_', TRUE) . '/apoyo';
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
	
			            $apoyo['file_type']= $upload_data['file_type'];
			            $apoyo['full_path']= $upload_data['full_path'];
			            $apoyo['file_name']= $upload_data['file_name'];
			            $apoyo['raw_name']= substr($upload_data['raw_name'], 0, 64);
			            $apoyo['file_ext']= $upload_data['file_ext'];
			            $apoyo['file_size']= byte_format($upload_data['file_size']*1024);
			            $apoyo['grupo'] = $this->input->post('grupo');
			            $apoyo['usuario'] = $this->session->userdata('id');
			            
			            if($apoyo = $this->apoyo->insertar($apoyo))
			            {
			            	$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'apoyo', 'instancia' => $apoyo));
				            redirect('modelos', 'success_04', TRUE);
			            }
			            else
			            {
				            redirect('modelos', 'danger_01', TRUE);
			            }
	
		            }
					else
			        {
			           	redirect('modelos', 'danger_07', TRUE);
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
		
		if(empty($id)) redirect('modelo', 'danger_01', TRUE);

		if($apoyo = $this->apoyo->seleccionar($id))
		{
			$this->apoyo->eliminar($id);
			
			if(is_file($apoyo['full_path'])) unlink($apoyo['full_path']);
			if(is_file($apoyo['full_path'].'.nocontrolado')) unlink($apoyo['full_path'].'.nocontrolado');
			$this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'apoyo', 'instancia' => $id));
			redirect('modelos', 'success_03', TRUE); 	
		}
		else
		{
			redirect('modelo', 'danger_02', TRUE);
		}
	}
	
	public function descargar($id = NULL)
	{
		if(empty($id)) redirect('', 'danger_01', TRUE);
        if( ! $apoyo = $this->apoyo->seleccionar($id))redirect('', 'danger_02', TRUE);
        if( ! is_file($apoyo['full_path'])) redirect('', 'danger_01', TRUE);
		
        $this->apoyo->actualizar($id, array('descarga' => $apoyo['descarga'] + 1));
        $this->auditoria->insertar(array('evento' => 'DWN', 'recurso' => 'apoyo', 'instancia' => $id));
        
        if(strtolower($apoyo['file_ext']) != '.pdf')
        {
            force_download($apoyo['file_name'], $apoyo['full_path']); 
            exit();
        }
        
        if(is_file($apoyo['full_path'].'.nocontrolado'))
        {
            force_download($apoyo['file_name'], $apoyo['full_path'].'.nocontrolado');
            exit();
        }
        else
        {
            require_once(APPPATH.'third_party/fpdf/fpdf.php');
            require_once(APPPATH.'third_party/fpdi/fpdi.php');

            $pdf = new FPDI();

            try
            {
                $cantidad = $pdf->setSourceFile($apoyo['full_path']);

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

                $pdf->Output('F', $apoyo['full_path'].'.nocontrolado', TRUE); 
                force_download($apoyo['file_name'], $apoyo['full_path'].'.nocontrolado');
                exit();
            }
            catch(Exception $e)
            {
                force_download($apoyo['file_name'], $apoyo['full_path']);
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
        if( ! $apoyo = $this->apoyo->seleccionar($id)) redirect('', 'danger_02', TRUE);
        if( ! is_file($apoyo['full_path'])) redirect('', 'danger_01', TRUE);
		
        $this->apoyo->actualizar($id, array('descarga' => $apoyo['descarga'] + 1));
        $this->auditoria->insertar(array('evento' => 'DWN', 'recurso' => 'apoyo', 'instancia' => $id));
        
        force_download($apoyo['file_name'], $apoyo['full_path']); 
        exit();
	}
}

/* End of file apoyo.php */
/* Location: ./application/controllers/apoyo.php */