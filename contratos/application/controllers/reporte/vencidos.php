<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vencidos extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
		$this->load->model('reporte_model', 'reporte');
    }
	
	public function index()
	{
        $data['reportes'] = $this->reporte->listar_vencidos();

		$this->load->view('header');
		$this->load->view('reporte/vencidos/index', $data);
		$this->load->view('footer');
	}
    
    public function imprimir()
	{
        $this->load->library('excel');
        $titulo = 'Reporte de Contratos vencidos';
        $reportes = $this->reporte->listar_vencidos();

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
		$this->excel->getActiveSheet()->setTitle('Contratos vencidos');
		    
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

/* End of file vencidos.php */
/* Location: ./application/controllers/reporte/vencidos.php */