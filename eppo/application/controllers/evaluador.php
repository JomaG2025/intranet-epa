<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluador extends CI_Controller {
    
    protected $mediciones;

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
        
        $this->load->model('periodo_model', 'periodo');
        $this->load->model('etapa_model', 'etapa');
        $this->load->model('formulario_model', 'formulario');
        $this->load->model('evaluador_model', 'evaluador');
        $this->load->model('evaluado_model', 'evaluado');
        $this->load->model('tiponivel_model', 'tiponivel');
        $this->load->model('tipocontrato_model', 'tipocontrato');
        $this->load->model('objetivoarea_model', 'objetivoarea');
        $this->load->model('objetivoindividual_model', 'objetivoindividual');
        $this->load->model('capacitacion_model', 'capacitacion');
        $this->load->model('retroalimentacion_model', 'retroalimentacion');
        $this->load->model('medicion_model', 'medicion');
        $this->load->model('evaluacion_model', 'evaluacion');
        $this->load->model('resultado_model', 'resultado');
        $this->load->model('resultadoapelacion_model', 'resultadoapelacion');
        $this->load->model('usuario_model', 'usuario');
        $this->load->model('auditoria_model', 'auditoria');
        $this->load->library('email');
        
        $this->mediciones = $this->medicion->listar();
    }
	
	public function index($periodo = NULL)
	{	
        $data['periodo_actual'] = $this->periodo->seleccionar_actual();
        $data['periodo'] = ( ! $periodo) ? $data['periodo_actual']['periodo'] : $periodo;
        $data['periodos'] = $this->periodo->listar();
        $data['evaluados'] = $this->formulario->listar_por_evaluador($this->session->userdata('usuario'), $data['periodo']);

        foreach($data['evaluados'] as $key => $evaluado)
        {
            $usuario = $this->usuario->seleccionar_intranet($evaluado['usuario']);
            $data['evaluados'][$key]['nombre'] = ($evaluado['nombre']) ? $evaluado['nombre'] : $usuario['nombre'];
            $data['evaluados'][$key]['cargo'] = $usuario['cargo'];
            $data['evaluados'][$key]['email'] = $usuario['email'];
        }
        
		$this->load->view('header');
		$this->load->view('evaluador/index', $data);
		$this->load->view('footer');
	}
    
    public function formulario($id = NULL)
    {
        if(empty($id)) redirect('evaluado', 'danger_01', TRUE);
        
        $data['periodo_actual'] = $this->periodo->seleccionar_actual();
        $data['etapa_actual'] = $this->etapa->seleccionar_actual($data['periodo_actual']['periodo']);
        
        if( ! $data['formulario'] = $this->formulario->seleccionar($id)) redirect('evaluador', 'danger_02', TRUE); 
        if( ! $data['evaluador'] = $this->evaluador->verificar($this->session->userdata('usuario'), $data['formulario']['periodo'], $data['formulario']['usuario'])) redirect('evaluador', 'danger_06', TRUE); 

        $data['evaluadores'] = $this->evaluado->listar_evaluadores($data['formulario']['usuario'], $data['formulario']['periodo']);
        $data['tipocontratos'] = $this->tipocontrato->listar();
        $data['tiponiveles'] = $this->tiponivel->listar();
        $data['objetivosarea'] = $this->objetivoarea->listar($data['formulario']['id']);
        $data['objetivosindividuales']['transversales'] = $this->objetivoindividual->listar($data['formulario']['id'], TRUE);
        $data['objetivosindividuales']['notransversales'] = $this->objetivoindividual->listar($data['formulario']['id'], FALSE);
        $data['capacitaciones'] = $this->capacitacion->listar($data['formulario']['id']);
        $data['mediciones'] = $this->medicion->listar();
        $data['resultado'] = $this->resultado->verificar($data['formulario']['id']);
        if(($data['resultadoapelacion'] = $this->resultadoapelacion->verificar($data['formulario']['id'])) OR ($data['formulario']['estadoformulario'] == 'APE')) $data['etapa_actual']['puedeevaluar'] = 0;
        
        foreach($data['evaluadores'] as $key => $evaluador)
        {
            $data['evaluadores'][$key] = $this->usuario->seleccionar_intranet($evaluador['usuario']);
        }
        
        $this->load->view('header');
		$this->load->view('evaluador/formulario', $data);
		$this->load->view('footer');
    }
    
    public function consensuar($id = NULL)
    {
        if(empty($id)) redirect('evaluado', 'danger_01', TRUE);
        
        $periodo_actual = $this->periodo->seleccionar_actual();
        $etapa_actual = $this->etapa->seleccionar_actual($data['periodo_actual']['periodo']);
        
        if( ! $formulario = $this->formulario->seleccionar($id)) redirect('evaluador', 'danger_02', TRUE);
        if( ! $this->evaluador->verificar($this->session->userdata('usuario'), $periodo_actual['periodo'], $formulario['usuario'])) redirect('evaluador/formulario/' . $formulario['id'], 'danger_06');  
        
        if(
            ($formulario['estadoformulario'] == 'CON') OR 
            ($formulario['estadoformulario'] == 'EVA') OR 
            ($formulario['estadoformulario'] == 'APE')
        ){
            redirect('evaluador/formulario/' . $formulario['id'], 'danger_06'); 
        }
        
        if($this->formulario->actualizar($formulario['id'], array('estadoformulario' => 'CON')))
        {
            if($evaluado = $this->usuario->seleccionar_intranet($formulario['usuario']))
            {
                $this->email->from('no-responder@puertoarica.com', 'Evaluación EPPO');
                $this->email->subject('Su Formulario EPPO '. $periodo_actual['periodo'] .' ha sido consensuado.'); 
                $this->email->to($evaluado['email']);
                $this->email->message($this->load->view('evaluador/email/consensuar', array('evaluado' => $evaluado, 'periodo' => $periodo_actual), TRUE));
                $this->email->send();
            }
            
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'formulario', 'instancia' => $formulario['id']));
            redirect('evaluador/formulario/' . $formulario['id'], 'success_02');
        }
        
        redirect('evaluador/formulario/' . $formulario['id'], 'warning_01');        
    }
    
    public function reabrir($id = NULL)
    {
        if(empty($id)) redirect('evaluado', 'danger_01', TRUE);
        
        $periodo_actual = $this->periodo->seleccionar_actual();
        $etapa_actual = $this->etapa->seleccionar_actual($data['periodo_actual']['periodo']);
        
        if( ! $formulario = $this->formulario->seleccionar($id)) redirect('evaluador', 'danger_02', TRUE);
        if( ! $this->evaluador->verificar($this->session->userdata('usuario'), $periodo_actual['periodo'], $formulario['usuario'])) redirect('evaluador/formulario/' . $formulario['id'], 'danger_06');   
        
        if($formulario['estadoformulario'] != 'CON') redirect('evaluador/formulario/' . $formulario['id'], 'danger_06', TRUE);
        
        if($this->formulario->actualizar($formulario['id'], array('estadoformulario' => 'REA')))
        {
            if($evaluado = $this->usuario->seleccionar_intranet($formulario['usuario']))
            {
                $this->email->from('no-responder@puertoarica.com', 'Evaluación EPPO');
                $this->email->subject('Su Formulario EPPO '. $periodo_actual['periodo'] .' ha sido re-abierto.'); 
                $this->email->to($evaluado['email']);
                $this->email->message($this->load->view('evaluador/email/reabrir', array('evaluado' => $evaluado, 'periodo' => $periodo_actual), TRUE));
                $this->email->send();
            }
            
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'formulario', 'instancia' => $formulario['id']));
            redirect('evaluador/formulario/' . $formulario['id'], 'success_02'); 
        }
        
        redirect('evaluador/formulario/' . $formulario['id'], 'warning_01');  
    }
    
    public function evaluar()
    {
        if(
			( ! $this->input->post('evaluacion')) OR
			( ! $this->input->post('resultado')) OR
			( ! $this->input->post('id'))
		){
            redirect('evaluado', 'danger_01', TRUE);
		}
        
        if( ! $formulario = $this->formulario->seleccionar($this->input->post('id'))) redirect('evaluado', 'danger_02', TRUE);
        
        $periodo_actual = $this->periodo->seleccionar_actual();
        $etapa_actual = $this->etapa->seleccionar_actual($periodo_actual['periodo']);
        
        if(($periodo_actual['periodo'] != $formulario['periodo']) OR ( ! $this->evaluador->verificar($this->session->userdata('usuario'), $periodo_actual['periodo'], $formulario['usuario']))) redirect('evaluador/formulario/' . $formulario['id'], 'danger_06');
        
        if($this->resultadoapelacion->verificar($data['formulario']['id'])) $etapa_actual['puedeevaluar'] = 0;
        
        if(
            ( ! $etapa_actual['puedeevaluar']) OR 
            ($formulario['estadoformulario'] == 'INI') OR 
            ($formulario['estadoformulario'] == 'ENV') OR 
            ($formulario['estadoformulario'] == 'RET') OR
            ($formulario['estadoformulario'] == 'REA') OR
            ($formulario['estadoformulario'] == 'APE')
        ){
            redirect('evaluador/formulario/' . $formulario['id'], 'danger_06'); 
        }
        
        $evaluaciones = $this->input->post('evaluacion');
        $resultado = $this->input->post('resultado');
        $puntajes = array(
            'objetivoarea' => array(),
            'objetivoindividual' => array()
        );
        
        /** Objetivos Área **/
        
        if(isset($evaluaciones['objetivosarea']))
        {
            foreach($evaluaciones['objetivosarea'] as $evaluacion)
            {
                if( ! $this->objetivoarea->seleccionar($evaluacion['objetivoarea'])) redirect('evaluador/formulario/' . $formulario['id'], 'danger_02'); 

                $array['objetivoarea'] = $evaluacion['objetivoarea'];
                $array['sigla'] = $evaluacion['sigla'];
                $array['observacion'] = ($evaluacion['observacion']) ? $evaluacion['observacion'] : NULL;
                $array['puntaje'] = $this->_puntaje($evaluacion['sigla']);

                if($tmp = $this->evaluacion->verificar($evaluacion['objetivoarea']))
                {
                    if($this->evaluacion->actualizar($tmp['id'], $array)) $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'evaluacion', 'instancia' => $tmp['id']));
                }
                else
                {
                    if($id = $this->evaluacion->insertar($array)) $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'evaluacion', 'instancia' => $id));
                }

                array_push($puntajes['objetivoarea'], $array['puntaje']);
                unset($array);
            }
        }
        
        /** Fin Objetivos Área **/
        
        /** Objetivos Individuales **/
        
        if(isset($evaluaciones['objetivosindividuales']))
        {
            foreach($evaluaciones['objetivosindividuales'] as $evaluacion)
            {
                if( ! $this->objetivoindividual->seleccionar($evaluacion['objetivoindividual'])) redirect('evaluador/formulario/' . $formulario['id'], 'danger_02'); 

                $array['objetivoindividual'] = $evaluacion['objetivoindividual'];
                $array['sigla'] = $evaluacion['sigla'];
                $array['observacion'] = ($evaluacion['observacion']) ? $evaluacion['observacion'] : NULL;
                $array['puntaje'] = $this->_puntaje($evaluacion['sigla']);

                if($tmp = $this->evaluacion->verificar(NULL, $evaluacion['objetivoindividual']))
                {
                    if($this->evaluacion->actualizar($tmp['id'], $array)) $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'evaluacion', 'instancia' => $tmp['id']));
                }
                else
                {
                    if($id = $this->evaluacion->insertar($array)) $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'evaluacion', 'instancia' => $id));
                }

                array_push($puntajes['objetivoindividual'], $array['puntaje']);
                unset($array);
            }
        }
        
        /** Fin Objetivos Individuales **/
        
        $resultado['formulario'] = $formulario['id'];
        $resultado['usuario'] = $this->session->userdata('usuario');
        $resultado['observacion'] = ($resultado['observacion']) ? $resultado['observacion'] : NULL;
        $resultado['puntaje'] = round((($puntajes['objetivoarea']) ? ($this->_promedio($puntajes['objetivoarea']) * ($periodo_actual['ponderacionobjetivoarea']/100)) : 0) + (($puntajes['objetivoindividual'])? ($this->_promedio($puntajes['objetivoindividual']) * ($periodo_actual['ponderacionobjetivoindividual']/100)) : 0));
        
        if($tmp = $this->resultado->verificar($formulario['id']))
        {
            $resultado['modificado'] = date('Y-m-d H:i:s');
            if($this->resultado->actualizar($tmp['id'], $resultado)) $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'resultado', 'instancia' => $tmp['id']));
        }
        else
        {
            if($id = $this->resultado->insertar($resultado)) $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'resultado', 'instancia' => $id));
        }
        
        $this->formulario->actualizar($formulario['id'], array('estadoformulario' => 'EVA'), TRUE);
        $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'formulario', 'instancia' => $formulario['id']));
        
        if($evaluado = $this->usuario->seleccionar_intranet($formulario['usuario']))
        {
            $this->email->from('no-responder@puertoarica.com', 'Evaluación EPPO');
            $this->email->subject('Su Formulario EPPO '. $periodo_actual['periodo'] .' ha sido evaluado.'); 
            $this->email->to($evaluado['email']);
            $this->email->message($this->load->view('evaluador/email/evaluar', array('evaluado' => $evaluado, 'periodo' => $periodo_actual), TRUE));
            $this->email->send();
        }
        
        redirect('evaluador/formulario/' . $formulario['id'], 'success_02');       
    }
    
    public function retroalimentar()
    {
        if(
			( ! $this->input->post('retroalimentacion')) OR
			( ! $this->input->post('formulario')) OR
			( ! $this->input->post('item')) OR
			( ! $this->input->post('id'))
		){
            redirect('evaluado', 'danger_01', TRUE);
		}
        
        switch($this->input->post('item'))
        {
            case 'objetivoarea' : 
                
                if( ! $item = $this->objetivoarea->seleccionar($this->input->post('id'))) redirect('evaluador', 'danger_01', TRUE); 
                
            break;
                
            case 'objetivoindividual' : 
                
                if( ! $item = $this->objetivoindividual->seleccionar($this->input->post('id'))) redirect('evaluador', 'danger_01', TRUE); 
                
            break;
                
            case 'capacitacion' : 
                
                if( ! $item = $this->capacitacion->seleccionar($this->input->post('id'))) redirect('evaluador', 'danger_01', TRUE); 
                
            break;
                
            default:
                
                redirect('evaluado', 'danger_01');
                
            break;
        }
        
        $periodo_actual = $this->periodo->seleccionar_actual(); 
        
        if( ! $formulario = $this->formulario->seleccionar($item['formulario'])) redirect('evaluador', 'danger_02', TRUE); 
        
        if( ! $this->evaluador->verificar($this->session->userdata('usuario'), $periodo_actual['periodo'], $usuario)) redirect('evaluador/formulario/' . $formulario['id'], 'danger_06');     
        
        if(
            ($formulario['estadoformulario'] == 'CON') OR 
            ($formulario['estadoformulario'] == 'EVA') OR 
            ($formulario['estadoformulario'] == 'APE') 
        ){
            redirect('evaluador/formulario/' . $formulario['id'], 'danger_06'); 
        }
        
        $retroalimentacion['usuario'] = $this->session->userdata('usuario');
        $retroalimentacion[$this->input->post('item')] = $item['id'];
        $retroalimentacion['retroalimentacion'] = $this->input->post('retroalimentacion');
        
        if($retroalimentacion = $this->retroalimentacion->insertar($retroalimentacion))
		{   
            $this->formulario->actualizar($item['formulario'], array('estadoformulario' => 'RET'));   
			$this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'formulario', 'instancia' => $item['formulario']));
			$this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'retroalimentacion', 'instancia' => $retroalimentacion));
            
            if($evaluado = $this->usuario->seleccionar_intranet($formulario['usuario']))
            {
                $this->email->from('no-responder@puertoarica.com', 'Evaluación EPPO');
                $this->email->subject('Su Formulario EPPO '. $periodo_actual['periodo'] .' ha recibido una retroalimentación.'); 
                $this->email->to($evaluado['email']);
                $this->email->message($this->load->view('evaluador/email/retroalimentar', array('evaluado' => $evaluado, 'periodo' => $periodo_actual, 'item' => $item, 'tipoitem' => $this->input->post('item'), 'retroalimentacion' => $this->input->post('retroalimentacion')), TRUE));
                $this->email->send();
            }
            
            redirect('evaluador/formulario/' . $formulario['id'] . '#' . $this->input->post('item'), 'success_01');
		}
		else
		{
            redirect('evaluador/formulario/' . $formulario['id'] . '#' . $this->input->post('item'), 'danger_01');
		}
    }
    
    private function _puntaje($sigla)
    {
        foreach($this->mediciones as $medicion)
        {
            if($sigla == $medicion['sigla']) return $medicion['puntaje'];   
        }
    }
    
    private function _promedio($puntajes)
    {
        return round(array_sum($puntajes)/count($puntajes));   
    }
}

/* End of file evaluado.php */
/* Location: ./application/controllers/evaluado.php */