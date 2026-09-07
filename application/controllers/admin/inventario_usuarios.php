<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Inventario_usuarios - Modulo Inventario
| -------------------------------------------------------------------
| Administracion de QUIEN tiene acceso al modulo Inventario y con que
| privilegio (tabla inv_usuarios, en intranet_inventario). Solo ADM del
| modulo. NO crea usuarios nuevos en la intranet -- el usuario ya debe
| existir ahi; este controlador solo autoriza (o desautoriza) su acceso
| a Inventario. NO duplica password/nombre/correo: eso sigue viviendo
| unicamente en la tabla `usuario` de `intranet`.
|
| Alcanzable en su nombre tecnico (admin/inventario_usuarios/...) y,
| pensado para usarse asi, en rutas amigables (/inventario/admin/usuarios/...)
| definidas en parches/05_rutas_inventario.md.
|
| Nunca hace un DELETE fisico de una autorizacion: desactivar (activo = 0)
| conserva el registro para trazabilidad -- ver activo().
| -------------------------------------------------------------------
*/
require_once(APPPATH . 'core/Inventario_Controller.php');

class Inventario_usuarios extends Inventario_Controller {

    public function __construct()
    {
        parent::__construct();

        if ( ! inv_es_admin()) redirect('inventario', 'danger_06', TRUE);

        $this->load->model('inventario_usuario_model', 'inv_usuario');
        $this->inv_usuario->set_conexion($this->db_inv);

        // Auditoria general: conexion normal (intranet), sin cambios.
        $this->load->model('auditoria_model', 'auditoria');

        $this->load->helper(array('inventario', 'inv_csrf'));
        $this->lang->load('inventario', 'spanish');
    }

    public function index()
    {
        $data['usuarios'] = $this->inv_usuario->listar();

        $this->load->view('inventario/header');
        $this->load->view('admin/inventario_usuarios/index', $data);
        $this->load->view('inventario/footer');
    }

    public function nuevo()
    {
        $data['usuarios_intranet'] = $this->inv_usuario->listar_intranet_disponibles();

        $this->load->view('inventario/header');
        $this->load->view('admin/inventario_usuarios/nuevo', $data);
        $this->load->view('inventario/footer');
    }

    public function grabar()
    {
        $this->_validar_csrf();

        $usuario_id = $this->input->post('usuario_id');
        $privilegio = $this->input->post('privilegio');

        if (( ! $usuario_id) OR ( ! ctype_digit((string) $usuario_id)) OR ( ! in_array($privilegio, array('ADM', 'EDI', 'CON'), TRUE)))
        {
            redirect('inventario/admin/usuarios/nuevo', 'inv_danger_01', TRUE);
        }

        // Nunca se confia en el <select>: se confirma que el usuario de
        // verdad exista y este activo en la tabla real de la intranet.
        if ( ! $this->inv_usuario->existe_en_intranet($usuario_id))
        {
            redirect('inventario/admin/usuarios/nuevo', 'inv_danger_01', TRUE);
        }

        // Evita duplicados: un mismo usuario no puede tener dos filas.
        if ($this->inv_usuario->verificar($usuario_id))
        {
            redirect('inventario/admin/usuarios/nuevo', 'inv_danger_08', TRUE);
        }

        $id = $this->inv_usuario->insertar(array(
            'usuario_id' => $usuario_id,
            'privilegio' => $privilegio,
            'activo'     => 1,
        ));

        if ($id)
        {
            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'inv_usuarios', 'instancia' => $id));
            redirect('inventario/admin/usuarios', 'inv_success_09');
        }
        else
        {
            redirect('inventario/admin/usuarios/nuevo', 'inv_danger_01', TRUE);
        }
    }

    public function editar($id = NULL)
    {
        if (empty($id)) redirect('inventario/admin/usuarios', 'inv_danger_09', TRUE);
        if ( ! $data['usuario'] = $this->inv_usuario->seleccionar($id)) redirect('inventario/admin/usuarios', 'inv_danger_09', TRUE);

        $this->load->view('inventario/header');
        $this->load->view('admin/inventario_usuarios/editar', $data);
        $this->load->view('inventario/footer');
    }

    public function actualizar()
    {
        $this->_validar_csrf();

        $id = $this->input->post('id');
        $privilegio = $this->input->post('privilegio');

        if (( ! $id) OR ( ! in_array($privilegio, array('ADM', 'EDI', 'CON'), TRUE)))
        {
            redirect('inventario/admin/usuarios', 'inv_danger_01', TRUE);
        }

        if ( ! $this->inv_usuario->seleccionar($id)) redirect('inventario/admin/usuarios', 'inv_danger_09', TRUE);

        $ok = $this->inv_usuario->actualizar($id, array('privilegio' => $privilegio));

        if ($ok)
        {
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_usuarios', 'instancia' => $id));
            redirect('inventario/admin/usuarios', 'inv_success_02');
        }
        else
        {
            redirect('inventario/admin/usuarios', 'inv_warning_01', TRUE);
        }
    }

    public function activo()
    {
        $this->_validar_csrf();

        $id = $this->input->post('id');
        if (empty($id)) redirect('inventario/admin/usuarios', 'inv_danger_09', TRUE);

        if ( ! $usuario = $this->inv_usuario->seleccionar($id)) redirect('inventario/admin/usuarios', 'inv_danger_09', TRUE);

        $nuevo = ($usuario['activo'] == 1) ? 0 : 1;

        // Nadie puede desactivarse a si mismo: evita quedar bloqueado del
        // modulo por accidente (no hay forma de reactivarse sin acceso).
        if (($nuevo == 0) AND ((int) $usuario['usuario_id'] === (int) $this->session->userdata('id')))
        {
            redirect('inventario/admin/usuarios', 'inv_danger_10', TRUE);
        }

        $ok = $this->inv_usuario->actualizar($id, array('activo' => $nuevo));

        if ($ok)
        {
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_usuarios', 'instancia' => $id));
        }

        redirect('inventario/admin/usuarios', 'inv_success_02', TRUE);
    }

    private function _validar_csrf()
    {
        if ( ! inv_csrf_validar($this->input->post('inv_csrf_token')))
        {
            redirect('inventario/admin/usuarios', 'inv_danger_05', TRUE);
        }
    }
}

/* End of file inventario_usuarios.php */
