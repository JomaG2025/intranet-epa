<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan_emergencia extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if( ! $this->session->userdata('id')) redirect('login');
    }

    public function index()
    {
        $data['title'] = 'Plan de Emergencia y Evacuación';

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/header.php')) {
            include $_SERVER['DOCUMENT_ROOT'].'/header.php';
        }

        $this->load->view('plan_emergencia/index', $data);

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/footer.php')) {
            include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
        }
    }
}
