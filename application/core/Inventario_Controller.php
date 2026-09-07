<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Inventario_Controller - clase base SOLO para el modulo Inventario
| -------------------------------------------------------------------
| A proposito NO se llama MY_Controller: ese nombre es el mecanismo de
| extension GLOBAL de CodeIgniter 2 (application/core/MY_Controller.php,
| $config['subclass_prefix']). Crear o modificar ese archivo puede
| afectar CUALQUIER controlador de la intranet que llegue a heredar de
| el, aunque sea sin querer. Esta clase vive en el mismo directorio
| application/core/ (por convencion, es donde CI2 espera las clases base)
| pero con un nombre propio: CI2 NO la autocarga sola -- cada controlador
| del modulo la trae explicitamente con require_once ANTES de declarar su
| propia clase (ver inventario.php, inventario_importacion.php,
| admin/inventario_catalogos.php, admin/inventario_usuarios.php). Asi
| queda aislada al modulo, tal como se pidio, sin tocar nada global.
|
| Que hace, para TODOS los controladores del modulo por igual:
|   1) Exige sesion iniciada en la intranet (iaual que antes: si no hay
|      sesion, redirect('login')).
|   2) Abre la conexion compartida a intranet_inventario ($this->db_inv),
|      igual que hacia cada controlador por separado antes.
|   3) Exige ademas autorizacion PROPIA del modulo (inv_tiene_acceso(),
|      ver application/helpers/inv_permisos_helper.php): si el usuario
|      tiene sesion en la intranet pero NO tiene una fila activa en
|      inv_usuarios, se le muestra la pantalla "Acceso no autorizado"
|      (application/views/inventario/acceso_denegado.php) y la ejecucion
|      se detiene ahi -- ningun metodo de ningun controlador del modulo
|      llega a ejecutarse para un usuario no autorizado, sin importar la
|      URL que haya escrito.
|
| Lo que CADA controlador sigue haciendo por su cuenta (llamando a
| parent::__construct() primero): cargar SUS PROPIOS modelos/helpers, y
| exigir el privilegio MAS ESPECIFICO que le corresponda (ej. solo ADM
| para administrar catalogos/usuarios/importar Excel/acciones masivas;
| ADM o EDI para crear/editar activos) via inv_es_admin()/
| inv_puede_editar() -- eso es distinto por controlador y hasta por
| metodo, no pertenece aca.
| -------------------------------------------------------------------
*/
class Inventario_Controller extends CI_Controller {

    protected $db_inv;

    public function __construct()
    {
        parent::__construct();

        if ( ! $this->session->userdata('id')) redirect('login');

        $this->db_inv = $this->load->database('inventario', TRUE);

        $this->load->helper('inv_permisos');

        if ( ! inv_tiene_acceso())
        {
            $this->_mostrar_acceso_denegado();
        }
    }

    /*
    | Renderiza la pantalla de acceso denegado con el mismo header/footer
    | del modulo (para que se vea integrada, no un error generico) y
    | corta la ejecucion ahi mismo -- ningun controlador que herede de
    | esta clase deberia seguir ejecutando codigo despues de esto.
    */
    protected function _mostrar_acceso_denegado()
    {
        $this->load->view('inventario/header');
        $this->load->view('inventario/acceso_denegado');
        $this->load->view('inventario/footer');
        exit;
    }
}

/* End of file Inventario_Controller.php */
