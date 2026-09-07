<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Administracion de los catalogos del modulo Inventario:
| gerencias, areas, ubicaciones, categorias, subcategorias, tipos,
| responsables y estados.
|
| En vez de 8 controladores casi identicos, este controlador es
| generico y recibe el catalogo a administrar como primer segmento de
| URL ($tipo), validado contra la lista blanca $tipos. Solo
| inv_subcategorias tiene manejo especial porque depende de
| inv_categorias (join + combo dependiente).
|
| Protegido igual que el resto del modulo: requiere sesion + acceso
| basico al modulo (clase base Inventario_Controller) y ADEMAS ADM
| DENTRO DEL MODULO (tabla inv_usuarios, no el privilegio general de la
| intranet -- ver application/helpers/inv_permisos_helper.php y
| sql/004_control_acceso_inventario.sql). Ademas valida el token CSRF
| propio del modulo (helper inv_csrf) en todo metodo que modifica
| datos, y activo() ahora es POST-only (revision 2): antes se
| activaba/desactivaba un catalogo con solo visitar una URL por GET.
|
| BASE DE DATOS (revision 4): los catalogos viven en `intranet_inventario`
| (grupo de conexion 'inventario'), no en `intranet`. Este controlador
| no usa transacciones (cada operacion es un unico INSERT/UPDATE, ya
| atomico de por si), pero igual necesita entregarle la conexion
| 'inventario' a los modelos catalogo/subcategoria via set_conexion().
| La auditoria sigue en la conexion normal (intranet), sin cambios. Ver
| docs/BASE_DATOS_INVENTARIO.md.
*/
require_once(APPPATH . 'core/Inventario_Controller.php');

class Inventario_catalogos extends Inventario_Controller {

    private $tipos = array(
        'gerencias'     => array('tabla' => 'inv_gerencias',     'titulo' => 'Gerencias'),
        'areas'         => array('tabla' => 'inv_areas',         'titulo' => 'Areas'),
        'ubicaciones'   => array('tabla' => 'inv_ubicaciones',   'titulo' => 'Ubicaciones'),
        'categorias'    => array('tabla' => 'inv_categorias',    'titulo' => 'Categorias'),
        'subcategorias' => array('tabla' => 'inv_subcategorias', 'titulo' => 'Subcategorias'),
        'tipos'         => array('tabla' => 'inv_tipos',         'titulo' => 'Tipos'),
        'responsables'  => array('tabla' => 'inv_responsables',  'titulo' => 'Responsables'),
        'estados'       => array('tabla' => 'inv_estados',       'titulo' => 'Estados'),
    );

    public function __construct()
    {
        parent::__construct();

        // Sesion y acceso basico al modulo ya quedaron resueltos en el
        // constructor de Inventario_Controller. Aca se agrega el
        // requisito especifico de este controlador: ADM del modulo.
        if ( ! inv_es_admin()) redirect('inventario', 'danger_06', TRUE);

        $this->load->model('inventario_catalogo_model', 'catalogo');
        $this->catalogo->set_conexion($this->db_inv);

        $this->load->model('inventario_subcategoria_model', 'subcategoria');
        $this->subcategoria->set_conexion($this->db_inv);

        // Auditoria general: sigue usando la conexion normal (intranet).
        $this->load->model('auditoria_model', 'auditoria');

        $this->load->helper(array('inventario', 'inv_csrf'));
        $this->lang->load('inventario', 'spanish');
    }

    private function _validar_tipo($tipo)
    {
        if ( ! isset($this->tipos[$tipo])) show_404();

        return $this->tipos[$tipo];
    }

    private function _validar_csrf()
    {
        if ( ! inv_csrf_validar($this->input->post('inv_csrf_token')))
        {
            redirect('admin/inventario_catalogos/index/gerencias', 'inv_danger_05', TRUE);
        }
    }

    public function index($tipo = NULL)
    {
        $meta = $this->_validar_tipo($tipo);

        $data['tipo'] = $tipo;
        $data['titulo'] = $meta['titulo'];
        $data['tipos'] = $this->tipos;

        if ($tipo == 'subcategorias')
        {
            $data['registros'] = $this->subcategoria->listar();
        }
        else
        {
            $this->catalogo->set_tabla($meta['tabla']);
            $data['registros'] = $this->catalogo->listar();
        }

        $this->load->view('inventario/header');
        $this->load->view('admin/inventario_catalogos/index', $data);
        $this->load->view('inventario/footer');
    }

    public function nuevo($tipo = NULL)
    {
        $meta = $this->_validar_tipo($tipo);

        $data['tipo'] = $tipo;
        $data['titulo'] = $meta['titulo'];
        $data['registro'] = NULL;
        $data['categorias'] = array();

        if ($tipo == 'subcategorias')
        {
            $this->catalogo->set_tabla('inv_categorias');
            $data['categorias'] = $this->catalogo->listar(TRUE);
        }

        $this->load->view('inventario/header');
        $this->load->view('admin/inventario_catalogos/nuevo', $data);
        $this->load->view('inventario/footer');
    }

    public function grabar($tipo = NULL)
    {
        $meta = $this->_validar_tipo($tipo);
        $this->_validar_csrf();

        $nombre = trim((string) $this->input->post('nombre'));

        if (($nombre === '') OR (strlen($nombre) > 150))
        {
            redirect('admin/inventario_catalogos/nuevo/' . $tipo, 'inv_danger_01', TRUE);
        }

        if ($tipo == 'subcategorias')
        {
            $categoria_id = $this->input->post('categoria_id');

            if (( ! $categoria_id) OR ( ! ctype_digit((string) $categoria_id)) OR ( ! $this->_categoria_valida($categoria_id)))
            {
                redirect('admin/inventario_catalogos/nuevo/' . $tipo, 'inv_danger_01', TRUE);
            }

            if ($this->subcategoria->verificar_nombre($categoria_id, $nombre))
            {
                redirect('admin/inventario_catalogos/nuevo/' . $tipo, 'inv_danger_02', TRUE);
            }

            $id = $this->subcategoria->insertar(array(
                'categoria_id' => $categoria_id,
                'nombre'       => $nombre,
                'activo'       => 1,
            ));
        }
        else
        {
            $this->catalogo->set_tabla($meta['tabla']);

            if ($this->catalogo->verificar_nombre($nombre))
            {
                redirect('admin/inventario_catalogos/nuevo/' . $tipo, 'inv_danger_02', TRUE);
            }

            $registro = array('nombre' => $nombre, 'activo' => 1);

            if ($tipo == 'responsables')
            {
                $cargo = trim((string) $this->input->post('cargo'));
                if (strlen($cargo) > 150) redirect('admin/inventario_catalogos/nuevo/' . $tipo, 'inv_danger_01', TRUE);
                $registro['cargo'] = $cargo !== '' ? $cargo : NULL;
            }

            $id = $this->catalogo->insertar($registro);
        }

        if ($id)
        {
            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => $meta['tabla'], 'instancia' => $id));
            redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_success_01');
        }
        else
        {
            redirect('admin/inventario_catalogos/nuevo/' . $tipo, 'inv_danger_01', TRUE);
        }
    }

    public function editar($tipo = NULL, $id = NULL)
    {
        $meta = $this->_validar_tipo($tipo);
        if (empty($id)) redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_danger_03', TRUE);

        $data['categorias'] = array();

        if ($tipo == 'subcategorias')
        {
            if ( ! $data['registro'] = $this->subcategoria->seleccionar($id)) redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_danger_03', TRUE);

            $this->catalogo->set_tabla('inv_categorias');
            $data['categorias'] = $this->catalogo->listar(TRUE);
        }
        else
        {
            $this->catalogo->set_tabla($meta['tabla']);
            if ( ! $data['registro'] = $this->catalogo->seleccionar($id)) redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_danger_03', TRUE);
        }

        $data['tipo'] = $tipo;
        $data['titulo'] = $meta['titulo'];

        $this->load->view('inventario/header');
        $this->load->view('admin/inventario_catalogos/editar', $data);
        $this->load->view('inventario/footer');
    }

    public function actualizar($tipo = NULL)
    {
        $meta = $this->_validar_tipo($tipo);
        $this->_validar_csrf();

        $id = $this->input->post('id');
        $nombre = trim((string) $this->input->post('nombre'));

        if (( ! $id) OR ($nombre === '') OR (strlen($nombre) > 150))
        {
            redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_danger_01', TRUE);
        }

        if ($tipo == 'subcategorias')
        {
            $categoria_id = $this->input->post('categoria_id');

            if (( ! $categoria_id) OR ( ! ctype_digit((string) $categoria_id)) OR ( ! $this->_categoria_valida($categoria_id)))
            {
                redirect('admin/inventario_catalogos/editar/' . $tipo . '/' . $id, 'inv_danger_01', TRUE);
            }

            if ($this->subcategoria->verificar_nombre($categoria_id, $nombre, $id))
            {
                redirect('admin/inventario_catalogos/editar/' . $tipo . '/' . $id, 'inv_danger_02', TRUE);
            }

            $ok = $this->subcategoria->actualizar($id, array(
                'categoria_id' => $categoria_id,
                'nombre'       => $nombre,
            ));
        }
        else
        {
            $this->catalogo->set_tabla($meta['tabla']);

            if ($this->catalogo->verificar_nombre($nombre, $id))
            {
                redirect('admin/inventario_catalogos/editar/' . $tipo . '/' . $id, 'inv_danger_02', TRUE);
            }

            $registro = array('nombre' => $nombre);

            if ($tipo == 'responsables')
            {
                $cargo = trim((string) $this->input->post('cargo'));
                if (strlen($cargo) > 150) redirect('admin/inventario_catalogos/editar/' . $tipo . '/' . $id, 'inv_danger_01', TRUE);
                $registro['cargo'] = $cargo !== '' ? $cargo : NULL;
            }

            $ok = $this->catalogo->actualizar($id, $registro);
        }

        if ($ok)
        {
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => $meta['tabla'], 'instancia' => $id));
            redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_success_02');
        }
        else
        {
            redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_warning_01', TRUE);
        }
    }

    public function activo()
    {
        $tipo = $this->input->post('tipo');
        $id = $this->input->post('id');

        $meta = $this->_validar_tipo($tipo);
        $this->_validar_csrf();

        if (empty($id)) redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_danger_03', TRUE);

        if ($tipo == 'subcategorias')
        {
            $this->subcategoria->activo($id);
        }
        else
        {
            $this->catalogo->set_tabla($meta['tabla']);
            $this->catalogo->activo($id);
        }

        $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => $meta['tabla'], 'instancia' => $id));
        redirect('admin/inventario_catalogos/index/' . $tipo, 'inv_success_02', TRUE);
    }

    private function _categoria_valida($categoria_id)
    {
        $this->catalogo->set_tabla('inv_categorias');
        $fila = $this->catalogo->seleccionar($categoria_id);

        return ($fila AND ($fila['activo'] == 1));
    }
}

/* End of file inventario_catalogos.php */
