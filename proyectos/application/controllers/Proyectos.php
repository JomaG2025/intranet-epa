<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyectos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        // Esto verifica que seas Jorge Bernal (o un usuario activo)
        if (!$this->session->userdata('usuario')) { redirect('login'); }
    }

    public function index() {
        // Consultamos tus 28 proyectos
        $query = $this->db->query("SELECT p.*, e.nombre AS estado_nombre 
                                   FROM pry_proyectos p 
                                   LEFT JOIN pry_estados e ON p.id_estado = e.id 
                                   ORDER BY p.id DESC");
        $data['proyectos'] = $query->result_array();
        
        // Cargamos el diseño oficial que rescatamos de relacionados
        $this->load->view('header'); 
        $this->load->view('proyectos/index', $data);
        $this->load->view('footer');
    }
}