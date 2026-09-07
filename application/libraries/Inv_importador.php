<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Inv_importador - Modulo Inventario
| -------------------------------------------------------------------
| Libreria (no modelo, porque no gestiona una sola tabla) que hace todo
| el trabajo pesado de la importacion desde Excel:
|   - Abre el archivo con PHPExcel y lee la hoja "Cruce Activos".
|   - Valida y normaliza cada fila (fechas, valores contables, Baja
|     Si/No, longitudes de texto).
|   - Resuelve catalogos por nombre (case-insensitive + trim), creando
|     los que falten SOLO cuando se le pide explicitamente.
|   - Detecta codigo_unico duplicado (dentro del mismo archivo, y contra
|     lo que ya existe en inv_activos).
|
| Dos formas de usarla, con la MISMA logica de validacion por dentro:
|   - analizar($ruta)              -> solo lectura, NUNCA escribe nada.
|   - importar($ruta, $usuario_id) -> inserta de verdad, en transaccion.
|
| Uso desde un controlador:
|   $this->load->library('inv_importador');
|   $this->load->model('inventario_catalogo_model', 'catalogo');
|   $this->load->model('inventario_subcategoria_model', 'subcategoria');
|   $this->inv_importador->set_conexion($this->db_inv);
|   $this->inv_importador->set_modelos($this->catalogo, $this->subcategoria);
|   $resultado = $this->inv_importador->analizar($ruta_archivo);
|
| Si application/third_party/PHPExcel/ no esta instalada, tanto
| analizar() como importar() devuelven ok=FALSE con 'error_general'
| explicando el problema -- nunca un error fatal de PHP.
| -------------------------------------------------------------------
*/
class Inv_importador {

    const HOJA = 'Cruce Activos';

    protected $db_inv;
    protected $catalogo_model;
    protected $subcategoria_model;

    private $_contador_pendientes = 0;

    public function set_conexion($db_inv)
    {
        $this->db_inv = $db_inv;
    }

    public function set_modelos($catalogo_model, $subcategoria_model)
    {
        $this->catalogo_model = $catalogo_model;
        $this->subcategoria_model = $subcategoria_model;
    }

    // ---------------------------------------------------------------
    // Vista previa: NUNCA inserta, actualiza ni crea nada en la BD.
    // ---------------------------------------------------------------
    public function analizar($ruta_archivo)
    {
        return $this->_procesar($ruta_archivo, FALSE, NULL);
    }

    // ---------------------------------------------------------------
    // Confirmacion: inserta dentro de una unica transaccion sobre
    // $this->db_inv. Si algo falla a mitad de camino, se revierte TODO
    // (no se deja una importacion a medias).
    // ---------------------------------------------------------------
    public function importar($ruta_archivo, $usuario_id)
    {
        return $this->_procesar($ruta_archivo, TRUE, (int) $usuario_id);
    }

    // ---------------------------------------------------------------
    // Motor comun
    // ---------------------------------------------------------------

    private function _procesar($ruta_archivo, $confirmar, $usuario_id)
    {
        if (function_exists('set_time_limit')) @set_time_limit(180);

        $resultado = array(
            'ok'            => FALSE,
            'error_general' => NULL,
            'filas'         => array(),
            'resumen'       => array(
                'total' => 0, 'nuevos' => 0, 'ya_existentes' => 0,
                'con_errores' => 0, 'con_advertencias' => 0,
            ),
            'catalogos_nuevos' => array(
                'gerencias' => array(), 'areas' => array(), 'categorias' => array(),
                'subcategorias' => array(), 'tipos' => array(), 'ubicaciones' => array(),
                'responsables' => array(), 'estados' => array(),
            ),
            'insertados' => 0,
            'omitidos'   => 0,
            'errores'    => 0,
        );

        if ( ! inv_excel_lib_disponible())
        {
            $resultado['error_general'] = 'La libreria PHPExcel no esta instalada en el servidor (application/third_party/PHPExcel/). Ver docs/IMPORTACION_EXCEL.md para instalarla.';

            return $resultado;
        }

        if (( ! $ruta_archivo) OR ( ! is_file($ruta_archivo)))
        {
            $resultado['error_general'] = 'El archivo ya no esta disponible en el servidor. Vuelve a subirlo.';

            return $resultado;
        }

        // PHPExcel 1.8.1 copiada a mano (sin Composer): un unico punto de
        // entrada, application/third_party/PHPExcel/PHPExcel.php. Desde ahi
        // el propio PHPExcel_Autoloader resuelve PHPExcel_IOFactory y el
        // resto de las clases contra la carpeta PHPExcel/ que vive junto a
        // el (application/third_party/PHPExcel/PHPExcel/) -- no hace falta
        // ningun require_once adicional ni vendor/autoload.php de Composer.
        // inv_excel_lib_disponible() ya comprobo que este archivo existe
        // antes de llegar hasta aca (ver mas arriba), asi que este
        // require_once no deberia fallar nunca en la practica.
        require_once(APPPATH . 'third_party/PHPExcel/PHPExcel.php');

        if ( ! class_exists('PHPExcel_IOFactory'))
        {
            $resultado['error_general'] = 'La libreria PHPExcel esta incompleta en el servidor (no se encontro PHPExcel_IOFactory). Revisa la instalacion en application/third_party/PHPExcel/.';

            return $resultado;
        }

        try
        {
            $lector = PHPExcel_IOFactory::createReaderForFile($ruta_archivo);
            $lector->setReadDataOnly(TRUE);
            $libro = $lector->load($ruta_archivo);
        }
        catch (Exception $e)
        {
            $resultado['error_general'] = 'No se pudo leer el archivo. Verifica que sea un Excel (.xlsx) valido y no este danado.';

            return $resultado;
        }

        if ( ! $libro->sheetNameExists(self::HOJA))
        {
            $resultado['error_general'] = 'El archivo no contiene una hoja llamada "' . self::HOJA . '". Revisa que sea la planilla correcta.';

            return $resultado;
        }

        $hoja = $libro->getSheetByName(self::HOJA);

        // toArray(NULL, TRUE, FALSE, FALSE): valores CRUDOS (sin dar
        // formato de fecha/moneda de antemano -- eso lo hace nuestro
        // propio helper), con formulas ya calculadas, indices 0-based en
        // ambas dimensiones. $todas[0] es la fila de encabezados (fila 1
        // real de la hoja); $todas[1] es la primera fila de datos (fila
        // 2 real de la hoja).
        $todas = $hoja->toArray(NULL, TRUE, FALSE, FALSE);

        if (empty($todas))
        {
            $resultado['error_general'] = 'La hoja "' . self::HOJA . '" esta vacia.';

            return $resultado;
        }

        $encabezados = array_shift($todas);
        $mapa_columnas = $this->_mapear_encabezados($encabezados);

        $obligatorios = array('codigo_unico' => 'Codigo Unico', 'categoria' => 'Categoria', 'descripcion' => 'Descripcion');
        $faltantes = array();
        foreach ($obligatorios as $campo => $etiqueta)
        {
            if ( ! isset($mapa_columnas[$campo])) $faltantes[] = $etiqueta;
        }

        if ( ! empty($faltantes))
        {
            $resultado['error_general'] = 'Faltan columnas obligatorias en "' . self::HOJA . '": ' . implode(', ', $faltantes) . '. Revisa que la primera fila tenga los encabezados originales de la planilla.';

            return $resultado;
        }

        $cache_catalogos = array();
        $vistos_codigos = array();
        $this->_contador_pendientes = 0;

        if ($confirmar) $this->db_inv->trans_start();

        $numero_fila = 1; // la fila 1 real ya se consumio como encabezado

        foreach ($todas as $fila_arr)
        {
            $numero_fila++;

            $cruda = $this->_extraer_fila($fila_arr, $mapa_columnas);

            if ($this->_fila_vacia($cruda)) continue;

            $resultado['resumen']['total']++;

            $procesada = $this->_resolver_fila($cruda, $numero_fila, $vistos_codigos, $confirmar, $cache_catalogos, $resultado['catalogos_nuevos']);

            if ($confirmar)
            {
                $this->_aplicar_confirmacion($procesada, $usuario_id, $resultado);
            }
            else
            {
                $this->_acumular_resumen($procesada['resultado'], $resultado['resumen']);
                $resultado['filas'][] = $procesada;
            }
        }

        if ($confirmar)
        {
            $this->db_inv->trans_complete();
            $resultado['ok'] = ($this->db_inv->trans_status() !== FALSE);

            if ( ! $resultado['ok'])
            {
                $resultado['error_general'] = 'Ocurrio un error durante la importacion. No se guardo ningun activo (se revirtio todo el archivo).';
                $resultado['insertados'] = 0;
                $resultado['omitidos'] = 0;
                $resultado['errores'] = $resultado['resumen']['total'];
            }
        }
        else
        {
            $resultado['ok'] = TRUE;
        }

        return $resultado;
    }

    private function _acumular_resumen($tipo, &$resumen)
    {
        switch ($tipo)
        {
            case 'LISTO_PARA_IMPORTAR': $resumen['nuevos']++; break;
            case 'ADVERTENCIA':         $resumen['con_advertencias']++; break;
            case 'YA_EXISTE':           $resumen['ya_existentes']++; break;
            case 'ERROR':               $resumen['con_errores']++; break;
        }
    }

    private function _aplicar_confirmacion($procesada, $usuario_id, &$resultado)
    {
        $this->_acumular_resumen($procesada['resultado'], $resultado['resumen']);

        if (in_array($procesada['resultado'], array('LISTO_PARA_IMPORTAR', 'ADVERTENCIA'), TRUE))
        {
            $id_activo = $this->_insertar_activo($procesada['datos'], $usuario_id);

            if ($id_activo)
            {
                $resultado['insertados']++;

                return;
            }

            $procesada['resultado'] = 'ERROR';
            $procesada['mensajes'][] = 'No se pudo insertar en la base de datos.';
        }

        if ($procesada['resultado'] === 'YA_EXISTE')
        {
            $resultado['omitidos']++;

            return;
        }

        // ERROR (incluye el caso de fallo de insercion de mas arriba).
        $resultado['errores']++;

        // Se conserva el detalle de errores solo hasta un tope, para no
        // acumular en memoria miles de filas en una confirmacion grande.
        if (count($resultado['filas']) < 200)
        {
            $resultado['filas'][] = $procesada;
        }
    }

    // ---------------------------------------------------------------
    // Lectura / mapeo de la hoja
    // ---------------------------------------------------------------

    private function _mapear_encabezados($encabezados)
    {
        $esperados = array(
            'codigo unico'      => 'codigo_unico',
            'codigo'             => 'codigo',
            'area'               => 'area',
            'grupo'              => 'grupo',
            'categoria'          => 'categoria',
            'subcategoria'       => 'subcategoria',
            'unidad'             => 'unidad',
            'descripcion'        => 'descripcion',
            'tipo'               => 'tipo',
            'marca'              => 'marca',
            'color'              => 'color',
            'modelo'             => 'modelo',
            'nro serie'          => 'numero_serie',
            'ubicacion fisica'   => 'ubicacion',
            'gerencia'           => 'gerencia',
            'cargo'              => 'cargo',
            'responsable'        => 'responsable',
            'fecha adquisicion'  => 'fecha_adquisicion',
            'valor libro'        => 'valor_libro',
            'dep acum total'     => 'depreciacion_acumulada',
            'estado articulo'    => 'estado',
            'baja si no'         => 'dado_baja',
            'observaciones'      => 'observaciones',
        );

        $mapa = array();

        foreach ($encabezados as $indice => $texto)
        {
            $clave = $this->_normalizar_encabezado($texto);

            if (isset($esperados[$clave]) AND ( ! isset($mapa[$esperados[$clave]])))
            {
                $mapa[$esperados[$clave]] = $indice;
            }
        }

        return $mapa;
    }

    private function _normalizar_encabezado($texto)
    {
        $texto = inv_normalizar($texto);

        $sin_tildes = array(
            "\xc3\xa1" => 'a', "\xc3\xa9" => 'e', "\xc3\xad" => 'i', "\xc3\xb3" => 'o', "\xc3\xba" => 'u',
            "\xc3\xb1" => 'n',
        );
        $texto = strtr($texto, $sin_tildes);
        $texto = preg_replace('/[^a-z0-9]+/', ' ', $texto);

        return trim($texto);
    }

    private function _extraer_fila($fila_arr, $mapa_columnas)
    {
        $cruda = array();

        foreach ($mapa_columnas as $campo => $indice)
        {
            $cruda[$campo] = isset($fila_arr[$indice]) ? $fila_arr[$indice] : NULL;
        }

        return $cruda;
    }

    private function _fila_vacia($cruda)
    {
        foreach ($cruda as $valor)
        {
            if (($valor !== NULL) AND (trim((string) $valor) !== '')) return FALSE;
        }

        return TRUE;
    }

    private function _valor($cruda, $campo)
    {
        return isset($cruda[$campo]) ? $cruda[$campo] : NULL;
    }

    // ---------------------------------------------------------------
    // Resolucion de una fila
    // ---------------------------------------------------------------

    private function _resolver_fila($cruda, $numero_fila, &$vistos_codigos, $crear, &$cache, &$catalogos_nuevos)
    {
        $codigo_unico = trim((string) $this->_valor($cruda, 'codigo_unico'));

        if ($codigo_unico === '')
        {
            return $this->_fila_resultado($numero_fila, NULL, 'ERROR', array('Codigo unico vacio.'), NULL);
        }

        if (strlen($codigo_unico) > 30)
        {
            return $this->_fila_resultado($numero_fila, $codigo_unico, 'ERROR', array('Codigo unico supera 30 caracteres.'), NULL);
        }

        $clave_codigo = inv_normalizar($codigo_unico);

        if (isset($vistos_codigos[$clave_codigo]))
        {
            return $this->_fila_resultado($numero_fila, $codigo_unico, 'ERROR', array('Codigo duplicado dentro del mismo archivo (ya aparece en la fila ' . $vistos_codigos[$clave_codigo] . ').'), NULL);
        }

        $vistos_codigos[$clave_codigo] = $numero_fila;

        if ($this->_existe_codigo($codigo_unico))
        {
            return $this->_fila_resultado($numero_fila, $codigo_unico, 'YA_EXISTE', array('Ya existe un activo con este codigo unico.'), NULL);
        }

        $mensajes = array();
        $es_advertencia = FALSE;

        $descripcion = trim((string) $this->_valor($cruda, 'descripcion'));
        $categoria_txt = trim((string) $this->_valor($cruda, 'categoria'));

        $errores_bloqueantes = array();
        if ($descripcion === '') $errores_bloqueantes[] = 'Descripcion vacia.';
        if ($categoria_txt === '') $errores_bloqueantes[] = 'Categoria vacia.';

        if ( ! empty($errores_bloqueantes))
        {
            return $this->_fila_resultado($numero_fila, $codigo_unico, 'ERROR', $errores_bloqueantes, NULL);
        }

        // -------- Catalogos --------

        $gerencia = $this->_resolver_catalogo('inv_gerencias', $this->_valor($cruda, 'gerencia'), $crear, $cache, array(), $catalogos_nuevos, 'gerencias');

        // inv_areas.nombre sigue siendo UNICO DE FORMA GLOBAL (no por
        // gerencia) -- ver sql/003_corregir_indices_catalogos.sql, seccion
        // 2, para la explicacion completa de por que se dejo asi. Por eso
        // gerencia_id NO se usa para BUSCAR/agrupar (5to argumento, campo
        // "scope" de _resolver_catalogo -- si se usara ahi, una misma area
        // sin gerencia en una fila y con gerencia en otra se tratarian
        // como dos areas distintas, y el INSERT de la segunda chocaria
        // contra la UNIQUE KEY global por nombre, el mismo error 1062 que
        // se corrigio para Tipos). gerencia_id solo se guarda como dato
        // informativo si la area se termina CREANDO de cero (8vo
        // argumento, solo aplica al INSERT, nunca a la busqueda).
        $area_nombre = trim((string) $this->_valor($cruda, 'area'));
        $area_extra_creacion = array();
        if (($area_nombre !== '') AND ($gerencia['id'] !== NULL) AND ($gerencia['id'] > 0))
        {
            $area_extra_creacion = array('gerencia_id' => $gerencia['id']);
        }
        $area = $this->_resolver_catalogo('inv_areas', $area_nombre, $crear, $cache, array(), $catalogos_nuevos, 'areas', $area_extra_creacion);

        $categoria = $this->_resolver_catalogo('inv_categorias', $categoria_txt, $crear, $cache, array(), $catalogos_nuevos, 'categorias');

        $subcategoria_txt = trim((string) $this->_valor($cruda, 'subcategoria'));
        $subcategoria = $this->_resolver_subcategoria($subcategoria_txt, $categoria['id'], $crear, $cache, $catalogos_nuevos);

        // A diferencia de Area/Gerencia: inv_tipos SI tiene una UNIQUE KEY
        // compuesta (subcategoria_id, nombre) desde
        // sql/003_corregir_indices_catalogos.sql, asi que aqui
        // subcategoria_id va como "scope" (5to argumento: se usa para
        // BUSCAR y para agrupar en cache, no solo al crear) -- un mismo
        // nombre de Tipo (ej. "Edificio") bajo dos Subcategorias distintas
        // son, a proposito, dos filas distintas de inv_tipos.
        $tipo_txt = trim((string) $this->_valor($cruda, 'tipo'));
        $tipo_scope = array();
        if (($tipo_txt !== '') AND ($subcategoria['id'] !== NULL) AND ($subcategoria['id'] > 0))
        {
            $tipo_scope = array('subcategoria_id' => $subcategoria['id']);
        }
        $tipo = $this->_resolver_catalogo('inv_tipos', $tipo_txt, $crear, $cache, $tipo_scope, $catalogos_nuevos, 'tipos');

        $ubicacion = $this->_resolver_catalogo('inv_ubicaciones', $this->_valor($cruda, 'ubicacion'), $crear, $cache, array(), $catalogos_nuevos, 'ubicaciones');

        $responsable = $this->_resolver_catalogo('inv_responsables', $this->_valor($cruda, 'responsable'), $crear, $cache, array(), $catalogos_nuevos, 'responsables');

        // -------- Baja Si/No --------

        $baja = inv_excel_interpretar_baja($this->_valor($cruda, 'dado_baja'));
        if ( ! $baja['reconocido'])
        {
            $mensajes[] = 'Valor de "Baja Si/No" no reconocido ("' . trim((string) $this->_valor($cruda, 'dado_baja')) . '"), se interpreto como "No".';
            $es_advertencia = TRUE;
        }

        // -------- Estado --------

        $estado_txt = trim((string) $this->_valor($cruda, 'estado'));
        if (($estado_txt === '') AND ($baja['dado_baja'] == 1))
        {
            $estado_txt = 'Dado de baja';
        }
        $estado = $this->_resolver_catalogo('inv_estados', $estado_txt, $crear, $cache, array(), $catalogos_nuevos, 'estados');

        // -------- Fecha --------

        $fecha_cruda = $this->_valor($cruda, 'fecha_adquisicion');
        $fecha = inv_excel_fecha_a_ymd($fecha_cruda);
        if ($fecha === FALSE)
        {
            $mensajes[] = 'Fecha de adquisicion no reconocida ("' . trim((string) $fecha_cruda) . '"), se dejo vacia.';
            $es_advertencia = TRUE;
            $fecha = NULL;
        }

        // -------- Valores contables --------

        $valor_libro_r = inv_excel_valor_contable($this->_valor($cruda, 'valor_libro'));
        if ( ! $valor_libro_r['valido'])
        {
            $mensajes[] = 'Valor libro no reconocido, se dejo vacio.';
            $es_advertencia = TRUE;
        }
        if ($valor_libro_r['negativo'])
        {
            $mensajes[] = 'Valor libro negativo (' . $valor_libro_r['valor'] . '), revisar.';
            $es_advertencia = TRUE;
        }

        $dep_r = inv_excel_valor_contable($this->_valor($cruda, 'depreciacion_acumulada'));
        if ( ! $dep_r['valido'])
        {
            $mensajes[] = 'Depreciacion acumulada no reconocida, se dejo vacia.';
            $es_advertencia = TRUE;
        }
        if ($dep_r['negativo'])
        {
            $mensajes[] = 'Depreciacion acumulada negativa (' . $dep_r['valor'] . '), revisar.';
            $es_advertencia = TRUE;
        }

        // -------- Unidad --------

        $unidad_cruda = $this->_valor($cruda, 'unidad');
        $unidad = 1;
        if (($unidad_cruda !== NULL) AND (trim((string) $unidad_cruda) !== ''))
        {
            if (is_numeric($unidad_cruda) AND ((int) $unidad_cruda >= 1))
            {
                $unidad = (int) $unidad_cruda;
            }
            else
            {
                $mensajes[] = 'Unidad invalida ("' . trim((string) $unidad_cruda) . '"), se uso 1.';
                $es_advertencia = TRUE;
            }
        }

        // -------- Longitudes de texto --------

        $codigo       = $this->_truncar_con_aviso($this->_valor($cruda, 'codigo'), 20, 'Codigo', $mensajes, $es_advertencia);
        $grupo        = $this->_truncar_con_aviso($this->_valor($cruda, 'grupo'), 30, 'Grupo', $mensajes, $es_advertencia);
        $marca        = $this->_truncar_con_aviso($this->_valor($cruda, 'marca'), 80, 'Marca', $mensajes, $es_advertencia);
        $modelo       = $this->_truncar_con_aviso($this->_valor($cruda, 'modelo'), 80, 'Modelo', $mensajes, $es_advertencia);
        $color        = $this->_truncar_con_aviso($this->_valor($cruda, 'color'), 40, 'Color', $mensajes, $es_advertencia);
        $numero_serie = $this->_truncar_con_aviso($this->_valor($cruda, 'numero_serie'), 80, 'N. de serie', $mensajes, $es_advertencia);
        $cargo        = $this->_truncar_con_aviso($this->_valor($cruda, 'cargo'), 150, 'Cargo', $mensajes, $es_advertencia);

        $observaciones = trim((string) $this->_valor($cruda, 'observaciones'));

        $datos = array(
            'codigo_unico'           => $codigo_unico,
            'codigo'                 => ($codigo !== '') ? $codigo : NULL,
            'gerencia_id'            => ($gerencia['id'] > 0) ? $gerencia['id'] : NULL,
            'area_id'                => ($area['id'] > 0) ? $area['id'] : NULL,
            'grupo'                  => ($grupo !== '') ? $grupo : NULL,
            'categoria_id'           => $categoria['id'],
            'subcategoria_id'        => ($subcategoria['id'] > 0) ? $subcategoria['id'] : NULL,
            'tipo_id'                => ($tipo['id'] > 0) ? $tipo['id'] : NULL,
            'unidad'                 => $unidad,
            'descripcion'            => $descripcion,
            'marca'                  => ($marca !== '') ? $marca : NULL,
            'color'                  => ($color !== '') ? $color : NULL,
            'modelo'                 => ($modelo !== '') ? $modelo : NULL,
            'numero_serie'           => ($numero_serie !== '') ? $numero_serie : NULL,
            'ubicacion_id'           => ($ubicacion['id'] > 0) ? $ubicacion['id'] : NULL,
            'cargo'                  => ($cargo !== '') ? $cargo : NULL,
            'responsable_id'         => ($responsable['id'] > 0) ? $responsable['id'] : NULL,
            'fecha_adquisicion'      => $fecha,
            'valor_libro'            => $valor_libro_r['valor'],
            'depreciacion_acumulada' => $dep_r['valor'],
            'estado_id'              => ($estado['id'] > 0) ? $estado['id'] : NULL,
            'dado_baja'              => $baja['dado_baja'],
            'observaciones'          => ($observaciones !== '') ? $observaciones : NULL,
        );

        $resultado_final = $es_advertencia ? 'ADVERTENCIA' : 'LISTO_PARA_IMPORTAR';

        return $this->_fila_resultado($numero_fila, $codigo_unico, $resultado_final, $mensajes, $datos);
    }

    private function _fila_resultado($numero_fila, $codigo_unico, $resultado, $mensajes, $datos)
    {
        return array(
            'fila'         => $numero_fila,
            'codigo_unico' => $codigo_unico,
            'resultado'    => $resultado,
            'mensajes'     => $mensajes,
            'datos'        => $datos,
        );
    }

    private function _truncar_con_aviso($valor, $maximo, $etiqueta, &$mensajes, &$es_advertencia)
    {
        $texto = trim((string) $valor);
        if ($texto === '') return '';

        $largo = function_exists('mb_strlen') ? mb_strlen($texto, 'UTF-8') : strlen($texto);

        if ($largo > $maximo)
        {
            $mensajes[] = $etiqueta . ' supera ' . $maximo . ' caracteres, se recorto.';
            $es_advertencia = TRUE;

            return function_exists('mb_substr') ? mb_substr($texto, 0, $maximo, 'UTF-8') : substr($texto, 0, $maximo);
        }

        return $texto;
    }

    // ---------------------------------------------------------------
    // Catalogos: busqueda case-insensitive + trim, creacion opcional
    // ---------------------------------------------------------------

    private function _existe_codigo($codigo_unico)
    {
        $this->db_inv->where('codigo_unico', $codigo_unico);
        $query = $this->db_inv->get('inv_activos');

        return ($query->num_rows() > 0);
    }

    /*
    | $campos_scope: columnas que de verdad forman parte de la unicidad
    | del catalogo (deben coincidir con la UNIQUE KEY real de la tabla en
    | sql/001_inventario_instalacion.sql / 003_corregir_indices_catalogos.sql).
    | Se usan tanto para BUSCAR (WHERE) como para agrupar en $cache, y
    | tambien se guardan al crear. Ejemplo: array('subcategoria_id' => 6)
    | para inv_tipos, que tiene UNIQUE(subcategoria_id, nombre).
    |
    | $campos_extra_creacion: columnas adicionales que SOLO se guardan si
    | el catalogo se termina creando de cero -- nunca se usan para buscar
    | ni entran en la clave de $cache. Para columnas que son solo dato
    | informativo, no parte de la unicidad real de la tabla. Ejemplo:
    | array('gerencia_id' => 5) para inv_areas, que sigue siendo UNIQUE
    | solo por `nombre` (ver seccion 2 de
    | sql/003_corregir_indices_catalogos.sql) -- si gerencia_id se usara
    | para buscar, una misma area sin gerencia en una fila y con gerencia
    | en otra se tratarian como dos filas distintas, y la segunda chocaria
    | contra esa UNIQUE KEY global (el mismo error 1062 que tenia Tipos).
    |
    | $bucket: clave dentro de catalogos_nuevos donde anotar el nombre
    | si termina creandose (o "se crearia", en vista previa).
    |
    | Devuelve array('id' => int, 'nuevo' => bool). En vista previa
    | (($crear == FALSE)) un catalogo que no existe recibe un id NEGATIVO
    | "pendiente" (solo tiene sentido dentro de este mismo analisis, para
    | que otra fila que dependa del mismo catalogo nuevo -- ej. una
    | Subcategoria de una Categoria que todavia no existe -- se resuelva
    | de forma consistente sin tocar la base de datos).
    */
    private function _resolver_catalogo($tabla, $nombre_crudo, $crear, &$cache, $campos_scope, &$catalogos_nuevos, $bucket, $campos_extra_creacion = array())
    {
        $nombre = trim((string) $nombre_crudo);

        if ($nombre === '')
        {
            return array('id' => NULL, 'nuevo' => FALSE);
        }

        $sufijo = '';
        foreach ($campos_scope as $k => $v) $sufijo .= $k . '=' . $v . ';';

        $clave = $tabla . '|' . $sufijo . '|' . inv_normalizar($nombre);

        if (isset($cache[$clave])) return $cache[$clave];

        $this->db_inv->where('LOWER(TRIM(nombre))', inv_normalizar($nombre));
        foreach ($campos_scope as $k => $v) $this->db_inv->where($k, $v);
        $query = $this->db_inv->get($tabla);

        if ($query->num_rows() > 0)
        {
            $fila = $query->row_array();
            $resultado = array('id' => (int) $fila['id'], 'nuevo' => FALSE);
            $cache[$clave] = $resultado;

            return $resultado;
        }

        if ($crear)
        {
            $this->catalogo_model->set_tabla($tabla);
            $datos_insertar = array_merge(array('nombre' => $nombre, 'activo' => 1), $campos_scope, $campos_extra_creacion);
            $id = $this->catalogo_model->insertar($datos_insertar);

            $resultado = array('id' => $id ? (int) $id : NULL, 'nuevo' => (bool) $id);
            $cache[$clave] = $resultado;

            if ($id) $this->_anotar_catalogo_nuevo($catalogos_nuevos, $bucket, $nombre);

            return $resultado;
        }

        $this->_contador_pendientes--;
        $resultado = array('id' => $this->_contador_pendientes, 'nuevo' => TRUE);
        $cache[$clave] = $resultado;

        $this->_anotar_catalogo_nuevo($catalogos_nuevos, $bucket, $nombre);

        return $resultado;
    }

    private function _resolver_subcategoria($nombre_crudo, $categoria_id, $crear, &$cache, &$catalogos_nuevos)
    {
        $nombre = trim((string) $nombre_crudo);

        if (($nombre === '') OR ($categoria_id === NULL))
        {
            return array('id' => NULL, 'nuevo' => FALSE);
        }

        $clave = 'inv_subcategorias|categoria_id=' . $categoria_id . '|' . inv_normalizar($nombre);

        if (isset($cache[$clave])) return $cache[$clave];

        // Un categoria_id "pendiente" (negativo, de vista previa) nunca
        // puede tener subcategorias reales todavia -- se salta la
        // consulta a la base de datos directamente.
        if ($categoria_id > 0)
        {
            $this->db_inv->where('categoria_id', $categoria_id);
            $this->db_inv->where('LOWER(TRIM(nombre))', inv_normalizar($nombre));
            $query = $this->db_inv->get('inv_subcategorias');

            if ($query->num_rows() > 0)
            {
                $fila = $query->row_array();
                $resultado = array('id' => (int) $fila['id'], 'nuevo' => FALSE);
                $cache[$clave] = $resultado;

                return $resultado;
            }
        }

        if ($crear AND ($categoria_id > 0))
        {
            $id = $this->subcategoria_model->insertar(array(
                'categoria_id' => $categoria_id,
                'nombre'       => $nombre,
                'activo'       => 1,
            ));

            $resultado = array('id' => $id ? (int) $id : NULL, 'nuevo' => (bool) $id);
            $cache[$clave] = $resultado;

            if ($id) $this->_anotar_catalogo_nuevo($catalogos_nuevos, 'subcategorias', $nombre);

            return $resultado;
        }

        $this->_contador_pendientes--;
        $resultado = array('id' => $this->_contador_pendientes, 'nuevo' => TRUE);
        $cache[$clave] = $resultado;

        $this->_anotar_catalogo_nuevo($catalogos_nuevos, 'subcategorias', $nombre);

        return $resultado;
    }

    private function _anotar_catalogo_nuevo(&$catalogos_nuevos, $bucket, $nombre)
    {
        if ( ! isset($catalogos_nuevos[$bucket])) return;
        if (in_array($nombre, $catalogos_nuevos[$bucket], TRUE)) return;

        $catalogos_nuevos[$bucket][] = $nombre;
    }

    // ---------------------------------------------------------------
    // Insercion real (solo se llama desde importar())
    // ---------------------------------------------------------------

    private function _insertar_activo($datos, $usuario_id)
    {
        $datos['creado'] = date('Y-m-d H:i:s');
        $datos['creado_por'] = $usuario_id;

        if ( ! $this->db_inv->insert('inv_activos', $datos)) return FALSE;

        $id = $this->db_inv->insert_id();

        $this->db_inv->insert('inv_movimientos', array(
            'activo_id'       => $id,
            'tipo_movimiento' => 'ALTA',
            'valor_anterior'  => NULL,
            'valor_nuevo'     => $datos['codigo_unico'],
            'motivo'          => 'Alta por importacion de Excel',
            'observacion'     => NULL,
            'usuario_id'      => $usuario_id,
            'fecha'           => date('Y-m-d H:i:s'),
        ));

        return $id;
    }
}

/* End of file Inv_importador.php */
