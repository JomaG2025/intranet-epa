<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Inventario_importacion - Modulo Inventario
| -------------------------------------------------------------------
| Controlador de "Inventario > Importar Excel". Solo ADM (misma regla
| que _puede_editar() en application/controllers/inventario.php).
|
| Flujo de 3 pasos, con el archivo guardado en el servidor entre pasos
| (nunca se le pide al navegador que reenvie los datos de las filas):
|
|   1. subir() [GET] + procesar_subida() [POST]
|      Sube el .xlsx a uploads/inventario/importaciones/ con nombre
|      generado en el servidor (nunca el nombre original) y guarda la
|      RUTA (no el contenido) en sesion.
|
|   2. vista_previa() [GET]
|      Lee el archivo guardado en sesion y llama a
|      Inv_importador::analizar() -- SOLO LECTURA, no se escribe nada en
|      la base de datos en este paso, se puede recargar la pantalla
|      cuantas veces haga falta.
|
|   3. confirmar() [POST]
|      Vuelve a leer el MISMO archivo (por la ruta guardada en sesion,
|      nunca por datos que reenvie el navegador) y llama a
|      Inv_importador::importar(), que inserta dentro de una transaccion
|      y devuelve los mismos contadores que ya se vieron en el paso 2.
|      Al terminar (bien o mal) se borra el archivo del servidor.
|
| cancelar() borra el archivo en staging sin importar nada.
| historial() lista las importaciones ya confirmadas (tabla
| inv_importaciones).
|
| BASE DE DATOS: igual que Inventario, usa una conexion propia a
| intranet_inventario compartida entre los modelos que la necesitan.
| Ver docs/BASE_DATOS_INVENTARIO.md e docs/IMPORTACION_EXCEL.md.
|
| PERMISOS (revision 6): "solo ADM" ahora significa ADM DENTRO DEL
| MODULO (tabla inv_usuarios), no el privilegio general de la intranet
| -- ver application/helpers/inv_permisos_helper.php y
| sql/004_control_acceso_inventario.sql. La exigencia de sesion + acceso
| basico al modulo la hace la clase base Inventario_Controller; este
| controlador solo agrega el requisito ADM especifico de importar Excel.
| -------------------------------------------------------------------
*/
require_once(APPPATH . 'core/Inventario_Controller.php');

class Inventario_importacion extends Inventario_Controller {

    public function __construct()
    {
        parent::__construct();

        // Sesion, acceso al modulo y $this->db_inv ya quedaron resueltos
        // en el constructor de Inventario_Controller.

        $this->load->model('inventario_catalogo_model', 'catalogo');
        $this->catalogo->set_conexion($this->db_inv);

        $this->load->model('inventario_subcategoria_model', 'subcategoria');
        $this->subcategoria->set_conexion($this->db_inv);

        $this->load->model('inventario_importacion_model', 'importacion');
        $this->importacion->set_conexion($this->db_inv);

        // Auditoria general: conexion normal (intranet), sin cambios.
        $this->load->model('auditoria_model', 'auditoria');

        $this->load->helper(array('inventario', 'inv_csrf', 'inv_excel'));
        $this->lang->load('inventario', 'spanish');
    }

    public function index()
    {
        redirect('inventario/importar');
    }

    // ---------------------------------------------------------------
    // Paso 1: subir archivo
    // ---------------------------------------------------------------

    public function subir()
    {
        $this->_requerir_administrador();

        $data['lib_disponible'] = inv_excel_lib_disponible();

        $this->load->view('inventario/header');
        $this->load->view('inventario_importacion/subir', $data);
        $this->load->view('inventario/footer');
    }

    public function procesar_subida()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        if ( ! inv_excel_lib_disponible())
        {
            redirect('inventario/importar', 'inv_danger_06', TRUE);
        }

        $carpeta = $this->config->item('upload_path') . 'inventario/importaciones/';
        if ( ! is_dir($carpeta)) @mkdir($carpeta, DIR_WRITE_MODE, TRUE);

        // Igual que subir_foto() en Inventario: el nombre del archivo en
        // disco se arma solo con datos del servidor (tiempo + numero
        // aleatorio), nunca con el nombre que mando el navegador.
        $config['upload_path'] = $carpeta;
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = 10240;
        $config['file_name'] = 'import-' . time() . '-' . mt_rand(1000, 9999);
        $config['encrypt_name'] = FALSE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('archivo'))
        {
            redirect('inventario/importar', 'inv_danger_06', TRUE);
        }

        $subida = $this->upload->data();

        // Se limpia cualquier archivo en proceso anterior que el usuario
        // no haya confirmado ni cancelado.
        $this->_limpiar_archivo_en_sesion();

        $this->session->set_userdata('inv_importacion_ruta', $subida['full_path']);
        // El nombre ORIGINAL solo se guarda como TEXTO, para mostrarlo en
        // pantalla y en el historial -- nunca se usa para construir rutas.
        $this->session->set_userdata('inv_importacion_nombre_original', $subida['orig_name']);

        redirect('inventario/importar/vista-previa');
    }

    // ---------------------------------------------------------------
    // Paso 2: vista previa (solo lectura)
    // ---------------------------------------------------------------

    public function vista_previa()
    {
        $this->_requerir_administrador();

        $ruta = $this->session->userdata('inv_importacion_ruta');
        if ( ! $ruta) redirect('inventario/importar', 'inv_danger_07', TRUE);

        $data['resultado'] = $this->_analizador()->analizar($ruta);
        $data['nombre_archivo'] = $this->session->userdata('inv_importacion_nombre_original');

        $this->load->view('inventario/header');
        $this->load->view('inventario_importacion/vista_previa', $data);
        $this->load->view('inventario/footer');
    }

    // ---------------------------------------------------------------
    // Paso 3: confirmar (unica escritura real en la base de datos)
    // ---------------------------------------------------------------

    public function confirmar()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $ruta = $this->session->userdata('inv_importacion_ruta');
        $nombre_original = $this->session->userdata('inv_importacion_nombre_original');

        if ( ! $ruta) redirect('inventario/importar', 'inv_danger_07', TRUE);

        $resultado = $this->_analizador()->importar($ruta, (int) $this->session->userdata('id'));

        if ($resultado['ok'])
        {
            $this->importacion->insertar(array(
                'nombre_archivo' => $nombre_original,
                'usuario_id'     => (int) $this->session->userdata('id'),
                'total_filas'    => $resultado['resumen']['total'],
                'insertados'     => $resultado['insertados'],
                'omitidos'       => $resultado['omitidos'],
                'errores'        => $resultado['errores'],
            ));

            // 'NEW' (no 'INS'): la tabla auditoria de la intranet real tiene
            // una FK evento -> int_evento.abrev que solo acepta NEW/UPD/DEL.
            // La importacion crea activos nuevos, igual que grabar() en
            // este mismo controlador -- mismo evento, 'NEW'.
            $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'inv_activos', 'instancia' => 0));
        }

        // El archivo en staging ya cumplio su proposito -- se leyo en
        // vista_previa() y aqui otra vez, siempre con el mismo contenido
        // (nunca se confio en nada que haya reenviado el navegador entre
        // medio). Se borra siempre, haya salido bien o mal.
        $this->_limpiar_archivo_en_sesion();

        $data['resultado'] = $resultado;
        $data['nombre_archivo'] = $nombre_original;

        $this->load->view('inventario/header');
        $this->load->view('inventario_importacion/resultado', $data);
        $this->load->view('inventario/footer');
    }

    public function cancelar()
    {
        $this->_requerir_administrador();
        $this->_validar_csrf();

        $this->_limpiar_archivo_en_sesion();

        redirect('inventario/importar');
    }

    // ---------------------------------------------------------------
    // Historial
    // ---------------------------------------------------------------

    public function historial()
    {
        $this->_requerir_administrador();

        $data['importaciones'] = $this->importacion->listar();

        $this->load->view('inventario/header');
        $this->load->view('inventario_importacion/historial', $data);
        $this->load->view('inventario/footer');
    }

    // ---------------------------------------------------------------
    // Privados
    // ---------------------------------------------------------------

    private function _analizador()
    {
        $this->load->library('inv_importador');
        $this->inv_importador->set_conexion($this->db_inv);
        $this->inv_importador->set_modelos($this->catalogo, $this->subcategoria);

        return $this->inv_importador;
    }

    private function _limpiar_archivo_en_sesion()
    {
        $ruta = $this->session->userdata('inv_importacion_ruta');
        if ($ruta AND is_file($ruta)) @unlink($ruta);

        $this->session->unset_userdata('inv_importacion_ruta');
        $this->session->unset_userdata('inv_importacion_nombre_original');
    }

    private function _requerir_administrador()
    {
        // ADM DENTRO DEL MODULO (inv_usuarios), no privilegio general de
        // la intranet -- ver application/helpers/inv_permisos_helper.php.
        if ( ! inv_es_admin())
        {
            redirect('inventario', 'danger_06', TRUE);
        }
    }

    private function _validar_csrf()
    {
        if ( ! inv_csrf_validar($this->input->post('inv_csrf_token')))
        {
            redirect('inventario/importar', 'inv_danger_05', TRUE);
        }
    }
}

/* End of file inventario_importacion.php */
