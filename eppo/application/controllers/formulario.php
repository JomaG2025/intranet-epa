<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formulario extends CI_Controller {
    
    private $heightfactor = 20;
    private $widthfactor = 30;

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
        
        $this->load->model('periodo_model', 'periodo');
        $this->load->model('etapa_model', 'etapa');
        $this->load->model('formulario_model', 'formulario');
        $this->load->model('comite_model', 'comite');
        $this->load->model('evaluador_model', 'evaluador');
        $this->load->model('evaluado_model', 'evaluado');
        $this->load->model('tiponivel_model', 'tiponivel');
        $this->load->model('tipocontrato_model', 'tipocontrato');
        $this->load->model('competenciatransversal_model', 'competenciatransversal');
        $this->load->model('objetivoarea_model', 'objetivoarea');
        $this->load->model('objetivoindividual_model', 'objetivoindividual');
        $this->load->model('capacitacion_model', 'capacitacion');
        $this->load->model('retroalimentacion_model', 'retroalimentacion');
        $this->load->model('medicion_model', 'medicion');
        $this->load->model('resultado_model', 'resultado');
        $this->load->model('resultadoapelacion_model', 'resultadoapelacion');
        $this->load->model('usuario_model', 'usuario');
        $this->load->model('auditoria_model', 'auditoria');
        $this->load->library('email'); 
    }
	
	public function index()
	{	
        $data['periodo_actual'] = $this->periodo->seleccionar_actual();
        $data['etapa_actual'] = $this->etapa->seleccionar_actual($data['periodo_actual']['periodo']);
        
        if( ! $data['formulario'] = $this->formulario->seleccionar_por_usuario_periodo($this->session->userdata('usuario'), $data['periodo_actual']['periodo'])) redirect('formulario/crear'); 
        
        $data['evaluadores'] = $this->evaluado->listar_evaluadores($this->session->userdata('usuario'), $data['periodo_actual']['periodo']);
        $data['tipocontratos'] = $this->tipocontrato->listar();
        $data['tiponiveles'] = $this->tiponivel->listar();
        $data['objetivosarea'] = $this->objetivoarea->listar($data['formulario']['id']);
        $data['objetivosindividuales']['transversales'] = $this->objetivoindividual->listar($data['formulario']['id'], TRUE);
        $data['objetivosindividuales']['notransversales'] = $this->objetivoindividual->listar($data['formulario']['id'], FALSE);
        $data['capacitaciones'] = $this->capacitacion->listar($data['formulario']['id']);
        $data['resultado'] = $this->resultado->verificar($data['formulario']['id']);
        if(($data['resultadoapelacion'] = $this->resultadoapelacion->verificar($data['formulario']['id'])) OR ($data['formulario']['estadoformulario'] == 'APE')) $data['etapa_actual']['puedeapelar'] = 0;
        
        foreach($data['evaluadores'] as $key => $evaluador)
        {
            $data['evaluadores'][$key] = $this->usuario->seleccionar_intranet($evaluador['usuario']);
        }
			
		$this->load->view('header');
		$this->load->view('formulario/index', $data);
		$this->load->view('footer');
	}
    
    public function crear()
    {
        $periodo_actual = $this->periodo->seleccionar_actual();
        
        if($this->formulario->seleccionar_por_usuario_periodo($this->session->userdata('usuario'), $periodo_actual['periodo']))
        {
            redirect('formulario');
        }
        else if($formulario = $this->formulario->crear(array('periodo' => $periodo_actual['periodo'], 'usuario' => $this->session->userdata('usuario'), 'nombre' => $this->session->userdata('nombre'))))
        {
            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'formulario', 'instancia' => $formulario));
            
            $competenciastransversales = $this->competenciatransversal->listar();
            
            foreach($competenciastransversales as $competenciatransversal)
            {
                $id = $this->objetivoindividual->insertar(array('formulario' => $formulario, 'competenciatransversal' => $competenciatransversal['id'], 'dimension' => $competenciatransversal['nombre'], 'objetivo' => $competenciatransversal['objetivo']));
                $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'objetivoindividual', 'instancia' => $id));
            }
            
            if($evaluado = $this->usuario->seleccionar_intranet($this->session->userdata('usuario')))
            {
                $evaluadores = $this->evaluado->listar_evaluadores($this->session->userdata('usuario'), $periodo_actual['periodo']);
                
                $this->email->from('no-responder@puertoarica.com', 'Evaluación EPPO');
                $this->email->subject($evaluado['nombre'] . ' ha iniciado el Formulario EPPO '. $periodo_actual['periodo'] .'.'); 
                
                foreach($evaluadores as $evaluador)
                {
                    if($evaluador = $this->usuario->seleccionar_intranet($evaluador['usuario']))
                    {
                        $this->email->to($evaluador['email']);
                        $this->email->message($this->load->view('formulario/email/crear', array('evaluador' => $evaluador, 'evaluado' => $evaluado, 'periodo' => $periodo_actual), TRUE));
                        $this->email->send();
                    }
                }
            }
            
            redirect('formulario');
        }
        else
        {
            redirect('', 'danger_01');   
        }
    }
    
    public function grabar()
    {
        if( ! $this->input->post('id')) redirect('formulario', 'danger_01', TRUE);
        
        if( ! $formulario = $this->formulario->seleccionar($this->input->post('id'))) redirect('formulario', 'danger_01', TRUE);
        
        $periodo_actual = $this->periodo->seleccionar_actual();
        
        if(
            ($formulario['estadoformulario'] == 'CON') OR 
            ($formulario['estadoformulario'] == 'EVA') OR 
            ($formulario['estadoformulario'] == 'APE')
        ){
            redirect('formulario', 'danger_06', TRUE);  
        }
        
        if(($formulario['usuario'] != $this->session->userdata('usuario')) OR ($formulario['periodo'] != $periodo_actual['periodo'])) redirect('formulario', 'danger_01', TRUE); 
        
        $this->_grabar($formulario['id'], $this->input->post());
        redirect('formulario', 'success_05');
    }
    
    public function enviar()
    {
        if( ! $this->input->post('id')) redirect('formulario', 'danger_01', TRUE);
        
        if( ! $formulario = $this->formulario->seleccionar($this->input->post('id'))) redirect('formulario', 'danger_01', TRUE); 
        
        $periodo_actual = $this->periodo->seleccionar_actual();
        
        if(
            ($formulario['estadoformulario'] == 'CON') OR 
            ($formulario['estadoformulario'] == 'EVA') OR 
            ($formulario['estadoformulario'] == 'APE')
        ){
            redirect('formulario', 'danger_06'. TRUE);  
        }
        
        if(($formulario['usuario'] != $this->session->userdata('usuario')) OR ($formulario['periodo'] != $periodo_actual['periodo'])) redirect('formulario', 'danger_01', TRUE);  
        
        $this->_grabar($formulario['id'], $this->input->post());
        $this->formulario->actualizar($formulario['id'], array('estadoformulario' => 'ENV'));
        
        if($evaluado = $this->usuario->seleccionar_intranet($formulario['usuario']))
        {
            $evaluadores = $this->evaluado->listar_evaluadores($formulario['usuario'], $periodo_actual['periodo']);
            
            $this->email->from('no-responder@puertoarica.com', 'Evaluación EPPO');
            $this->email->subject($evaluado['nombre'] . ' ha enviado el Formulario EPPO '. $periodo_actual['periodo'] .' para su revisión y retroalimentación.'); 
            
            foreach($evaluadores as $evaluador)
            {
                if($evaluador = $this->usuario->seleccionar_intranet($evaluador['usuario']))
                {
                    $this->email->to($evaluador['email']);
                    $this->email->message($this->load->view('formulario/email/enviar', array('evaluador' => $evaluador, 'evaluado' => $evaluado, 'periodo' => $periodo_actual), TRUE));
                    $this->email->send();
                }
            }
        }

        redirect('', 'success_06');
    }
    
    public function apelar()
    {
        if( 
            ( ! $this->input->post('id')) OR
            ( ! $this->input->post('apelacion'))
		){
            redirect('formulario', 'danger_01', TRUE);
		}
        
        if( ! $formulario = $this->formulario->seleccionar($this->input->post('id'))) redirect('formulario', 'danger_01', TRUE); 
        
        $periodo_actual = $this->periodo->seleccionar_actual();
        $etapa_actual = $this->etapa->seleccionar_actual($periodo_actual['periodo']);
        if($this->resultadoapelacion->verificar($formulario['id'])) $etapa_actual['puedeapelar'] = 0;
        
        if( ! $etapa_actual['puedeapelar']) redirect('formulario', 'danger_06', TRUE);
        
        if($formulario['estadoformulario'] != 'EVA') redirect('formulario', 'danger_06', TRUE);
        
        if(($formulario['usuario'] != $this->session->userdata('usuario')) OR ($formulario['periodo'] != $periodo_actual['periodo'])) redirect('formulario', 'danger_01', TRUE);  
        
        $this->formulario->actualizar($formulario['id'], array('estadoformulario' => 'APE', 'apelacion' => $this->input->post('apelacion')));
        
        if($evaluado = $this->usuario->seleccionar_intranet($formulario['usuario']))
        {
            $evaluadores = $this->evaluado->listar_evaluadores($formulario['usuario'], $periodo_actual['periodo']);
            $comites = $this->comite->listar($periodo_actual['periodo']);
            
            $this->email->from('no-responder@puertoarica.com', 'Evaluación EPPO');
            $this->email->subject($evaluado['nombre'] . ' ha solicitado la apelación de su Formulario EPPO '. $periodo_actual['periodo'] .'.'); 
            
            foreach($evaluadores as $evaluador)
            {
                if($evaluador = $this->usuario->seleccionar_intranet($evaluador['usuario']))
                {
                    $this->email->to($evaluador['email']);
                    $this->email->message($this->load->view('formulario/email/apelar', array('evaluador' => $evaluador, 'evaluado' => $evaluado, 'periodo' => $periodo_actual, 'apelacion' => $this->input->post('apelacion')), TRUE));
                    $this->email->send();
                }
            }
            
            foreach($comites as $comite)
            {
                if( ! in_array_column($comite['usuario'], 'usuario', $evaluadores))
                {
                    if($comite = $this->usuario->seleccionar_intranet($comite['usuario']))
                    {
                        $this->email->to($comite['email']);
                        $this->email->message($this->load->view('formulario/email/apelar', array('evaluador' => $comite, 'evaluado' => $evaluado, 'periodo' => $periodo_actual, 'apelacion' => $this->input->post('apelacion')), TRUE));
                        $this->email->send();
                    }
                }
            }
        }
        
        redirect('', 'success_07');
    }
    
    public function excel($formulario = NULL)
    {
        $this->load->library('excel');
        
        if( ! $formulario) redirect('', 'danger_01', TRUE);
        
        if( ! $formulario = $this->formulario->seleccionar($formulario)) redirect('', 'danger_02', TRUE);
        
        if(
            ($this->session->userdata('privilegio') != 'ADM') AND 
            ($this->session->userdata('privilegio') != 'COR') AND
            ( ! $this->comite->verificar($this->session->userdata('usuario'), $formulario['periodo'])) AND
            ( ! $this->evaluador->verificar($this->session->userdata('usuario'), $formulario['periodo'], $formulario['usuario']))
        ){
            redirect('', 'danger_06', TRUE);
        }

        $evaluadores = $this->evaluado->listar_evaluadores($formulario['usuario'], $formulario['periodo']);
        $tipocontratos = $this->tipocontrato->listar();
        $tiponiveles = $this->tiponivel->listar();
        $objetivosarea = $this->objetivoarea->listar($formulario['id']);
        $objetivosindividuales['transversales'] = $this->objetivoindividual->listar($formulario['id'], TRUE);
        $objetivosindividuales['notransversales'] = $this->objetivoindividual->listar($formulario['id'], FALSE);
        $capacitaciones = $this->capacitacion->listar($formulario['id']);
        $mediciones = $this->medicion->listar();
        $resultado = $this->resultado->verificar($formulario['id']);
        $resultadoapelacion = $this->resultadoapelacion->verificar($formulario['id']);
        
        foreach($evaluadores as $key => $evaluador)
        {
            $evaluadores[$key] = $this->usuario->seleccionar_intranet($evaluador['usuario']);
        }
        
        $estilo_bordes = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000')
                )
            )
        );
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        
        $this->excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        
        $this->excel->getActiveSheet()->mergeCells('A1:M1');
        $this->excel->getActiveSheet()->setCellValue('A1', 'EVALUACIÓN PARTICIPATIVA POR OBJETIVOS');
        $this->excel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(TRUE);
        $this->excel->getActiveSheet()->getStyle('A1:M2')->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
        
        $this->excel->getActiveSheet()->mergeCells('A2:M2');
        $this->excel->getActiveSheet()->setCellValue('A2', 'Periodo Diciembre '. ($formulario['periodo']-1) . ' - Diciembre '. $formulario['periodo']);
        
        $row = 5;
        
        foreach($evaluadores as $evaluador)
        {
            $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
            
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':C'. $row);
            $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Evaluador (Nombre y Firma)');
            $this->excel->getActiveSheet()->getStyle('A'.$row.':G'. $row)->applyFromArray($estilo_bordes);
            
            $this->excel->getActiveSheet()->mergeCells('D'.$row.':E'. $row);
            $this->excel->getActiveSheet()->setCellValue('D'.$row, $evaluador['nombre']);
            
            $this->excel->getActiveSheet()->mergeCells('F'.$row.':G'. $row);

            $row++;
        }
        
        $this->excel->getActiveSheet()->getRowDimension('5')->setRowHeight(40);
        $this->excel->getActiveSheet()->mergeCells('I5:M5');
        $this->excel->getActiveSheet()->setCellValue('I5', 'NIVEL/ Situación Contractual');
        $this->excel->getActiveSheet()->getStyle('I5:M7')->applyFromArray($estilo_bordes);
        $this->excel->getActiveSheet()->getStyle('I5:M5')->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
        
        $this->excel->getActiveSheet()->getRowDimension('6')->setRowHeight(40);
        $this->excel->getActiveSheet()->setCellValue('I6', 'Nivel');
        $this->excel->getActiveSheet()->mergeCells('J6:M6');
        $this->excel->getActiveSheet()->setCellValue('J6', $formulario['tiponivel_nombre']);
        
        $this->excel->getActiveSheet()->getRowDimension('7')->setRowHeight(40);
        $this->excel->getActiveSheet()->setCellValue('I7', 'Contrato');
        $this->excel->getActiveSheet()->mergeCells('J7:M7');
        $this->excel->getActiveSheet()->setCellValue('J7', $formulario['tipocontrato_nombre']);
        
        
        $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
        $this->excel->getActiveSheet()->mergeCells('A'.$row.':C'. $row);
        $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Evaluado (Nombre y Firma)');
        $this->excel->getActiveSheet()->getStyle('A'.$row.':G'. $row)->applyFromArray($estilo_bordes);
        $this->excel->getActiveSheet()->mergeCells('D'.$row.':E'. $row);
        $this->excel->getActiveSheet()->setCellValue('D'.$row, $formulario['nombre']);
        $this->excel->getActiveSheet()->mergeCells('F'.$row.':G'. $row);
        
        $row++;
        
        $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
        $this->excel->getActiveSheet()->mergeCells('A'.$row.':C'. $row);
        $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Estado y puntaje');
        $this->excel->getActiveSheet()->getStyle('A'.$row.':G'. $row)->applyFromArray($estilo_bordes);
        $this->excel->getActiveSheet()->mergeCells('D'.$row.':E'. $row);
        $this->excel->getActiveSheet()->setCellValue('D'.$row, $formulario['estadoformulario_nombre']);
        $this->excel->getActiveSheet()->mergeCells('F'.$row.':G'. $row);
        
        if($resultadoapelacion['puntaje']) $this->excel->getActiveSheet()->setCellValue('F'.$row, $resultadoapelacion['puntaje'] . ' PTS.');
        else if($resultado['puntaje']) $this->excel->getActiveSheet()->setCellValue('F'.$row, $resultado['puntaje'] . ' PTS.');
        else $this->excel->getActiveSheet()->setCellValue('F'.$row, 'No evaluado');

        $row++;
        $row++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$row, 'OBJETIVOS A EVALUAR');
        $this->excel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(TRUE);
        
        $row++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$row, '1. Objetivos por Área o Departamento.');
        
        $row++;
        $row++;
        
        $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
        
        if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE'))
        { 
            $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row+(count($objetivosarea)*2)))->applyFromArray($estilo_bordes);
        }
        else
        {
            $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row+(count($objetivosarea))))->applyFromArray($estilo_bordes);
        }
        
        $this->excel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
        $this->excel->getActiveSheet()->mergeCells('A'.$row.':B'. $row);
        $this->excel->getActiveSheet()->mergeCells('C'.$row.':D'. $row);
        $this->excel->getActiveSheet()->mergeCells('E'.$row.':F'. $row);
        $this->excel->getActiveSheet()->mergeCells('G'.$row.':H'. $row);
        $this->excel->getActiveSheet()->mergeCells('I'.$row.':J'. $row);
        $this->excel->getActiveSheet()->mergeCells('K'.$row.':L'. $row);
        $this->excel->getActiveSheet()->setCellValue('A'.$row, 'PROYECTO/PROCESO');
        $this->excel->getActiveSheet()->setCellValue('C'.$row, 'DESCRIPCIÓN');
        $this->excel->getActiveSheet()->setCellValue('E'.$row, 'OBJETIVO');
        $this->excel->getActiveSheet()->setCellValue('G'.$row, 'META');
        $this->excel->getActiveSheet()->setCellValue('I'.$row, 'INDICADOR DE GESTIÓN');
        $this->excel->getActiveSheet()->setCellValue('K'.$row, 'PLAN DE ACCIÓN CONSENSUADO');
        $this->excel->getActiveSheet()->setCellValue('M'.$row, 'EVALUACIÓN');
        $this->excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('K'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('M'.$row)->getAlignment()->setWrapText(TRUE);
        
        $row++;
        
        foreach($objetivosarea as $key => $objetivoarea)
        {
            $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight($this->_calculateHeight($objetivoarea));
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':B'. $row);
            $this->excel->getActiveSheet()->mergeCells('C'.$row.':D'. $row);
            $this->excel->getActiveSheet()->mergeCells('E'.$row.':F'. $row);
            $this->excel->getActiveSheet()->mergeCells('G'.$row.':H'. $row);
            $this->excel->getActiveSheet()->mergeCells('I'.$row.':J'. $row);
            $this->excel->getActiveSheet()->mergeCells('K'.$row.':L'. $row);
            $this->excel->getActiveSheet()->setCellValue('A'.$row, $objetivoarea['proyecto']);
            $this->excel->getActiveSheet()->setCellValue('C'.$row, $objetivoarea['descripcion']);
            $this->excel->getActiveSheet()->setCellValue('E'.$row, $objetivoarea['objetivo']);
            $this->excel->getActiveSheet()->setCellValue('G'.$row, $objetivoarea['meta']);
            $this->excel->getActiveSheet()->setCellValue('I'.$row, $objetivoarea['indicador']);
            $this->excel->getActiveSheet()->setCellValue('K'.$row, $objetivoarea['plan']);
            $this->excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('K'.$row)->getAlignment()->setWrapText(TRUE);
            
            if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE'))
            { 
                $this->excel->getActiveSheet()->mergeCells('M'.($row).':M'. ($row+1));
                $this->excel->getActiveSheet()->setCellValue('M'.$row, $objetivoarea['evaluacion_puntaje']);
                $this->excel->getActiveSheet()->getStyle('M'.$row)->getAlignment()->setWrapText(TRUE);
                
                $row++;
                
                $this->excel->getActiveSheet()->mergeCells('A'.$row.':L'. $row);
                $this->excel->getActiveSheet()->setCellValue('A'.$row, $objetivoarea['evaluacion_observacion']);
            }
            
            $row++;
        }
        
        $row++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$row, '2. Objetivos Individuales.');
        
        $row++;
        $row++;
        
        $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
        
        if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE'))
        {
            $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row+(count($objetivosindividuales['transversales'])*2)+(count($objetivosindividuales['notransversales']))*2))->applyFromArray($estilo_bordes);
        }
        else
        {
            $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row+count($objetivosindividuales['transversales'])+count($objetivosindividuales['notransversales'])))->applyFromArray($estilo_bordes);
        }
            
        $this->excel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
        $this->excel->getActiveSheet()->mergeCells('A'.$row.':C'. $row);
        $this->excel->getActiveSheet()->mergeCells('D'.$row.':F'. $row);
        $this->excel->getActiveSheet()->mergeCells('G'.$row.':I'. $row);
        $this->excel->getActiveSheet()->mergeCells('J'.$row.':L'. $row);
        $this->excel->getActiveSheet()->setCellValue('A'.$row, 'ORIGEN');
        $this->excel->getActiveSheet()->setCellValue('D'.$row, 'DIMENSIÓN');
        $this->excel->getActiveSheet()->setCellValue('G'.$row, 'OBJETIVO');
        $this->excel->getActiveSheet()->setCellValue('J'.$row, 'PLAN DE ACCIÓN');
        $this->excel->getActiveSheet()->setCellValue('M'.$row, 'EVALUACIÓN');
        $this->excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('J'.$row)->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getStyle('M'.$row)->getAlignment()->setWrapText(TRUE);
        
        $row++;
        
        foreach($objetivosindividuales['transversales'] as $key => $objetivoindividual)
        {
            if($key == 0)
            {
                if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE'))
                {
                    $this->excel->getActiveSheet()->mergeCells('A'.$row.':C'. ($row+(count($objetivosindividuales['transversales'])*2)-1));
                }
                else
                {
                    $this->excel->getActiveSheet()->mergeCells('A'.$row.':C'. ($row+count($objetivosindividuales['transversales'])-1));
                }  
                    
                $this->excel->getActiveSheet()->setCellValue('A'.$row, 'PLAN ESTRATÉGICO Competencias transversales');
                $this->excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setWrapText(TRUE);
            }
            
            $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight($this->_calculateHeight($objetivoindividual));
            
            $this->excel->getActiveSheet()->mergeCells('D'.$row.':F'. $row);
            $this->excel->getActiveSheet()->mergeCells('G'.$row.':I'. $row);
            $this->excel->getActiveSheet()->mergeCells('J'.$row.':L'. $row);
            $this->excel->getActiveSheet()->setCellValue('D'.$row, $objetivoindividual['dimension']);
            $this->excel->getActiveSheet()->setCellValue('G'.$row, $objetivoindividual['objetivo']);
            $this->excel->getActiveSheet()->setCellValue('J'.$row, $objetivoindividual['plan']);
            $this->excel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('J'.$row)->getAlignment()->setWrapText(TRUE);
            
            if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE'))
            { 
                $this->excel->getActiveSheet()->mergeCells('M'.($row).':M'. ($row+1));
                $this->excel->getActiveSheet()->setCellValue('M'.$row, $objetivoindividual['evaluacion_puntaje']);
                $this->excel->getActiveSheet()->getStyle('M'.$row)->getAlignment()->setWrapText(TRUE);
                
                $row++;
                
                $this->excel->getActiveSheet()->mergeCells('D'.$row.':L'. $row);
                $this->excel->getActiveSheet()->setCellValue('D'.$row, $objetivoindividual['evaluacion_observacion']);
            }
            
            $row++;
        }
        
        foreach($objetivosindividuales['notransversales'] as $key => $objetivoindividual)
        {
            if($key == 0)
            {
                if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE'))
                {
                    $this->excel->getActiveSheet()->mergeCells('A'.$row.':C'. ($row+(count($objetivosindividuales['notransversales'])*2)-1));
                }
                else
                {
                    $this->excel->getActiveSheet()->mergeCells('A'.$row.':C'. ($row+count($objetivosindividuales['notransversales'])-1));
                }
                    
                $this->excel->getActiveSheet()->setCellValue('A'.$row, 'DESCRIPTOR DE CARGO Competencias específicas');
                $this->excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setWrapText(TRUE);
            }
            
            $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight($this->_calculateHeight($objetivoindividual));
            $this->excel->getActiveSheet()->mergeCells('D'.$row.':F'. $row);
            $this->excel->getActiveSheet()->mergeCells('G'.$row.':I'. $row);
            $this->excel->getActiveSheet()->mergeCells('J'.$row.':L'. $row);
            $this->excel->getActiveSheet()->setCellValue('D'.$row, $objetivoindividual['dimension']);
            $this->excel->getActiveSheet()->setCellValue('G'.$row, $objetivoindividual['objetivo']);
            $this->excel->getActiveSheet()->setCellValue('J'.$row, $objetivoindividual['plan']);
            $this->excel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setWrapText(TRUE);
            $this->excel->getActiveSheet()->getStyle('J'.$row)->getAlignment()->setWrapText(TRUE);
            
            if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE'))
            { 
                $this->excel->getActiveSheet()->mergeCells('M'.($row).':M'. ($row+1));
                $this->excel->getActiveSheet()->setCellValue('M'.$row, $objetivoindividual['evaluacion_puntaje']);
                $this->excel->getActiveSheet()->getStyle('M'.$row)->getAlignment()->setWrapText(TRUE);
                
                $row++;
                
                $this->excel->getActiveSheet()->mergeCells('D'.$row.':L'. $row);
                $this->excel->getActiveSheet()->setCellValue('D'.$row, $objetivoindividual['evaluacion_observacion']);
            }
            
            $row++;
        }
        
        $row++;
        
        $this->excel->getActiveSheet()->mergeCells('A'.$row.':D'. $row);
        $this->excel->getActiveSheet()->setCellValue('A'.$row, 'El Trabajador Requiere Capacitación:');
        $this->excel->getActiveSheet()->setCellValue('E'.$row, ($capacitaciones) ? 'Si' : 'No');
        
        $row++;
        
        if($capacitaciones)
        {
            $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row+count($capacitaciones)-1))->applyFromArray($estilo_bordes);
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':D'. ($row+count($capacitaciones)-1));
            $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Sugerencia de Capacitación:');

            foreach($capacitaciones as $capacitacion)
            {
                $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight($this->_calculateHeight($capacitacion['descripcion']));
                $this->excel->getActiveSheet()->mergeCells('E'.$row.':M'. $row);
                $this->excel->getActiveSheet()->setCellValue('E'.$row, $capacitacion['descripcion']);
                $this->excel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setWrapText(TRUE);
                $row++;
            }

            $row++;
        }
        
        $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row))->applyFromArray($estilo_bordes);
        $this->excel->getActiveSheet()->mergeCells('A'.$row.':E'. $row);
        $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Observaciones, Comentarios y/o Sugerencias del evaluado:');
        $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight($this->_calculateHeight($formulario['observaciones']));
        $this->excel->getActiveSheet()->mergeCells('F'.$row.':M'. $row);
        $this->excel->getActiveSheet()->setCellValue('F'.$row, $formulario['observaciones']);
        $this->excel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setWrapText(TRUE);
        
        if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))
        {
            $row++;
            $row++;
        
            $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row))->applyFromArray($estilo_bordes);
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':E'. $row);
            $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Observaciones finales resultado evaluación:');
            $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight($this->_calculateHeight($resultado['observacion']));
            $this->excel->getActiveSheet()->mergeCells('F'.$row.':M'. $row);
            $this->excel->getActiveSheet()->setCellValue('F'.$row, $resultado['observacion']);
            $this->excel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setWrapText(TRUE);
        }
        
        if($formulario['apelacion'])
        {
            $row++;
            $row++;
        
            $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row))->applyFromArray($estilo_bordes);
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':E'. $row);
            $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Apelación del evaluado:');
            $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight($this->_calculateHeight($formulario['apelacion']));
            $this->excel->getActiveSheet()->mergeCells('F'.$row.':M'. $row);
            $this->excel->getActiveSheet()->setCellValue('F'.$row, $formulario['apelacion']);
            $this->excel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setWrapText(TRUE);
        }
        
        if($resultadoapelacion)
        {
            $row++;
            $row++;
        
            $this->excel->getActiveSheet()->getStyle('A'.$row.':M'. ($row))->applyFromArray($estilo_bordes);
            $this->excel->getActiveSheet()->mergeCells('A'.$row.':E'. $row);
            $this->excel->getActiveSheet()->setCellValue('A'.$row, 'Resultado apelación:');
            $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight($this->_calculateHeight($resultadoapelacion['observacion']));
            $this->excel->getActiveSheet()->mergeCells('F'.$row.':M'. $row);
            $this->excel->getActiveSheet()->setCellValue('F'.$row, $resultadoapelacion['observacion']);
            $this->excel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setWrapText(TRUE);
        }
        
        header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Formulario_'. $formulario['usuario'].'_'. $formulario['periodo'] .'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        $objWriter->save('php://output');
    }
    
    private function _calculateHeight($var)
    {
        $max = 0;
        
        if(is_array($var))
        {
            foreach($var as $item)
            {
                if(strlen($item) > $max) $max = strlen($item);
            }
        }
        else
        {
            if(strlen($var) > $max) $max = strlen($var);
        }
        
        if($max < $this->widthfactor) return $this->heightfactor;
        else return ceil($max/$this->widthfactor) * $this->heightfactor;
    }
    
    private function _grabar($id, $input)
    {
        $objetivosarea = $this->objetivoarea->listar($id);
        $nuevosobjetivosarea = (isset($input['objetivosarea'])) ? $input['objetivosarea'] : array();
        $objetivosindividuales = $this->objetivoindividual->listar($id);
        $nuevosobjetivosindividuales = (isset($input['objetivosindividuales'])) ? $input['objetivosindividuales'] : array();
        $capacitaciones = $this->capacitacion->listar($id);
        $nuevascapacitaciones = (isset($input['capacitaciones'])) ? $input['capacitaciones'] : array();
        
        /** Objetivos Área **/

        foreach($nuevosobjetivosarea as $key => $objetivoarea)
        {
            if(array_filter($objetivoarea))
            {
                $array['formulario'] = $id;
                $array['proyecto'] = (empty($objetivoarea['proyecto'])) ? NULL : $objetivoarea['proyecto'];
                $array['descripcion'] = (empty($objetivoarea['descripcion'])) ? NULL : $objetivoarea['descripcion'];
                $array['objetivo'] = (empty($objetivoarea['objetivo'])) ? NULL : $objetivoarea['objetivo'];
                $array['meta'] = (empty($objetivoarea['meta'])) ? NULL : $objetivoarea['meta'];
                $array['indicador'] = (empty($objetivoarea['indicador'])) ? NULL : $objetivoarea['indicador'];
                $array['plan'] = (empty($objetivoarea['plan'])) ? NULL : $objetivoarea['plan'];
                
                if(empty($objetivoarea['id']))
                {
                    unset($nuevosobjetivosarea[$key]);
                    $objetivoarea = $this->objetivoarea->insertar($array);
                    $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'objetivoarea', 'instancia' => $objetivoarea));
                }
                else
                {   
                    $this->objetivoarea->actualizar($objetivoarea['id'], $array);
                    $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'objetivoarea', 'instancia' => $objetivoarea['id']));
                }
                
                unset($array);
            }
            else
            {
                unset($nuevosobjetivosarea[$key]);
            }
        }
        
        foreach(array_diff(array_column($objetivosarea, 'id'), array_column($nuevosobjetivosarea, 'id')) as $id)
        {
            if($this->objetivoarea->eliminar($id)) $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'objetivoarea', 'instancia' => $id));
        }
        
        /** Fin Objetivos Área **/   
        
        /** Objetivos individuales **/
        
        foreach($nuevosobjetivosindividuales as $key => $objetivoindividual)
        {
            if(isset($objetivoindividual['competenciatransversal']))
            {
                $array['formulario'] = $id;
                $array['plan'] = (empty($objetivoindividual['plan'])) ? NULL : $objetivoindividual['plan'];
                
                if($this->objetivoindividual->actualizar($objetivoindividual['id'], $array)) $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'objetivoindividual', 'instancia' => $objetivoindividual['id']));
            }
            else
            {
                if(array_filter($objetivoindividual))
                {
                    $array['formulario'] = $id;
                    $array['dimension'] = (empty($objetivoindividual['dimension'])) ? NULL : $objetivoindividual['dimension'];
                    $array['objetivo'] = (empty($objetivoindividual['objetivo'])) ? NULL : $objetivoindividual['objetivo'];
                    $array['plan'] = (empty($objetivoindividual['plan'])) ? NULL : $objetivoindividual['plan'];
                    
                    if(empty($objetivoindividual['id']))
                    {
                        unset($nuevosobjetivosindividuales[$key]);
                        $objetivoindividual = $this->objetivoindividual->insertar($array);
                        $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'objetivoindividual', 'instancia' => $objetivoindividual));
                    }
                    else
                    {
                        $this->objetivoindividual->actualizar($objetivoindividual['id'], $array);
                        $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'objetivoindividual', 'instancia' => $objetivoindividual['id']));
                    }
                }
                else
                {
                    unset($nuevosobjetivosindividuales[$key]);
                }
            }
            
            unset($array);
        }
        
        foreach(array_diff(array_column($objetivosindividuales, 'id'), array_column($nuevosobjetivosindividuales, 'id')) as $id)
        {
            if($this->objetivoindividual->eliminar($id)) $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'objetivoindividual', 'instancia' => $id));
        }
        
        /** Fin Objetivos individuales **/
        
        /** Capacitación **/
        
        if($input['radio-capacitacion'])
        {
            foreach($nuevascapacitaciones as $key => $capacitacion)
            {
                if(array_filter($capacitacion))
                {
                    $array['formulario'] = $id;
                    $array['descripcion'] = (empty($capacitacion['descripcion'])) ? NULL : $capacitacion['descripcion'];

                    if(empty($capacitacion['id']))
                    {
                        unset($nuevascapacitaciones[$key]);
                        $capacitacion = $this->capacitacion->insertar($array);
                        $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'capacitacion', 'instancia' => $capacitacion));
                    }
                    else
                    {   
                        $this->capacitacion->actualizar($capacitacion['id'], $array);
                        $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'capacitacion', 'instancia' => $capacitacion['id']));
                    }

                    unset($array);
                }
                else
                {
                    unset($nuevascapacitaciones[$key]);
                }
            }

            foreach(array_diff(array_column($capacitaciones, 'id'), array_column($nuevascapacitaciones, 'id')) as $id)
            {
                if($this->capacitacion->eliminar($id)) $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'capacitacion', 'instancia' => $id));
            }
        }
        else
        {
            foreach($capacitaciones as $capacitacion)
            {
                if($this->capacitacion->eliminar($capacitacion['id'])) $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'capacitacion', 'instancia' => $capacitacion['id']));
            }
        }
        
        /** Fin Capacitación **/
        
        $formulario['tiponivel'] = (empty($input['tiponivel'])) ? NULL : $input['tiponivel'];
        $formulario['tipocontrato'] = (empty($input['tipocontrato'])) ? NULL : $input['tipocontrato'];
        $formulario['observaciones'] = (empty($input['observaciones'])) ? NULL : $input['observaciones'];
        
        $this->formulario->actualizar($id, $formulario, TRUE);
        
        return TRUE;
    }
}

/* End of file formulario.php */
/* Location: ./application/controllers/formulario.php */