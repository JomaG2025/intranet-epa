<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditoria extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if($this->session->userdata('privilegio') != 'ADM') redirect('', 'danger_06');
		
		$this->load->model('auditoria_model', 'auditoria');
    }
	
	public function index()
	{
		$data['anos'] = $this->auditoria->listar_ano();
		$data['auditorias'] = array();
		$data['filtro'] = NULL;
		$data['valor'] = NULL;
		
		if($this->input->get('filtro'))
		{
			if($this->input->get('desde') AND $this->input->get('hasta') AND ($this->input->get('filtro') == 'rango'))
			{
                if(date($this->input->get('desde')) >= date($this->input->get('hasta')))
                {
                    redirect('auditoria', 'danger_01', TRUE);
                }
                else
                {
                    $data['filtro'] = 'rango';
                    $data['desde'] = $this->input->get('desde');
                    $data['hasta'] = $this->input->get('hasta');
                    $data['auditorias'] = $this->auditoria->listar($this->input->get('filtro'), $this->input->get('desde'), $this->input->get('hasta'));
                }
			}

			if($this->input->get('dia') AND ($this->input->get('filtro') == 'dia'))
			{
				$data['filtro'] = 'dia';
				$data['valor'] = $this->input->get('dia');
				$data['auditorias'] = $this->auditoria->listar($this->input->get('filtro'), $this->input->get('dia'));
			}

			if($this->input->get('mes') AND ($this->input->get('filtro') == 'mes'))
			{
				$data['filtro'] = 'mes';
				$data['valor'] = $this->input->get('mes');
				$data['auditorias'] = $this->auditoria->listar($this->input->get('filtro'), $this->input->get('mes'));
			}			
		}	

		$this->load->view('header');
		$this->load->view('admin/auditoria/index', $data);
		$this->load->view('footer');
	}	
}

/* End of file auditoria.php */
/* Location: ./application/controllers/admin/auditoria.php */