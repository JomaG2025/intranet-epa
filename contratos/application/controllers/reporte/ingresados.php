<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingresados extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
		$this->load->model('reporte_model', 'reporte');
    }
	
	public function index()
	{
		$data['reportes'] = array();
		$data['desde'] = NULL;
		$data['hasta'] = NULL;
		
		if($this->input->get('desde') AND $this->input->get('hasta'))
		{
            if(date($this->input->get('desde')) >= date($this->input->get('hasta')))
            {
               $this->session->set_flashdata('alerta', 'danger_01');
               redirect('reporte/ingresados/');
            }
            else
            {
               $data['desde'] = $this->input->get('desde');
               $data['hasta'] = $this->input->get('hasta');
               $data['reportes'] = $this->reporte->listar_ingresados($this->input->get('desde'), $this->input->get('hasta'));
            }
		}	

		$this->load->view('header');
		$this->load->view('reporte/ingresados/index', $data);
		$this->load->view('footer');
	}
    
    public function imprimir()
	{
        $this->load->library('excel');

		if($this->input->get('desde') AND $this->input->get('hasta'))
		{
            if(date($this->input->get('desde')) >= date($this->input->get('hasta')))
            {
                $this->session->set_flashdata('alerta', 'danger_01');
                redirect('reporte/ingresados');
            }
            else
            {
                $titulo = 'Reporte de Contratos con terceros ingresados entre '. $this->input->get('desde') . ' hasta '. $this->input->get('hasta');
                $reportes = $this->reporte->listar_ingresados($this->input->get('desde'), $this->input->get('hasta'));
            }
		}
		else
		{
		  	$this->session->set_flashdata('alerta', 'danger_01');
		  	redirect('');
        }

        $columnas = array(
            'id' => 'id',
            'creado' => 'Ingresado',
            'modificado' => 'Modificado por última vez',
            'estado_nombre' => 'Estado',
            'identificacion' => 'Identificación y Nº del acto administrativo',
            'fecha' => 'Fecha del acto administrativo aprobatorio del contrato',
            'proveedor' => 'Proveedor RUT',
            'proveedor_dv' => 'Proveedor DV',
            'proveedor_razon' => 'Razón social',
            'inicio' => 'Fecha de inicio del contrato',
            'termino' => 'Fecha de término del contrato',
            'tipoplazo_nombre' => 'Tipo de plazo',
            'tipopago_nombre' => 'Tipo de pago',
            'moneda' => 'Tipo de moneda',
            'monto' => 'Monto',
            'glosa' => 'Tipo de contratación y objeto de la contratación'
        );
		    
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Contratos ingresados');
		    
		$col = 0;
		    
		foreach($columnas as $columna)
        {
		    $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $columna);
            $col++;
		}
		    
		$row = 2;
		    
		foreach($reportes as $reporte)
        {
		    $col = 0;
		    foreach($columnas as $key => $columna)
		    {
		        $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reporte[$key]);
		        $col++;
		    }
		
            $row++;
		}
		    
		$this->excel->getActiveSheet()->setAutoFilterByColumnAndRow(0, 1, $col-1, $row-1);
		    
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. str_replace(' ', '_' , $titulo).'.xls"');
		header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		$objWriter->save('php://output');
	}
}

/* End of file ingresados.php */
/* Location: ./application/controllers/reporte/ingresados.php */