<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyectos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cargamos la base de datos de tu intranet automáticamente
        $this->load->database();
        // Aquí usualmente va la validación de si el usuario tiene $_SESSION activa
    }

    public function index() {
        // Consultamos a la base de datos usando las herramientas nativas de tu framework
        $query = $this->db->query("SELECT * FROM pry_estados");
        $datos['estados'] = $query->result_array();

        // ---------------------------------------------------------
        // AQUÍ CARGAMOS LAS PANTALLAS (Vistas)
        // OJO: Esto puede variar según cómo esté armada tu intranet.
        // Generalmente se cargan el header, el menú, el contenido y el footer.
        // ---------------------------------------------------------
        
        // $this->load->view('includes/header');
        // $this->load->view('includes/sidebar');
        
        // Cargamos nuestra nueva vista principal y le pasamos los datos
        $this->load->view('proyectos/inicio', $datos);
        
        // $this->load->view('includes/footer');
    }
}