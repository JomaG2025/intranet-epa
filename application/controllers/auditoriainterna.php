<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditoriainterna extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
    }

	public function index()
	{		
		$this->load->view('header');
		$this->load->view('auditoriainterna/index');
		$this->load->view('footer');
	}
    
    public function descargar($doc = NULL)
    {
        if(empty($doc)) redirect('auditoriainterna', 'danger_01', TRUE);
        
        if( ! is_file($this->config->item('upload_path') . 'auditoriainterna/'. $doc .'.pdf'))
        {
            redirect('auditoriainterna', 'danger_02', TRUE);
        }
        else
        {
            $this->load->helper('large_download');
            force_download($doc .'.pdf', $this->config->item('upload_path') . 'auditoriainterna/'. $doc .'.pdf');
        }
    }
}

/* End of file auditoriainterna.php */
/* Location: ./application/controllers/auditoriainterna.php */