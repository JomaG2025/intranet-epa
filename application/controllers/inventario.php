<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Controlador principal del modulo Inventario de Activos.
|
| Sigue el mismo patron que application/controllers/admin/usuarios.php
| e informesprensa.php ya existentes en la intranet:
|   - Validacion de sesion identica a los demas controladores.
|   - redirect($uri, 'clave_lang') + flashdata para mensajes.
|   - auditoria->insertar() despues de cada alta/baja/modificacion.
|
| Nombre de archivo en minusculas (inventario.php) para que coincida
| con la URL /inventario en un servidor Linux (sensible a mayusculas),
| igual que login.php, inicio.php, boletines.php, etc.
|
| PERMISOS (revision 6 - control de acceso propio del modulo): cualquier
| usuario con sesion iniciada en la intranet Y con una fila activa en
| inv_usuarios puede consultar (dashboard, listado, detalle, historial,
| ficha, etiqueta). ADM o EDI pueden crear/editar/cambiar ubicacion-
| responsable-estado/administrar fotografias. Solo ADM puede dar de
| baja/reactivar/importar Excel/acciones masivas/ver valores contables.
| El privilegio GENERAL de la intranet ($this->session->userdata('privilegio'))
| YA NO se usa para nada de esto -- ver application/helpers/
| inv_permisos_helper.php y sql/004_control_acceso_inventario.sql. La
| exigencia de sesion + acceso al modulo la hace la clase base
| Inventario_Controller (application/core/Inventario_Controller.php);
| este controlador solo agrega el privilegio MAS ESPECIFICO que le
| corresponda a cada metodo, via _requerir_edicion() (ADM o EDI) y
| _requerir_administrador() (solo ADM).
|
| CSRF (revision 2): la app tiene CSRF global deshabilitado y no se
| toca esa configuracion. Este controlador valida un token propio del
| modulo (helper inv_csrf) en todo metodo que modifica datos.
|
| BASE DE DATOS (revision 4): las tablas inv_* viven en la base
| independiente `intranet_inventario` (grupo de conexion 'inventario',
| ver application/config/database.php), NO en `intranet`. La conexion
| compartida ($this->db_inv) la abre la clase base Inventario_Controller
| y se la entrega a los modelos propios del modulo justo despues de
| cargarlos (set_conexion()), para que TODOS compartan la misma conexion
| fisica: es lo que hace que $this->db_inv->trans_start()/trans_complete()
| mas abajo realmente cubran las consultas que hacen esos modelos, no
| solo las que haria este controlador por su cuenta. El modelo de
| auditoria (`$this->auditoria`) NO se toca: sigue usando $this->db
| normal, sobre `intranet`, como siempre. Detalle completo en
| docs/BASE_DATOS_INVENTARIO.md.
*/
require_once(APPPATH . 'core/Inventario_Controller.php');

class Inventario extends Inventario_Controller {

    public function __construct()
    {
        parent::__construct();

        // Sesion, acceso al modulo y $this->db_inv ya quedaron resueltos
        // en el constructor de Inventario_Controller (ver ese archivo).

        $this->load->model('inventario_model', 'inventario');
        $this->inventario->set_conexion($this->db_inv);

        $this->load->model('inventario_movimiento_model', 'movimiento');
        $this->movimiento->set_conexion($this->db_inv);

        $this->load->model('inventario_foto_model', 'fotografia');
        $this->fotografia->set_conexion($this->db_inv);

        $this->load->model('inventario_catalogo_model', 'catalogo');
        $this->catalogo->set_conexion($this->db_inv);

        $this->load->model('inventario_subcategoria_model', 'subcategoria');
        $this->subcategoria->set_conexion($this->db_inv);

        // Auditoria general: sigue usando la conexion normal (intranet),
        // sin cambios -- ver seccion 7 de docs/BASE_DATOS_INVENTARIO.md.
        $this->load->model('auditoria_model', 'auditoria');

        $this->load->helper(array('inventario', 'qr', 'inv_csrf'));
        $this->lang->load('inventario', 'spanish');
    }

    // ---------------------------------------------------------------
    // Dashboard
    // ---------------------------------------------------------------

    public function index()
    {
        $data['total_operativos'] = $this->inventario->total(0);
        $data['total_baja'] = $this->inventario->total(1);
        $data['por_estado'] = $this->inventario->total_por_estado();
        $data['por_area'] = $this->inventario->total_por_area();
        $data['puede_editar'] = $this->_puede_editar();
        $data['puede_administrar'] = $this->_puede_administrar();

        $this->load->view('inventario/header');
        $this->load->view('inventario/dashboard', $data);
        $this->load->view('inventario/footer');
    }

    // ---------------------------------------------------------------
    // Listado / busqueda / filtros
    // ---------------------------------------------------------------

    public function listado()
    {
        $filtros = array(
            'buscar'          => $this->input->get('buscar'),
            'area_id'         => $this->input->get('area_id'),
            'gerencia_id'     => $this->input->get('gerencia_id'),
            'categoria_id'    => $this->input->get('categoria_id'),
            'subcategoria_id' => $this->input->get('subcategoria_id'),
            'tipo_id'         => $this->input->get('tipo_id'),
            'ubicacion_id'    => $this->input->get('ubicacion_id'),
            'estado_id'       => $this->input->get('estado_id'),
            'dado_baja'       => $this->input->get('dado_baja'),
        );

        $data['filtros'] = $filtros;
        $data['activos'] = $this->inventario->listar($filtros);
        $data['puede_editar'] = $this->_puede_editar();
        // Las acciones masivas (baja/reactivar/cambiar_*_masiva) son solo
        // ADM (ver _requerir_administrador() en esos metodos) -- distinto
        // de $puede_editar (ADM o EDI), asi que necesitan su propia
        // bandera para no mostrar en el listado un menu de acciones que
        // despues el servidor va a rechazar igual.
        $data['puede_administrar'] = $this->_puede_administrar();
        $data = array_merge($data, $this->_datos_catalogos());

        $this->load->view('inventario/header');
        $this->load->view('inventario/listado', $data);
        $this->load->view('inventario/footer');
    }

    // ---------------------------------------------------------------
    // Alta
    // ---------------------------------------------------------------

    public function nuevo()
    {
        $this->_requerir_edicion();

        $data = $this->_datos_catalogos();
        $data['puede_ver_valores'] = $this->_puede_ver_valores();

        $this->load->view('inventario/header');
        $this->load->view('inventario/nuevo', $data);
        $this->load->view('inventario/footer');
    }

    public function grabar()
    {
        $this->_requerir_edicion();
        $this->_validar_csrf();

        $errores = $this->_validar_datos_activo(TRUE);

        if ( ! empty($errores))
        {
            redirect('inventario/nuevo', 'inv_danger_01', TRUE);
        }

        if ($this->inventario->verificar_codigo_unico($this->input->post('codigo_unico')))
        {
            redirect('inventario/nuevo', 'inv_danger_02', TRUE);
        }

        $activo = $this->_datos_post(TRUE);

        $this->db_inv->trans_start();

        $id = $this->inventario->insertar($activo);

        if ($id)
        {
            $this->movimiento->insertar(array(
                'activo_id'       => $id,
                'tipo_movimiento' => 'ALTA',
                'valor_anterior'  => NULL,
                'valor_nuevo'     => $activo['codigo_unico'],
                'motivo'          => 'Alta de activo',
                'observacion'     => NULL,
            ));

            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'inv_activos', 'instancia' => $id));
        }

        $this->db_inv->trans_complete();

        if ($id AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/detalle/' . $id, 'inv_success_01');
        }
        else
        {
            redirect('inventario/nuevo', 'inv_danger_01', TRUE);
        }
    }

    // ---------------------------------------------------------------
    // Edicion general
    // ---------------------------------------------------------------

    public function editar($id = NULL)
    {
        $this->_requerir_edicion();

        if (empty($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $data = array_merge($data, $this->_datos_catalogos());
        $data['puede_ver_valores'] = $this->_puede_ver_valores();

        $this->load->view('inventario/header');
        $this->load->view('inventario/editar', $data);
        $this->load->view('inventario/footer');
    }

    public function actualizar()
    {
        $this->_requerir_edicion();
        $this->_validar_csrf();

        $id = $this->input->post('id');

        if (( ! $id) OR ( ! $anterior = $this->inventario->seleccionar($id))) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $errores = $this->_validar_datos_activo(FALSE);

        if ( ! empty($errores))
        {
            redirect('inventario/editar/' . $id, 'inv_danger_01', TRUE);
        }

        if ($this->inventario->verificar_codigo_unico($this->input->post('codigo_unico'), $id))
        {
            redirect('inventario/editar/' . $id, 'inv_danger_02', TRUE);
        }

        // ubicacion_id / responsable_id / estado_id NUNCA viajan en este
        // array (ver _datos_post()): aunque alguien los mande por POST,
        // el formulario general de edicion no los toca. Se cambian solo
        // desde cambiar_ubicacion()/cambiar_responsable()/cambiar_estado().
        $activo = $this->_datos_post(FALSE);

        $this->db_inv->trans_start();

        $ok = $this->inventario->actualizar($id, $activo);

        if ($ok)
        {
            $resumen = $this->_resumen_cambios_edicion($anterior, $activo);

            if ($resumen)
            {
                $this->movimiento->insertar(array(
                    'activo_id'       => $id,
                    'tipo_movimiento' => 'EDICION',
                    'valor_anterior'  => NULL,
                    'valor_nuevo'     => NULL,
                    'motivo'          => 'Edicion general del activo',
                    'observacion'     => $resumen,
                ));
            }

            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => $id));
        }

        $this->db_inv->trans_complete();

        if ($ok AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/detalle/' . $id, 'inv_success_02');
        }
        else
        {
            redirect('inventario/editar/' . $id, 'inv_warning_01', TRUE);
        }
    }

    // ---------------------------------------------------------------
    // Detalle / historial (consulta, abierto a cualquier usuario logueado)
    // ---------------------------------------------------------------

    public function detalle($id = NULL)
    {
        if (empty($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $data['fotos'] = $this->fotografia->listar($id);
        $data['foto'] = $this->fotografia->principal($id);
        $data['movimientos'] = $this->movimiento->listar($id);
        $data['mostrar_valor'] = $this->_puede_ver_valores();
        $data['puede_editar'] = $this->_puede_editar();
        // Dar de baja / reactivar son solo ADM (ver _requerir_administrador()
        // en esos metodos) -- distinto de $puede_editar (ADM o EDI).
        $data['puede_administrar'] = $this->_puede_administrar();

        $this->load->view('inventario/header');
        $this->load->view('inventario/detalle', $data);
        $this->load->view('inventario/footer');
    }

    public function historial($id = NULL)
    {
        if (empty($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $data['movimientos'] = $this->movimiento->listar($id);

        $this->load->view('inventario/header');
        $this->load->view('inventario/historial', $data);
        $this->load->view('inventario/footer');
    }

    // ---------------------------------------------------------------
    // Ficha / etiqueta / QR (paginas imprimibles, requieren sesion,
    // abiertas a cualquier usuario logueado: "Imprimir ficha basica" y
    // "Imprimir etiqueta" estan permitidos para todos)
    // ---------------------------------------------------------------

    public function ficha($id = NULL)
    {
        if (empty($id)) show_404();
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) show_404();

        $data['foto'] = $this->fotografia->principal($id);
        $data['mostrar_valor'] = $this->_puede_ver_valores();
        $data['qr_disponible'] = inv_qr_lib_disponible();

        $this->load->view('inventario/ficha', $data);
    }

    public function etiqueta($id = NULL)
    {
        if (empty($id)) show_404();
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) show_404();

        $data['qr_disponible'] = inv_qr_lib_disponible();

        $this->load->view('inventario/etiqueta', $data);
    }

    public function qr($id = NULL)
    {
        if (empty($id)) show_404();
        if ( ! $this->inventario->seleccionar($id)) show_404();

        $carpeta = $this->config->item('upload_path') . 'inventario/qr/';
        $ruta = $carpeta . $id . '.png';

        if ( ! is_file($ruta))
        {
            inv_qr_generar(site_url('inventario/detalle/' . $id), $ruta);
        }

        header('Content-Type: image/png');
        header('Cache-Control: no-cache, must-revalidate');

        if (is_file($ruta))
        {
            readfile($ruta);
        }
        else
        {
            // Libreria phpqrcode aun no instalada: se sirve un PNG
            // transparente de 1x1 en vez de romper el layout de la pagina.
            echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        }

        exit;
    }

    // ---------------------------------------------------------------
    // Baja / reactivacion
    // ---------------------------------------------------------------

    public function baja($id = NULL)
    {
        $this->_requerir_administrador();

        if (empty($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ($data['activo']['dado_baja'] == 1) redirect('inventario/detalle/' . $id, 'inv_warning_02', TRUE);

        $this->load->view('inventario/header');
        $this->load->view('inventario/baja', $data);
        $this->load->view('inventario/footer');
    }

    public function procesar_baja()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $id = $this->input->post('id');
        $motivo = $this->input->post('motivo');

        if (( ! $id) OR ( ! $this->inventario->seleccionar($id))) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $motivo) redirect('inventario/baja/' . $id, 'inv_danger_01', TRUE);

        $this->db_inv->trans_start();

        $ok = $this->inventario->dar_baja($id, $motivo);

        if ($ok)
        {
            $this->movimiento->insertar(array(
                'activo_id'       => $id,
                'tipo_movimiento' => 'BAJA',
                'valor_anterior'  => 'Activo',
                'valor_nuevo'     => 'Dado de baja',
                'motivo'          => $motivo,
                'observacion'     => $this->input->post('observacion'),
            ));

            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => $id));
        }

        $this->db_inv->trans_complete();

        if ($ok AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/detalle/' . $id, 'inv_success_03');
        }
        else
        {
            redirect('inventario/baja/' . $id, 'inv_danger_01', TRUE);
        }
    }

    public function reactivar()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $id = $this->input->post('id');

        if (( ! $id) OR ( ! $this->inventario->seleccionar($id))) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->db_inv->trans_start();

        $ok = $this->inventario->reactivar($id);

        if ($ok)
        {
            $this->movimiento->insertar(array(
                'activo_id'       => $id,
                'tipo_movimiento' => 'REACTIVACION',
                'valor_anterior'  => 'Dado de baja',
                'valor_nuevo'     => 'Activo',
                'motivo'          => 'Reactivacion de activo',
                'observacion'     => NULL,
            ));

            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => $id));
        }

        $this->db_inv->trans_complete();

        if ($ok AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/detalle/' . $id, 'inv_success_04');
        }
        else
        {
            redirect('inventario/detalle/' . $id, 'inv_danger_01', TRUE);
        }
    }

    // ---------------------------------------------------------------
    // Cambios rapidos: ubicacion / responsable / estado
    // Unica via para modificar estos 3 campos (ver seccion 6 de
    // docs/CORRECCIONES_REVISION_2.md) - el formulario general de
    // edicion los muestra de solo lectura.
    // ---------------------------------------------------------------

    public function cambiar_ubicacion($id = NULL)
    {
        $this->_requerir_edicion();

        if (empty($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->catalogo->set_tabla('inv_ubicaciones');
        $data['ubicaciones'] = $this->catalogo->listar(TRUE);

        $this->load->view('inventario/header');
        $this->load->view('inventario/cambiar_ubicacion', $data);
        $this->load->view('inventario/footer');
    }

    public function actualizar_ubicacion()
    {
        $this->_requerir_edicion();
        $this->_validar_csrf();

        $id = $this->input->post('id');
        $nueva_id = $this->input->post('ubicacion_id');

        if (( ! $id) OR ( ! $activo = $this->inventario->seleccionar($id))) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $error_catalogo = $this->_validar_catalogo('ubicacion_id', 'inv_ubicaciones', TRUE);
        if ( ! empty($error_catalogo)) redirect('inventario/cambiar_ubicacion/' . $id, 'inv_danger_01', TRUE);

        $this->catalogo->set_tabla('inv_ubicaciones');
        $nueva = $this->catalogo->seleccionar($nueva_id);

        $this->db_inv->trans_start();

        $ok = $this->inventario->actualizar($id, array('ubicacion_id' => $nueva_id));

        if ($ok)
        {
            $this->movimiento->insertar(array(
                'activo_id'       => $id,
                'tipo_movimiento' => 'UBICACION',
                'valor_anterior'  => $activo['ubicacion_nombre'],
                'valor_nuevo'     => $nueva ? $nueva['nombre'] : NULL,
                'motivo'          => $this->input->post('motivo'),
                'observacion'     => $this->input->post('observacion'),
            ));

            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => $id));
        }

        $this->db_inv->trans_complete();

        if ($ok AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/detalle/' . $id, 'inv_success_05');
        }
        else
        {
            redirect('inventario/cambiar_ubicacion/' . $id, 'inv_warning_01', TRUE);
        }
    }

    public function cambiar_responsable($id = NULL)
    {
        $this->_requerir_edicion();

        if (empty($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->catalogo->set_tabla('inv_responsables');
        $data['responsables'] = $this->catalogo->listar(TRUE);

        $this->load->view('inventario/header');
        $this->load->view('inventario/cambiar_responsable', $data);
        $this->load->view('inventario/footer');
    }

    public function actualizar_responsable()
    {
        $this->_requerir_edicion();
        $this->_validar_csrf();

        $id = $this->input->post('id');
        $nuevo_id = $this->input->post('responsable_id');

        if (( ! $id) OR ( ! $activo = $this->inventario->seleccionar($id))) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $error_catalogo = $this->_validar_catalogo('responsable_id', 'inv_responsables', TRUE);
        if ( ! empty($error_catalogo)) redirect('inventario/cambiar_responsable/' . $id, 'inv_danger_01', TRUE);

        $this->catalogo->set_tabla('inv_responsables');
        $nuevo = $this->catalogo->seleccionar($nuevo_id);

        $this->db_inv->trans_start();

        $ok = $this->inventario->actualizar($id, array('responsable_id' => $nuevo_id));

        if ($ok)
        {
            $this->movimiento->insertar(array(
                'activo_id'       => $id,
                'tipo_movimiento' => 'RESPONSABLE',
                'valor_anterior'  => $activo['responsable_nombre'],
                'valor_nuevo'     => $nuevo ? $nuevo['nombre'] : NULL,
                'motivo'          => $this->input->post('motivo'),
                'observacion'     => $this->input->post('observacion'),
            ));

            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => $id));
        }

        $this->db_inv->trans_complete();

        if ($ok AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/detalle/' . $id, 'inv_success_05');
        }
        else
        {
            redirect('inventario/cambiar_responsable/' . $id, 'inv_warning_01', TRUE);
        }
    }

    public function cambiar_estado($id = NULL)
    {
        $this->_requerir_edicion();

        if (empty($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->catalogo->set_tabla('inv_estados');
        $data['estados'] = $this->catalogo->listar(TRUE);

        $this->load->view('inventario/header');
        $this->load->view('inventario/cambiar_estado', $data);
        $this->load->view('inventario/footer');
    }

    public function actualizar_estado()
    {
        $this->_requerir_edicion();
        $this->_validar_csrf();

        $id = $this->input->post('id');
        $nuevo_id = $this->input->post('estado_id');

        if (( ! $id) OR ( ! $activo = $this->inventario->seleccionar($id))) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $error_catalogo = $this->_validar_catalogo('estado_id', 'inv_estados', TRUE);
        if ( ! empty($error_catalogo)) redirect('inventario/cambiar_estado/' . $id, 'inv_danger_01', TRUE);

        $this->catalogo->set_tabla('inv_estados');
        $nuevo = $this->catalogo->seleccionar($nuevo_id);

        $this->db_inv->trans_start();

        $ok = $this->inventario->actualizar($id, array('estado_id' => $nuevo_id));

        if ($ok)
        {
            $this->movimiento->insertar(array(
                'activo_id'       => $id,
                'tipo_movimiento' => 'ESTADO',
                'valor_anterior'  => $activo['estado_nombre'],
                'valor_nuevo'     => $nuevo ? $nuevo['nombre'] : NULL,
                'motivo'          => $this->input->post('motivo'),
                'observacion'     => $this->input->post('observacion'),
            ));

            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => $id));
        }

        $this->db_inv->trans_complete();

        if ($ok AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/detalle/' . $id, 'inv_success_05');
        }
        else
        {
            redirect('inventario/cambiar_estado/' . $id, 'inv_warning_01', TRUE);
        }
    }

    // ---------------------------------------------------------------
    // Fotografia
    // ---------------------------------------------------------------

    public function foto($id = NULL)
    {
        $this->_requerir_edicion();

        if (empty($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $data['activo'] = $this->inventario->seleccionar($id)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $data['fotos'] = $this->fotografia->listar($id);

        $this->load->view('inventario/header');
        $this->load->view('inventario/foto', $data);
        $this->load->view('inventario/footer');
    }

    public function subir_foto()
    {
        $this->_requerir_edicion();
        $this->_validar_csrf();

        $id = (int) $this->input->post('id');

        if (( ! $id) OR ( ! $this->inventario->seleccionar($id))) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $carpeta = $this->config->item('upload_path') . 'inventario/fotos/';
        if ( ! is_dir($carpeta)) @mkdir($carpeta, DIR_WRITE_MODE, TRUE);

        // El nombre del archivo se arma solo con datos que ya validamos
        // (el id numerico del activo, casteado a int) + tiempo/numero
        // aleatorio del servidor. Nunca se usa el nombre original que
        // manda el navegador ni ningun otro dato escrito por el usuario.
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 4096;
        $config['max_width'] = 6000;
        $config['max_height'] = 6000;
        $config['file_name'] = 'activo-' . $id . '-' . time() . '-' . mt_rand(1000, 9999);
        $config['encrypt_name'] = FALSE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('imagen'))
        {
            redirect('inventario/foto/' . $id, 'inv_danger_04', TRUE);
        }

        $subida = $this->upload->data();
        $ruta_completa = $subida['full_path'];

        if ( ! $this->_es_imagen_valida($ruta_completa, $subida['file_ext']))
        {
            @unlink($ruta_completa);
            redirect('inventario/foto/' . $id, 'inv_danger_04', TRUE);
        }

        $this->db_inv->trans_start();

        $foto_id = $this->fotografia->insertar(array(
            'activo_id'  => $id,
            'archivo'    => $subida['file_name'],
            'usuario_id' => $this->session->userdata('id'),
        ));

        if ($foto_id)
        {
            if ( ! $this->fotografia->principal($id))
            {
                $this->fotografia->marcar_principal($id, $foto_id);
            }

            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_fotografias', 'instancia' => $foto_id));
        }

        $this->db_inv->trans_complete();

        if ($foto_id AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/foto/' . $id, 'inv_success_06');
        }
        else
        {
            // No quedo guardado el registro: no dejamos el archivo huerfano.
            @unlink($ruta_completa);
            redirect('inventario/foto/' . $id, 'inv_danger_04', TRUE);
        }
    }

    public function foto_principal()
    {
        $this->_requerir_edicion();
        $this->_validar_csrf();

        $foto_id = $this->input->post('foto_id');

        if (empty($foto_id) OR ( ! $foto = $this->fotografia->seleccionar($foto_id))) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->db_inv->trans_start();

        // marcar_principal() valida internamente que la foto exista, no
        // este eliminada y pertenezca a este activo (desmarca la anterior
        // + marca la nueva) y devuelve TRUE/FALSE.
        $ok = $this->fotografia->marcar_principal($foto['activo_id'], $foto_id);

        if ($ok)
        {
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_fotografias', 'instancia' => $foto_id));
        }

        $this->db_inv->trans_complete();

        if ($ok AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/foto/' . $foto['activo_id'], 'inv_success_02');
        }
        else
        {
            redirect('inventario/foto/' . $foto['activo_id'], 'inv_danger_01', TRUE);
        }
    }

    public function eliminar_foto()
    {
        $this->_requerir_edicion();
        $this->_validar_csrf();

        $foto_id = $this->input->post('foto_id');

        if (empty($foto_id) OR ( ! $foto = $this->fotografia->seleccionar($foto_id))) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->db_inv->trans_start();

        // Baja logica: el archivo fisico se conserva como evidencia.
        $eliminado_ok = $this->fotografia->eliminar($foto_id, $this->session->userdata('id'));

        if ($eliminado_ok)
        {
            if ($foto['principal'] == 1)
            {
                // Busca otra foto vigente del mismo activo para asignarla
                // como principal. Si no queda ninguna, el activo se queda
                // sin foto principal (comportamiento esperado, no es un
                // error).
                $restantes = $this->fotografia->listar($foto['activo_id']);

                if ( ! empty($restantes))
                {
                    $this->fotografia->marcar_principal($foto['activo_id'], $restantes[0]['id']);
                }
            }

            $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'inv_fotografias', 'instancia' => $foto_id));
        }
        // Si $eliminado_ok es FALSE, no se reasigna principal ni se
        // registra auditoria: la consulta de baja logica en si misma no
        // se completo, asi que trans_complete() hara rollback de
        // cualquier cosa que se hubiera alcanzado a hacer en esta misma
        // transaccion.

        $this->db_inv->trans_complete();

        if ($eliminado_ok AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/foto/' . $foto['activo_id'], 'inv_success_02');
        }
        else
        {
            redirect('inventario/foto/' . $foto['activo_id'], 'inv_danger_01', TRUE);
        }
    }

    // ---------------------------------------------------------------
    // Acciones masivas (listado > seleccion multiple)
    //
    // Mismo patron GET-confirmar / POST-aplicar que baja()/procesar_baja(),
    // cambiar_ubicacion()/actualizar_ubicacion(), etc., pero:
    //   - El primer paso llega por POST (no GET) porque puede traer
    //     cientos de ids seleccionados, y una URL GET tiene limite de
    //     largo.
    //   - Los ids se revalidan SIEMPRE contra la base de datos
    //     (_ids_validos_post()) y se guardan en sesion; el paso de
    //     "aplicar" nunca vuelve a confiar en ids que reenvie el
    //     navegador, solo en lo que quedo guardado en sesion en el paso
    //     anterior.
    //   - Nunca se hace un UPDATE masivo de una sola pasada: cada activo
    //     se actualiza y registra en inv_movimientos de forma individual,
    //     para que el historial de cada uno quede completo.
    //   - "Eliminar seleccionados" NO existe como tal: la unica baja
    //     masiva posible es la logica (dado_baja=1), igual que en el
    //     resto del modulo. Nunca se hace DELETE FROM inv_activos.
    // ---------------------------------------------------------------

    public function baja_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->_ids_validos_post();
        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->session->set_userdata('inv_masivo_ids', $ids);
        $data['activos'] = $this->inventario->seleccionar_multiples($ids);

        $this->load->view('inventario/header');
        $this->load->view('inventario/baja_masiva', $data);
        $this->load->view('inventario/footer');
    }

    public function procesar_baja_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->session->userdata('inv_masivo_ids');
        $motivo = $this->input->post('motivo');

        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $motivo) redirect('inventario/listado', 'inv_danger_01', TRUE);

        // Se vuelve a leer de la base (no solo de la sesion): un activo
        // pudo cambiar de estado entre el paso de confirmacion y este envio.
        $activos = $this->inventario->seleccionar_multiples($ids);

        $this->db_inv->trans_start();

        $aplicados = 0;

        foreach ($activos as $activo)
        {
            if ($activo['dado_baja'] == 1) continue;

            if ($this->inventario->dar_baja($activo['id'], $motivo))
            {
                $this->movimiento->insertar(array(
                    'activo_id'       => $activo['id'],
                    'tipo_movimiento' => 'BAJA',
                    'valor_anterior'  => 'Activo',
                    'valor_nuevo'     => 'Dado de baja',
                    'motivo'          => $motivo,
                    'observacion'     => 'Baja masiva (' . count($activos) . ' activos seleccionados).',
                ));

                $aplicados++;
            }
        }

        if ($aplicados > 0) $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => 0));

        $this->db_inv->trans_complete();
        $this->session->unset_userdata('inv_masivo_ids');

        if ($aplicados AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/listado', 'inv_success_08');
        }
        else
        {
            redirect('inventario/listado', 'inv_danger_01', TRUE);
        }
    }

    public function reactivar_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->_ids_validos_post();
        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->session->set_userdata('inv_masivo_ids', $ids);
        $data['activos'] = $this->inventario->seleccionar_multiples($ids);

        $this->catalogo->set_tabla('inv_estados');
        $data['estados'] = $this->catalogo->listar(TRUE);

        $this->load->view('inventario/header');
        $this->load->view('inventario/reactivar_masiva', $data);
        $this->load->view('inventario/footer');
    }

    public function procesar_reactivar_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->session->userdata('inv_masivo_ids');
        $motivo = $this->input->post('motivo');
        $estado_id = $this->input->post('estado_id');

        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $motivo) redirect('inventario/listado', 'inv_danger_01', TRUE);

        $error_catalogo = $this->_validar_catalogo('estado_id', 'inv_estados', TRUE);
        if ( ! empty($error_catalogo)) redirect('inventario/listado', 'inv_danger_01', TRUE);

        $this->catalogo->set_tabla('inv_estados');
        $nuevo_estado = $this->catalogo->seleccionar($estado_id);

        $activos = $this->inventario->seleccionar_multiples($ids);

        $this->db_inv->trans_start();

        $aplicados = 0;

        foreach ($activos as $activo)
        {
            if ($activo['dado_baja'] == 0) continue;

            $ok = $this->inventario->reactivar($activo['id']);
            if ($ok) $ok = $this->inventario->actualizar($activo['id'], array('estado_id' => $estado_id));

            if ($ok)
            {
                $this->movimiento->insertar(array(
                    'activo_id'       => $activo['id'],
                    'tipo_movimiento' => 'REACTIVACION',
                    'valor_anterior'  => 'Dado de baja',
                    'valor_nuevo'     => $nuevo_estado ? $nuevo_estado['nombre'] : NULL,
                    'motivo'          => $motivo,
                    'observacion'     => 'Reactivacion masiva (' . count($activos) . ' activos seleccionados).',
                ));

                $aplicados++;
            }
        }

        if ($aplicados > 0) $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => 0));

        $this->db_inv->trans_complete();
        $this->session->unset_userdata('inv_masivo_ids');

        if ($aplicados AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/listado', 'inv_success_08');
        }
        else
        {
            redirect('inventario/listado', 'inv_danger_01', TRUE);
        }
    }

    public function cambiar_ubicacion_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->_ids_validos_post();
        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->session->set_userdata('inv_masivo_ids', $ids);
        $data['activos'] = $this->inventario->seleccionar_multiples($ids);

        $this->catalogo->set_tabla('inv_ubicaciones');
        $data['ubicaciones'] = $this->catalogo->listar(TRUE);

        $this->load->view('inventario/header');
        $this->load->view('inventario/cambiar_ubicacion_masiva', $data);
        $this->load->view('inventario/footer');
    }

    public function procesar_cambiar_ubicacion_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->session->userdata('inv_masivo_ids');
        $motivo = $this->input->post('motivo');
        $nueva_id = $this->input->post('ubicacion_id');

        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $motivo) redirect('inventario/listado', 'inv_danger_01', TRUE);

        $error_catalogo = $this->_validar_catalogo('ubicacion_id', 'inv_ubicaciones', TRUE);
        if ( ! empty($error_catalogo)) redirect('inventario/listado', 'inv_danger_01', TRUE);

        $this->catalogo->set_tabla('inv_ubicaciones');
        $nueva = $this->catalogo->seleccionar($nueva_id);

        $activos = $this->inventario->seleccionar_multiples($ids);

        $this->db_inv->trans_start();

        $aplicados = 0;

        foreach ($activos as $activo)
        {
            $ok = $this->inventario->actualizar($activo['id'], array('ubicacion_id' => $nueva_id));

            if ($ok)
            {
                $this->movimiento->insertar(array(
                    'activo_id'       => $activo['id'],
                    'tipo_movimiento' => 'UBICACION',
                    'valor_anterior'  => $activo['ubicacion_nombre'],
                    'valor_nuevo'     => $nueva ? $nueva['nombre'] : NULL,
                    'motivo'          => $motivo,
                    'observacion'     => 'Cambio masivo de ubicacion (' . count($activos) . ' activos seleccionados).',
                ));

                $aplicados++;
            }
        }

        if ($aplicados > 0) $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => 0));

        $this->db_inv->trans_complete();
        $this->session->unset_userdata('inv_masivo_ids');

        if ($aplicados AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/listado', 'inv_success_08');
        }
        else
        {
            redirect('inventario/listado', 'inv_danger_01', TRUE);
        }
    }

    public function cambiar_responsable_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->_ids_validos_post();
        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->session->set_userdata('inv_masivo_ids', $ids);
        $data['activos'] = $this->inventario->seleccionar_multiples($ids);

        $this->catalogo->set_tabla('inv_responsables');
        $data['responsables'] = $this->catalogo->listar(TRUE);

        $this->load->view('inventario/header');
        $this->load->view('inventario/cambiar_responsable_masiva', $data);
        $this->load->view('inventario/footer');
    }

    public function procesar_cambiar_responsable_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->session->userdata('inv_masivo_ids');
        $motivo = $this->input->post('motivo');
        $nuevo_id = $this->input->post('responsable_id');

        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $motivo) redirect('inventario/listado', 'inv_danger_01', TRUE);

        $error_catalogo = $this->_validar_catalogo('responsable_id', 'inv_responsables', TRUE);
        if ( ! empty($error_catalogo)) redirect('inventario/listado', 'inv_danger_01', TRUE);

        $this->catalogo->set_tabla('inv_responsables');
        $nuevo = $this->catalogo->seleccionar($nuevo_id);

        $activos = $this->inventario->seleccionar_multiples($ids);

        $this->db_inv->trans_start();

        $aplicados = 0;

        foreach ($activos as $activo)
        {
            $ok = $this->inventario->actualizar($activo['id'], array('responsable_id' => $nuevo_id));

            if ($ok)
            {
                $this->movimiento->insertar(array(
                    'activo_id'       => $activo['id'],
                    'tipo_movimiento' => 'RESPONSABLE',
                    'valor_anterior'  => $activo['responsable_nombre'],
                    'valor_nuevo'     => $nuevo ? $nuevo['nombre'] : NULL,
                    'motivo'          => $motivo,
                    'observacion'     => 'Cambio masivo de responsable (' . count($activos) . ' activos seleccionados).',
                ));

                $aplicados++;
            }
        }

        if ($aplicados > 0) $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => 0));

        $this->db_inv->trans_complete();
        $this->session->unset_userdata('inv_masivo_ids');

        if ($aplicados AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/listado', 'inv_success_08');
        }
        else
        {
            redirect('inventario/listado', 'inv_danger_01', TRUE);
        }
    }

    public function cambiar_estado_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->_ids_validos_post();
        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);

        $this->session->set_userdata('inv_masivo_ids', $ids);
        $data['activos'] = $this->inventario->seleccionar_multiples($ids);

        $this->catalogo->set_tabla('inv_estados');
        $data['estados'] = $this->catalogo->listar(TRUE);

        $this->load->view('inventario/header');
        $this->load->view('inventario/cambiar_estado_masiva', $data);
        $this->load->view('inventario/footer');
    }

    public function procesar_cambiar_estado_masiva()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ids = $this->session->userdata('inv_masivo_ids');
        $motivo = $this->input->post('motivo');
        $nuevo_id = $this->input->post('estado_id');

        if (empty($ids)) redirect('inventario/listado', 'inv_danger_03', TRUE);
        if ( ! $motivo) redirect('inventario/listado', 'inv_danger_01', TRUE);

        $error_catalogo = $this->_validar_catalogo('estado_id', 'inv_estados', TRUE);
        if ( ! empty($error_catalogo)) redirect('inventario/listado', 'inv_danger_01', TRUE);

        $this->catalogo->set_tabla('inv_estados');
        $nuevo = $this->catalogo->seleccionar($nuevo_id);

        $activos = $this->inventario->seleccionar_multiples($ids);

        $this->db_inv->trans_start();

        $aplicados = 0;

        foreach ($activos as $activo)
        {
            $ok = $this->inventario->actualizar($activo['id'], array('estado_id' => $nuevo_id));

            if ($ok)
            {
                $this->movimiento->insertar(array(
                    'activo_id'       => $activo['id'],
                    'tipo_movimiento' => 'ESTADO',
                    'valor_anterior'  => $activo['estado_nombre'],
                    'valor_nuevo'     => $nuevo ? $nuevo['nombre'] : NULL,
                    'motivo'          => $motivo,
                    'observacion'     => 'Cambio masivo de estado (' . count($activos) . ' activos seleccionados).',
                ));

                $aplicados++;
            }
        }

        if ($aplicados > 0) $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'inv_activos', 'instancia' => 0));

        $this->db_inv->trans_complete();
        $this->session->unset_userdata('inv_masivo_ids');

        if ($aplicados AND ($this->db_inv->trans_status() !== FALSE))
        {
            redirect('inventario/listado', 'inv_success_08');
        }
        else
        {
            redirect('inventario/listado', 'inv_danger_01', TRUE);
        }
    }

    public function cancelar_masivo()
    {
        $this->_requerir_administrador();

        $this->session->unset_userdata('inv_masivo_ids');
        redirect('inventario/listado');
    }

    /*
    | Lee 'ids' (array) desde POST y devuelve solo los que de verdad
    | existen y no estan eliminados (nunca se confia en lo que el
    | navegador marco como seleccionado).
    */
    private function _ids_validos_post()
    {
        $ids_post = $this->input->post('ids');
        if (empty($ids_post) OR ( ! is_array($ids_post))) return array();

        $ids = array();
        foreach ($ids_post as $id)
        {
            if (ctype_digit((string) $id)) $ids[] = (int) $id;
        }

        if (empty($ids)) return array();

        $activos = $this->inventario->seleccionar_multiples($ids);

        $validos = array();
        foreach ($activos as $activo) $validos[] = (int) $activo['id'];

        return $validos;
    }

    // ---------------------------------------------------------------
    // Permisos
    // ---------------------------------------------------------------

    // Los 4 helpers inv_* (application/helpers/inv_permisos_helper.php)
    // son ahora la UNICA fuente de verdad sobre permisos del modulo -- ya
    // no se lee $this->session->userdata('privilegio') (privilegio
    // GENERAL de la intranet) en ningun punto de este controlador. Estos
    // 4 metodos quedan solo como fachada corta, para no repetir
    // "inv_puede_editar()" etc. por todo el archivo y para que las vistas
    // sigan pudiendo llamar $this->_puede_editar() como antes.

    private function _puede_consultar()
    {
        return inv_puede_consultar();
    }

    private function _puede_editar()
    {
        return inv_puede_editar();
    }

    private function _puede_administrar()
    {
        return inv_es_admin();
    }

    private function _puede_ver_valores()
    {
        // Igual que antes de este cambio: ver/editar valores contables
        // sigue siendo exclusivo de ADM (el nuevo privilegio EDI no lo
        // incluye) -- no se pidio ampliar esto, asi que se mantiene tal
        // cual estaba.
        return inv_es_admin();
    }

    private function _requerir_edicion()
    {
        // ADM o EDI: crear/editar activos, cambiar ubicacion/responsable/
        // estado, fotografias.
        if ( ! $this->_puede_editar())
        {
            redirect('inventario', 'danger_06', TRUE);
        }
    }

    private function _requerir_administrador()
    {
        // Solo ADM: dar de baja/reactivar (individual), y todas las
        // acciones masivas (baja_masiva, reactivar_masiva, cambiar_*_masiva).
        if ( ! $this->_puede_administrar())
        {
            redirect('inventario', 'danger_06', TRUE);
        }
    }

    private function _validar_csrf()
    {
        if ( ! inv_csrf_validar($this->input->post('inv_csrf_token')))
        {
            redirect('inventario/listado', 'inv_danger_05', TRUE);
        }
    }

    // ---------------------------------------------------------------
    // Datos / validacion
    // ---------------------------------------------------------------

    private function _datos_catalogos()
    {
        $data = array();

        $this->catalogo->set_tabla('inv_gerencias');
        $data['gerencias'] = $this->catalogo->listar(TRUE);

        $this->catalogo->set_tabla('inv_areas');
        $data['areas'] = $this->catalogo->listar(TRUE);

        $this->catalogo->set_tabla('inv_categorias');
        $data['categorias'] = $this->catalogo->listar(TRUE);

        $data['subcategorias'] = $this->subcategoria->listar(TRUE);

        $this->catalogo->set_tabla('inv_tipos');
        $data['tipos'] = $this->catalogo->listar(TRUE);

        $this->catalogo->set_tabla('inv_ubicaciones');
        $data['ubicaciones'] = $this->catalogo->listar(TRUE);

        $this->catalogo->set_tabla('inv_responsables');
        $data['responsables'] = $this->catalogo->listar(TRUE);

        $this->catalogo->set_tabla('inv_estados');
        $data['estados'] = $this->catalogo->listar(TRUE);

        return $data;
    }

    /*
    | $incluir_asignacion_inicial = TRUE solo cuando se esta CREANDO un
    | activo nuevo (grabar()): ahi si se permite fijar ubicacion_id,
    | responsable_id y estado_id iniciales, porque el activo recien nace
    | y ese es su primer valor (queda registrado igual en el movimiento
    | ALTA). En actualizar() SIEMPRE se llama con FALSE, por lo que esas
    | 3 llaves nunca existen en el array devuelto y actualizar() no las
    | puede tocar aunque alguien las mande por POST.
    */
    private function _datos_post($incluir_asignacion_inicial = FALSE)
    {
        $activo = array(
            'codigo_unico'      => $this->input->post('codigo_unico'),
            'codigo'            => $this->input->post('codigo'),
            'gerencia_id'       => $this->input->post('gerencia_id') ? $this->input->post('gerencia_id') : NULL,
            'area_id'           => $this->input->post('area_id') ? $this->input->post('area_id') : NULL,
            'grupo'             => $this->input->post('grupo'),
            'categoria_id'      => $this->input->post('categoria_id'),
            'subcategoria_id'   => $this->input->post('subcategoria_id') ? $this->input->post('subcategoria_id') : NULL,
            'tipo_id'           => $this->input->post('tipo_id') ? $this->input->post('tipo_id') : NULL,
            'unidad'            => $this->input->post('unidad') ? $this->input->post('unidad') : 1,
            'descripcion'       => $this->input->post('descripcion'),
            'marca'             => $this->input->post('marca'),
            'color'             => $this->input->post('color'),
            'modelo'            => $this->input->post('modelo'),
            'numero_serie'      => $this->input->post('numero_serie'),
            'cargo'             => $this->input->post('cargo'),
            'fecha_adquisicion' => $this->input->post('fecha_adquisicion') ? $this->input->post('fecha_adquisicion') : NULL,
            'observaciones'     => $this->input->post('observaciones'),
        );

        if ($incluir_asignacion_inicial)
        {
            $activo['ubicacion_id']   = $this->input->post('ubicacion_id') ? $this->input->post('ubicacion_id') : NULL;
            $activo['responsable_id'] = $this->input->post('responsable_id') ? $this->input->post('responsable_id') : NULL;
            $activo['estado_id']      = $this->input->post('estado_id') ? $this->input->post('estado_id') : NULL;
        }

        // Los campos contables solo se leen del POST (y por lo tanto solo
        // se pueden crear/modificar) si el usuario tiene permiso para
        // verlos. Si no se incluyen en el array, el UPDATE/INSERT
        // simplemente no toca esas columnas.
        if ($this->_puede_ver_valores())
        {
            $activo['valor_libro'] = $this->input->post('valor_libro') ? $this->input->post('valor_libro') : NULL;
            $activo['depreciacion_acumulada'] = $this->input->post('depreciacion_acumulada') ? $this->input->post('depreciacion_acumulada') : NULL;
        }

        return $activo;
    }

    private function _validar_datos_activo($es_creacion)
    {
        $errores = array();

        $codigo_unico = trim((string) $this->input->post('codigo_unico'));
        if (($codigo_unico === '') OR (strlen($codigo_unico) > 30)) $errores[] = 'codigo_unico';

        $descripcion = trim((string) $this->input->post('descripcion'));
        if ($descripcion === '') $errores[] = 'descripcion';

        // Longitudes maximas segun el esquema real de inv_activos.
        $errores = array_merge($errores, $this->_validar_longitud('codigo', 20));
        $errores = array_merge($errores, $this->_validar_longitud('grupo', 30));
        $errores = array_merge($errores, $this->_validar_longitud('marca', 80));
        $errores = array_merge($errores, $this->_validar_longitud('modelo', 80));
        $errores = array_merge($errores, $this->_validar_longitud('color', 40));
        $errores = array_merge($errores, $this->_validar_longitud('numero_serie', 80));
        $errores = array_merge($errores, $this->_validar_longitud('cargo', 150));

        $errores = array_merge($errores, $this->_validar_catalogo('gerencia_id', 'inv_gerencias', FALSE));
        $errores = array_merge($errores, $this->_validar_catalogo('area_id', 'inv_areas', FALSE));
        $errores = array_merge($errores, $this->_validar_catalogo('categoria_id', 'inv_categorias', TRUE));
        $errores = array_merge($errores, $this->_validar_catalogo('subcategoria_id', 'inv_subcategorias', FALSE));
        $errores = array_merge($errores, $this->_validar_catalogo('tipo_id', 'inv_tipos', FALSE));

        if ($es_creacion)
        {
            $errores = array_merge($errores, $this->_validar_catalogo('ubicacion_id', 'inv_ubicaciones', FALSE));
            $errores = array_merge($errores, $this->_validar_catalogo('responsable_id', 'inv_responsables', FALSE));
            $errores = array_merge($errores, $this->_validar_catalogo('estado_id', 'inv_estados', FALSE));
        }

        // La subcategoria debe pertenecer a la categoria seleccionada.
        $subcategoria_id = $this->input->post('subcategoria_id');
        $categoria_id = $this->input->post('categoria_id');

        if ($subcategoria_id AND empty($errores))
        {
            $subcat = $this->subcategoria->seleccionar($subcategoria_id);
            if (( ! $subcat) OR ((string) $subcat['categoria_id'] !== (string) $categoria_id))
            {
                $errores[] = 'subcategoria_id';
            }
        }

        // Unidad: entero positivo.
        $unidad = $this->input->post('unidad');
        if (($unidad !== NULL) AND ($unidad !== '') AND (( ! ctype_digit((string) $unidad)) OR ((int) $unidad < 1)))
        {
            $errores[] = 'unidad';
        }

        // Valores contables: numericos y no negativos (solo si se pueden ver/editar).
        if ($this->_puede_ver_valores())
        {
            foreach (array('valor_libro', 'depreciacion_acumulada') as $campo)
            {
                $valor = $this->input->post($campo);
                if (($valor !== NULL) AND ($valor !== '') AND (( ! is_numeric($valor)) OR ($valor < 0)))
                {
                    $errores[] = $campo;
                }
            }
        }

        // Fecha de adquisicion: formato YYYY-MM-DD y fecha de calendario real.
        $fecha = $this->input->post('fecha_adquisicion');
        if ($fecha)
        {
            if ( ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha))
            {
                $errores[] = 'fecha_adquisicion';
            }
            else
            {
                $partes = explode('-', $fecha);
                if ( ! checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0]))
                {
                    $errores[] = 'fecha_adquisicion';
                }
            }
        }

        return $errores;
    }

    private function _validar_longitud($campo, $maximo)
    {
        $valor = $this->input->post($campo);
        if ($valor AND (strlen($valor) > $maximo)) return array($campo);

        return array();
    }

    private function _validar_catalogo($campo, $tabla, $requerido)
    {
        $valor = $this->input->post($campo);

        if (( ! $valor) OR ($valor === ''))
        {
            return $requerido ? array($campo) : array();
        }

        if (( ! ctype_digit((string) $valor)) OR ((int) $valor < 1)) return array($campo);

        $this->catalogo->set_tabla($tabla);
        $fila = $this->catalogo->seleccionar($valor);

        if (( ! $fila) OR ($fila['activo'] != 1)) return array($campo);

        return array();
    }

    private function _resumen_cambios_edicion($anterior, $nuevo)
    {
        $etiquetas = array(
            'codigo_unico'           => 'Codigo unico',
            'descripcion'            => 'Descripcion',
            'marca'                  => 'Marca',
            'modelo'                 => 'Modelo',
            'numero_serie'           => 'N. de serie',
            'categoria_id'           => 'Categoria (id)',
            'valor_libro'            => 'Valor libro',
            'depreciacion_acumulada' => 'Depreciacion acumulada',
        );

        $cambios = array();

        foreach ($etiquetas as $campo => $etiqueta)
        {
            // Los campos contables solo estan en $nuevo si el usuario
            // tiene permiso para editarlos; si no estan, no se comparan.
            if ( ! array_key_exists($campo, $nuevo)) continue;

            $valor_anterior = isset($anterior[$campo]) ? $anterior[$campo] : NULL;
            $valor_nuevo = $nuevo[$campo];

            if ((string) $valor_anterior !== (string) $valor_nuevo)
            {
                $cambios[] = $etiqueta . ': "' . $valor_anterior . '" -> "' . $valor_nuevo . '"';
            }
        }

        if (empty($cambios)) return NULL;

        return implode('; ', $cambios);
    }

    private function _es_imagen_valida($ruta, $extension)
    {
        $extension = strtolower(ltrim($extension, '.'));
        if ( ! in_array($extension, array('jpg', 'jpeg', 'png'))) return FALSE;

        $info = @getimagesize($ruta);
        if ($info === FALSE) return FALSE;

        $tipos_permitidos = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);
        if ( ! in_array($info[2], $tipos_permitidos)) return FALSE;

        if (($info[0] > 6000) OR ($info[1] > 6000)) return FALSE;

        return TRUE;
    }
}

/* End of file inventario.php */
