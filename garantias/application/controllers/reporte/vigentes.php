<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vigentes extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
		$this->load->model('reporte_model', 'reporte');
    }
	
	public function index()
	{   
        $data['reportes'] = $this->reporte->listar_vigentes();

		$this->load->view('header');
		$this->load->view('reporte/vigentes/index', $data);
		$this->load->view('footer');
	}
    
    public function imprimir()
	{
        $this->load->library('excel');
        $titulo = 'Reporte de Garantias vigentes';
        $reportes = $this->reporte->listar_vigentes();

        $columnas = array(
            'id' => 'id',
            'creado' => 'Ingresado',
            'modificado' => 'Modificado por última vez',
            'estado_nombre' => 'Estado',
            'proveedor' => 'Proveedor RUT',
            'proveedor_dv' => 'Proveedor DV',
            'proveedor_razon' => 'Razón social',
            'inicio' => 'Fecha de inicio',
            'termino' => 'Fecha de término',
            'institucion_nombre' => 'Institución Financiera',
            'serie' => 'Serie del documento',
            'moneda' => 'Tipo de moneda',
            'monto' => 'Monto',
            'glosa' => 'Glosa'
        );
		    
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Garantias vigentes');
		    
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

/* End of file vigentes.php */
/* Location: ./application/controllers/reporte/vigentes.php */