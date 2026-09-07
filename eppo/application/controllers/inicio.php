<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
    }
	
	public function index()
	{	
        $this->load->model('etapa_model', 'etapa');
        $this->load->model('periodo_model', 'periodo');
        $this->load->model('evaluado_model', 'evaluado');
        $this->load->model('formulario_model', 'formulario');
        $this->load->model('resultado_model', 'resultado');
        $this->load->model('usuario_model', 'usuario');
        $this->load->model('resultadoapelacion_model', 'resultadoapelacion');
        
        $data['periodo_actual'] = $this->periodo->seleccionar_actual();
        $data['etapas'] = $this->etapa->listar_por_periodo($data['periodo_actual']['periodo']);
        
        if( ! $data['etapa_actual'] = $this->etapa->seleccionar_actual($data['periodo_actual']['periodo']) AND $data['etapas']) $data['etapa_actual'] = $data['etapas'][count($data['etapas']) - 1];
        
        $data['etapa_anterior'] = $this->etapa->seleccionar_anterior($data['periodo_actual']['periodo'],  $data['etapa_actual']['correlativo']);
        $data['etapa_siguiente'] = $this->etapa->seleccionar_siguiente($data['periodo_actual']['periodo'],  $data['etapa_actual']['correlativo']);
        
        if($data['etapa_anterior'])
        {
            $data['porcentaje_avance'] = round((strtotime($data['etapa_actual']['limite']) > strtotime(date('Y-m-d'))) ? abs(100-((dias_diferencia(date('Y-m-d'), $data['etapa_actual']['limite'])) * 100) / (dias_diferencia($data['etapa_anterior']['limite'], $data['etapa_actual']['limite']))) : 100);
        }
        else
        {
            $data['porcentaje_avance'] = round((strtotime($data['etapa_actual']['limite']) > strtotime(date('Y-m-d'))) ? abs(100-((dias_diferencia(date('Y-m-d'), $data['etapa_actual']['limite'])) * 100) / (dias_diferencia($data['periodo_actual']['periodo'].'-01-01', $data['etapa_actual']['limite']))) : 100);
        }

        if($data['formulario'] = $this->formulario->seleccionar_por_usuario_periodo($this->session->userdata('usuario'), $data['periodo_actual']['periodo']))
        {
            $data['resultado'] = $this->resultado->verificar($data['formulario']['id']); 
            $data['resultadoapelacion'] = $this->resultadoapelacion->verificar($data['formulario']['id']); 
        }
        
        $data['evaluados'] = $this->formulario->listar_por_evaluador($this->session->userdata('usuario'), $data['periodo_actual']['periodo']);
        $data['evaluadores'] = $this->evaluado->listar_evaluadores($this->session->userdata('usuario'), $data['periodo_actual']['periodo']);
        
        foreach($data['evaluadores'] as $key => $evaluador)
        {
            $data['evaluadores'][$key] = $this->usuario->seleccionar_intranet($evaluador['usuario']);
        }
        
        foreach($data['evaluados'] as $key => $evaluado)
        {
            $usuario = $this->usuario->seleccionar_intranet($evaluado['usuario']);
            $data['evaluados'][$key]['nombre'] = ($evaluado['nombre']) ? $evaluado['nombre'] : $usuario['nombre'];
            $data['evaluados'][$key]['cargo'] = $usuario['cargo'];
            $data['evaluados'][$key]['email'] = $usuario['email'];
        }
        
		$this->load->view('header');
		$this->load->view('inicio/index', $data);
		$this->load->view('footer');
	}	
}

/* End of file inicio.php */
/* Location: ./application/controllers/inicio.php */