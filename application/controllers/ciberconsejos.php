<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ciberconsejos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url']);
    }

    public function index() {
        $data['title'] = 'Ciber-Consejos';

        // --- usa los mismos includes globales que ya tienes en la raíz ---
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/header.php')) {
            include $_SERVER['DOCUMENT_ROOT'].'/header.php';
        }

        // contenido central (la vista SIN <html>/<head>)
        $this->load->view('ciberconsejos/index', $data);

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/footer.php')) {
            include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
        }
    }
}
