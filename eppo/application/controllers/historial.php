<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historial extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        $this->load->model('periodo_model', 'periodo');
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
        $this->load->model('usuario_model', 'usuario');
        $this->load->model('resultadoapelacion_model', 'resultadoapelacion');
        
        if( ! $this->session->userdata('id')) redirect('login');
    }
	
	public function index()
	{	
        $data['periodo_actual'] = $this->periodo->seleccionar_actual();
        $data['formularios'] = $this->formulario->listar($this->session->userdata('usuario'));
       
		$this->load->view('header');
		$this->load->view('historial/index', $data);
		$this->load->view('footer');
	}
    
    public function formulario($id = NULL)
    {
        if(empty($id)) redirect('historial', 'danger_01', TRUE);
        
        if( ! $data['formulario'] = $this->formulario->seleccionar($id)) redirect('historial', 'danger_02', TRUE);
        if($data['formulario']['usuario'] != $this->session->userdata('usuario')) redirect('historial', 'danger_06', TRUE);

        $data['evaluadores'] = $this->evaluado->listar_evaluadores($data['formulario']['usuario'], $data['formulario']['periodo']);
        $data['tipocontratos'] = $this->tipocontrato->listar();
        $data['tiponiveles'] = $this->tiponivel->listar();
        $data['objetivosarea'] = $this->objetivoarea->listar($data['formulario']['id']);
        $data['objetivosindividuales']['transversales'] = $this->objetivoindividual->listar($data['formulario']['id'], TRUE);
        $data['objetivosindividuales']['notransversales'] = $this->objetivoindividual->listar($data['formulario']['id'], FALSE);
        $data['capacitaciones'] = $this->capacitacion->listar($data['formulario']['id']);
        $data['mediciones'] = $this->medicion->listar();
        $data['resultado'] = $this->resultado->verificar($data['formulario']['id']);
        $data['resultadoapelacion'] = $this->resultadoapelacion->verificar($data['formulario']['id']);
        $data['rango'] = $this->medicion->rango();
        
        foreach($data['evaluadores'] as $key => $evaluador)
        {
            $data['evaluadores'][$key] = $this->usuario->seleccionar_intranet($evaluador['usuario']);
        }
        
        $this->load->view('header');
		$this->load->view('historial/formulario', $data);
		$this->load->view('footer');
    }
}

/* End of file historial.php */
/* Location: ./application/controllers/historial.php */