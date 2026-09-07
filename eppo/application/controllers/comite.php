<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comite extends CI_Controller {
    
    protected $periodo_actual;

	public function __construct()
    {
        parent::__construct();
		
		$this->load->model('periodo_model', 'periodo');
        $this->load->model('formulario_model', 'formulario');
        $this->load->model('comite_model', 'comite');
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
        
        $this->periodo_actual = $this->periodo->seleccionar_actual();
        
        if(( ! $this->comite->verificar($this->session->userdata('usuario'), $this->periodo_actual['periodo'])) AND ($this->session->userdata('privilegio') != 'ADM')) redirect('', 'danger_06');
    }
	
	public function index()
	{
        $data['periodo_actual'] = $this->periodo_actual;
        $data['evaluadores'] = $this->evaluador->listar($data['periodo_actual']['periodo'], TRUE);
        
        foreach($data['evaluadores'] as $key => $evaluador)
        {
            if( ! $evaluador['usuario_eliminado'])
            {
                $usuario = $this->usuario->seleccionar_intranet($evaluador['usuario']);
                $data['evaluadores'][$key]['nombre'] = $usuario['nombre'];
                $data['evaluadores'][$key]['cargo'] = $usuario['cargo'];
                $data['evaluadores'][$key]['email'] = $usuario['email'];
            }
            
            $data['evaluadores'][$key]['evaluados'] = $this->formulario->listar_por_evaluador($evaluador['usuario'], $evaluador['periodo']); 
            
            foreach($data['evaluadores'][$key]['evaluados'] as $key2 => $evaluado)
            {
                $usuario = $this->usuario->seleccionar_intranet($evaluado['usuario']);
                $data['evaluadores'][$key]['evaluados'][$key2]['nombre'] = ($evaluado['nombre']) ? $evaluado['nombre'] : $usuario['nombre'];
                $data['evaluadores'][$key]['evaluados'][$key2]['cargo'] = $usuario['cargo'];
                $data['evaluadores'][$key]['evaluados'][$key2]['email'] = $usuario['email'];
            }
        }

		$this->load->view('header');
		$this->load->view('comite/index', $data);
		$this->load->view('footer');
	}
    
    public function formulario($id = NULL)
    {
        if(empty($id)) redirect('comite', 'danger_01', TRUE);
        
        $data['periodo_actual'] = $this->periodo_actual;
        if( ! $data['formulario'] = $this->formulario->seleccionar($id)) redirect('comite', 'danger_02', TRUE);

        $data['evaluadores'] = $this->evaluado->listar_evaluadores($data['formulario']['usuario'], $data['periodo_actual']['periodo']);
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
		$this->load->view('comite/formulario', $data);
		$this->load->view('footer');
    }
    
    public function apelacion()
    {
        if(
            ( ! $this->input->post('formulario')) OR
            ( ! $this->input->post('resultadoapelacion'))
        ){
            redirect('comite', 'danger_01', TRUE);
        }
        
        $resultadoapelacion = $this->input->post('resultadoapelacion');
        $rango = $this->medicion->rango();
        
        if((empty($resultadoapelacion['observacion'])) OR (empty($resultadoapelacion['puntaje']))) redirect('comite', 'danger_01', TRUE);
        $resultadoapelacion['puntaje']= intval($resultadoapelacion['puntaje']);
        
        if(($resultadoapelacion['puntaje'] < $rango['minimo']) AND ($resultadoapelacion['puntaje'] > $rango['maximo'])) redirect('comite', 'danger_01', TRUE);
        if( ! $formulario = $this->formulario->seleccionar($this->input->post('formulario'))) redirect('comite', 'danger_02', TRUE);
        if($this->resultadoapelacion->verificar($formulario['id'])) redirect('comite/formulario'. $formulario['id'], 'danger_03', TRUE);
        if($formulario['estadoformulario'] != 'APE') redirect('comite/formulario'. $formulario['id'], 'danger_06', TRUE);
        
        $array['formulario'] = $formulario['id'];
        $array['usuario'] = $this->session->userdata('usuario');
        $array['puntaje'] = $resultadoapelacion['puntaje'];
        $array['observacion'] = $resultadoapelacion['observacion'];
        
        if($id = $this->resultadoapelacion->insertar($array)) $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'resultadoapelacion', 'instancia' => $id));
        
        $this->formulario->actualizar($formulario['id'], array('estadoformulario' => 'EVA'), TRUE);
        $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'formulario', 'instancia' => $formulario['id']));
        
        if($evaluado = $this->usuario->seleccionar_intranet($formulario['usuario']))
        {
            $this->email->from('no-responder@puertoarica.com', 'Evaluación EPPO');
            $this->email->subject('Su Apelación del Formulario EPPO '. $formulario['periodo'] .' ha sido resuelta.'); 
            $this->email->to($evaluado['email']);
            $this->email->message($this->load->view('comite/email/apelacion', array('evaluado' => $evaluado, 'periodo' => $formulario['periodo']), TRUE));
            $this->email->send();
        }
        
        redirect('comite/formulario/' . $formulario['id'], 'success_02'); 
    }
}

/* End of file comite.php */
/* Location: ./application/controllers/comite.php */