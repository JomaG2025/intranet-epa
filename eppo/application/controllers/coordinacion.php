<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coordinacion extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
		if(($this->session->userdata('privilegio') != 'ADM') AND ($this->session->userdata('privilegio') != 'COR')) redirect('', 'danger_06');
		
		$this->load->model('periodo_model', 'periodo');
		$this->load->model('evaluador_model', 'evaluador');
		$this->load->model('formulario_model', 'formulario');
		$this->load->model('usuario_model', 'usuario');
    }
	
	public function index($periodo = NULL)
	{
        $data['periodo_actual'] = $this->periodo->seleccionar_actual();        
        $data['periodo'] = ( ! $periodo) ? $data['periodo_actual']['periodo'] : $periodo;
        $data['periodos'] = $this->periodo->listar();
        $data['evaluadores'] = $this->evaluador->listar($data['periodo'], TRUE);
        
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
		$this->load->view('coordinacion/index', $data);
		$this->load->view('footer');
	}
}

/* End of file coordinacion.php */
/* Location: ./application/controllers/coordinacion.php */