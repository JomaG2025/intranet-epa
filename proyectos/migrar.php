<?php
// /intranet/proyectos/migrar.php
require_once 'config/conexion.php';

$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_csv'])) {
    $archivo = $_FILES['archivo_csv']['tmp_name'];

    if ($_FILES['archivo_csv']['error'] == UPLOAD_ERR_OK && is_uploaded_file($archivo)) {
        if (($handle = fopen($archivo, "r")) !== FALSE) {
            $pdo->beginTransaction();
            try {
                $header_idx = [];

                // Leer hasta encontrar la fila de encabezados
                while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
                    $encontrado = false;
                    foreach ($data as $col) {
                        if (strpos($col, 'Nombre de la iniciativa') !== false) {
                            $encontrado = true;
                            break;
                        }
                    }
                    if ($encontrado) {
                        $header_idx = array_flip($data);
                        break;
                    }
                }

                if (empty($header_idx)) {
                    throw new Exception("No se encontró la fila de encabezados en el archivo.");
                }

                // Mapeo dinámico de columnas (tolerante a tildes mal codificadas)
                $cols = [
                    'nombre'                 => 'Nombre de la iniciativa',
                    'bip'                    => 'Cod. BIP',
                    'rate'                   => 'RATE',
                    'gerencia'               => 'Gerencia',
                    'estado'                 => 'Estado de la Iniciativa',
                    'ejecucion'              => '% de Ejecuci',     // parcial para saltear tildes
                    'tipologia'              => 'Tipolog',           // parcial
                    'etapa_postulacion'      => 'Etapa de Postulaci', // parcial
                    'prioridad_estrategica'  => 'Prioridad Estrat',  // parcial
                    'alineacion_estrategica' => 'Alineaci',          // parcial
                    'fuente_financiamiento'  => 'Fuente de Financiamiento',
                    'clasificacion'          => 'Clasificaci',       // parcial
                    'descripcion'            => 'Breve Descripci',   // parcial
                    'presupuesto_ip'         => 'Presupuesto IP',
                    'presupuesto_adjudicado' => 'Presupuesto Adjudicado',
                    'presupuesto_ejecutado'  => 'Presupuestos Ejecutados',
                    'observaciones'          => 'Observaciones',
                    'encargados'             => 'Encargados',
                ];

                $idx = [];
                foreach ($cols as $key => $patron) {
                    foreach ($header_idx as $nombre_col => $i) {
                        if (strpos($nombre_col, $patron) !== false) {
                            $idx[$key] = $i;
                            break;
                        }
                    }
                }

                if (!isset($idx['nombre'])) {
                    throw new Exception("No se encontró la columna 'Nombre de la iniciativa'.");
                }

                $insertados = 0;

                while (($row = fgetcsv($handle, 10000, ";")) !== FALSE) {
                    $get = function($key) use ($row, $idx) {
                        return (isset($idx[$key]) && isset($row[$idx[$key]])) ? trim($row[$idx[$key]]) : '';
                    };

                    $nombre = utf8_encode($get('nombre'));
                    if (empty($nombre)) continue;

                    // Porcentaje de ejecución
                    $ejecucion = floatval(str_replace(['%', ','], ['', '.'], $get('ejecucion')));

                    // Montos: quitar puntos de miles, convertir coma decimal
                    $limpiar_monto = function($v) {
                        $v = str_replace(['.', ' '], '', trim($v));
                        $v = str_replace(',', '.', $v);
                        return ($v !== '' && is_numeric($v)) ? round(floatval($v)) : null;
                    };

                    $presupuesto_ip         = $limpiar_monto($get('presupuesto_ip'));
                    $presupuesto_adjudicado = $limpiar_monto($get('presupuesto_adjudicado'));
                    $presupuesto_ejecutado  = $limpiar_monto($get('presupuesto_ejecutado'));

                    // Estado → buscar ID en la tabla de estados
                    $id_estado  = null;
                    $estado_str = utf8_encode($get('estado'));
                    if (!empty($estado_str)) {
                        $stmtE = $pdo->prepare("SELECT id FROM pry_estados WHERE nombre LIKE ? LIMIT 1");
                        $stmtE->execute(["%" . $estado_str . "%"]);
                        $resultado_id = $stmtE->fetchColumn();
                        if ($resultado_id) $id_estado = $resultado_id;
                    }

                    $sql = "INSERT INTO pry_proyectos (
                                cod_bip, nombre, descripcion,
                                tipologia, etapa_postulacion, clasificacion,
                                prioridad_estrategica, alineacion_estrategica, fuente_financiamiento,
                                rate, porcentaje_ejecucion, codigo_gerencia, id_estado,
                                presupuesto_ip, presupuesto_adjudicado, presupuesto_ejecutado,
                                observaciones
                            ) VALUES (
                                ?, ?, ?,
                                ?, ?, ?,
                                ?, ?, ?,
                                ?, ?, ?, ?,
                                ?, ?, ?,
                                ?
                            )";

                    $cod_bip              = $get('bip')                    ?: null;
                    $rate                 = $get('rate')                   ?: null;
                    $gerencia             = $get('gerencia')               ?: null;
                    $tipologia            = utf8_encode($get('tipologia')) ?: null;
                    $etapa_postulacion    = utf8_encode($get('etapa_postulacion')) ?: null;
                    $clasificacion        = utf8_encode($get('clasificacion'))     ?: null;
                    $prioridad            = utf8_encode($get('prioridad_estrategica')) ?: null;
                    $alineacion           = utf8_encode($get('alineacion_estrategica')) ?: null;
                    $fuente               = $get('fuente_financiamiento')  ?: null;
                    $descripcion          = utf8_encode($get('descripcion')) ?: null;
                    $observaciones        = utf8_encode($get('observaciones')) ?: null;

                    $pdo->prepare($sql)->execute([
                        $cod_bip, $nombre, $descripcion,
                        $tipologia, $etapa_postulacion, $clasificacion,
                        $prioridad, $alineacion, $fuente,
                        $rate, $ejecucion, $gerencia, $id_estado,
                        $presupuesto_ip, $presupuesto_adjudicado, $presupuesto_ejecutado,
                        $observaciones,
                    ]);

                    // Encargados
                    $encargados_str = $get('encargados');
                    if (!empty($encargados_str)) {
                        $id_nuevo = $pdo->lastInsertId();
                        $stmt_enc = $pdo->prepare("INSERT INTO pry_proyectos_encargados (id_proyecto, usuario) VALUES (?, ?)");
                        foreach (explode(',', utf8_encode($encargados_str)) as $enc) {
                            $enc = trim($enc);
                            if ($enc !== '') $stmt_enc->execute([$id_nuevo, $enc]);
                        }
                    }

                    $insertados++;
                }

                $pdo->commit();
                $mensaje = "¡Migración Exitosa! Se importaron <strong>$insertados proyectos</strong> a la base de datos.";

            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Ocurrió un error crítico: " . $e->getMessage();
            }
            fclose($handle);
        } else {
            $error = "No se pudo abrir el archivo subido.";
        }
    } else {
        $error = "Error al subir el archivo. Verifica que sea un formato válido.";
    }
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/intranet/header.php')) {
    include $_SERVER['DOCUMENT_ROOT'].'/intranet/header.php';
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/header.php')) {
    include $_SERVER['DOCUMENT_ROOT'].'/header.php';
}
?>

<div class="container-fluid" style="padding-top: 20px;">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-upload"></i> Migración Masiva de Cartera (CSV)</h3>
                </div>
                <div class="panel-body">

                    <?php if ($mensaje): ?>
                        <div class="alert alert-success"><i class="fa fa-check"></i> <?= $mensaje ?></div>
                        <a href="index.php" class="btn btn-primary">Ir al Dashboard</a>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><i class="fa fa-warning"></i> <?= $error ?></div>
                    <?php endif; ?>

                    <?php if (!$mensaje): ?>
                        <p>El sistema importa el archivo CSV con delimitador <code>;</code> y mapea automáticamente las columnas disponibles.</p>
                        <p><strong>Columnas que se importan:</strong> Nombre, Cod. BIP, RATE, Estado, % Ejecución, Tipología, Etapa de Postulación,
                           Prioridad Estratégica, Alineación Estratégica, Fuente de Financiamiento, Clasificación, Descripción,
                           Presupuesto IP, Presupuesto Adjudicado, Presupuesto Ejecutado, Observaciones, Encargados, Gerencia.</p>
                        <form action="migrar.php" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                            <div class="form-group">
                                <label>Seleccionar Archivo CSV:</label>
                                <input type="file" name="archivo_csv" accept=".csv" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fa fa-cogs"></i> Iniciar Migración</button>
                            <a href="index.php" class="btn btn-default">Cancelar</a>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/intranet/footer.php')) {
    include $_SERVER['DOCUMENT_ROOT'].'/intranet/footer.php';
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/footer.php')) {
    include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
}
?>
