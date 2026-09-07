<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Boletines extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        
        if( ! $this->session->userdata('id')) redirect('login');
    }

	public function index()
	{		
		$this->load->view('header');
		$this->load->view('boletines/index');
		$this->load->view('footer');
	}
    
    public function descargar($numero = NULL)
    {
        if(empty($numero)) redirect('boletines', 'danger_01', TRUE);
        
        if( ! is_file($this->config->item('upload_path') . 'boletines/boletin'. str_pad($numero, 2, '0', STR_PAD_LEFT). '.pdf'))
        {
            redirect('boletines', 'danger_02', TRUE);
        }
        else
        {
            $this->load->helper('large_download');
            force_download('boletin'. str_pad($numero, 2, '0', STR_PAD_LEFT). '.pdf', $this->config->item('upload_path') . 'boletines/boletin'. str_pad($numero, 2, '0', STR_PAD_LEFT). '.pdf');
        }
    }
}

/* End of file boletines.php */
/* Location: ./application/controllers/boletines.php */