<?php
// /intranet/proyectos/repositorio.php
include_once 'header_local.php';
require_once 'config/helpers.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = null;
$proyecto = null;
$items = [];
$breadcrumb = [];
$ruta_rel = '';
$puede_escribir = in_array($rango_mostrar, ['ADM', 'GER', 'EP']);
$puede_eliminar = in_array($rango_mostrar, ['ADM', 'GER']);

if ($id <= 0) {
    $error = "ID de proyecto no válido.";
} else {
    $stmt = $pdo->prepare("SELECT nombre FROM pry_proyectos WHERE id = ?");
    $stmt->execute([$id]);
    $proyecto = $stmt->fetch();
    if (!$proyecto) {
        $error = "Proyecto no encontrado.";
    }
}

if (!$error) {
    // Ruta relativa (navegación dentro del repositorio)
    $ruta_rel = isset($_GET['ruta']) ? $_GET['ruta'] : '';
    $ruta_rel = str_replace(['..', "\0", '\\'], '', $ruta_rel);
    $ruta_rel = trim($ruta_rel, '/');
    $ruta_rel = preg_replace('/[^a-zA-Z0-9 \-\_\.\/áéíóúüÁÉÍÓÚÜñÑ]/u', '', $ruta_rel);

    $base_dir   = __DIR__ . '/repositorios/' . $id;
    $dir_actual = $base_dir . ($ruta_rel !== '' ? '/' . $ruta_rel : '');

    // Intentar crear directorio base si no existe
    if (!is_dir($base_dir)) {
        if (!@mkdir($base_dir, 0755, true)) {
            $error = "No se pudo crear el directorio del repositorio. El administrador del servidor debe crear la carpeta <code>proyectos/repositorios/</code> con permisos de escritura para el usuario web.";
        }
    }

    if (!$error) {
        $real_base   = realpath($base_dir);
        $real_actual = realpath($dir_actual);

        if (!$real_base || !$real_actual || strpos($real_actual, $real_base) !== 0) {
            $error = "Ruta no válida o sin acceso.";
        } else {
            // Listar contenido
            foreach (scandir($dir_actual) as $e) {
                if ($e === '.' || $e === '..') continue;
                $fp = $dir_actual . '/' . $e;
                $items[] = [
                    'nombre' => $e,
                    'es_dir' => is_dir($fp),
                    'tamaño' => is_file($fp) ? filesize($fp) : null,
                    'fecha'  => date('d/m/Y H:i', filemtime($fp)),
                ];
            }
            usort($items, function($a, $b) {
                if ($a['es_dir'] !== $b['es_dir']) return $b['es_dir'] - $a['es_dir'];
                return strnatcasecmp($a['nombre'], $b['nombre']);
            });

            // Breadcrumb
            if ($ruta_rel !== '') {
                $partes = explode('/', $ruta_rel);
                $acum = '';
                foreach ($partes as $p) {
                    $acum = $acum !== '' ? "$acum/$p" : $p;
                    $breadcrumb[] = ['nombre' => $p, 'ruta' => $acum];
                }
            }
        }
    }
}

function fmt_bytes($b) {
    if (!$b) return '—';
    if ($b < 1024) return $b . ' B';
    if ($b < 1048576) return round($b / 1024, 1) . ' KB';
    return round($b / 1048576, 1) . ' MB';
}

function tipo_preview($nombre) {
    $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','gif'])) return 'imagen';
    if ($ext === 'pdf') return 'pdf';
    if (in_array($ext, ['txt','csv']))              return 'texto';
    return 'otro';
}

function icono_ext($nombre) {
    $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
    $m = [
        'pdf'  => ['fa-file-pdf-o',       '#c0392b'],
        'doc'  => ['fa-file-word-o',       '#2980b9'],
        'docx' => ['fa-file-word-o',       '#2980b9'],
        'xls'  => ['fa-file-excel-o',      '#27ae60'],
        'xlsx' => ['fa-file-excel-o',      '#27ae60'],
        'ppt'  => ['fa-file-powerpoint-o', '#e67e22'],
        'pptx' => ['fa-file-powerpoint-o', '#e67e22'],
        'jpg'  => ['fa-file-image-o',      '#8e44ad'],
        'jpeg' => ['fa-file-image-o',      '#8e44ad'],
        'png'  => ['fa-file-image-o',      '#8e44ad'],
        'gif'  => ['fa-file-image-o',      '#8e44ad'],
        'zip'  => ['fa-file-archive-o',    '#7f8c8d'],
        'rar'  => ['fa-file-archive-o',    '#7f8c8d'],
        'txt'  => ['fa-file-text-o',       '#95a5a6'],
        'csv'  => ['fa-file-text-o',       '#27ae60'],
    ];
    return isset($m[$ext]) ? $m[$ext] : ['fa-file-o', '#95a5a6'];
}

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Repositorio<?= $proyecto ? ' — ' . htmlspecialchars($proyecto['nombre']) : '' ?></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body { background:#f8f8f8; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif; }
    .page-header { border-bottom:2px solid #eee; margin-bottom:5px; font-weight:800; color:#0d2244; }
    .breadcrumb { background:#fff; border:1px solid #ddd; border-radius:4px; padding:8px 14px; margin-bottom:12px; font-size:13px; }
    .breadcrumb > li + li:before { content:"/ "; color:#bbb; }
    .item-nombre a { color:#0d2244; }
    .item-nombre a:hover { text-decoration:underline; }
    .tabla-repo td { vertical-align:middle !important; }
</style>
</head>
<body>
<div id="wrapper">
<div id="page-wrapper">

<h1 class="page-header"><i class="fa fa-folder-open" style="color:#f39c12;"></i> Repositorio</h1>

<?php if ($proyecto): ?>
<p style="margin-bottom:15px;">
    <a href="ficha.php?id=<?= $id ?>" class="text-muted" style="font-size:13px;">
        <i class="fa fa-arrow-left"></i> <?= htmlspecialchars($proyecto['nombre']) ?>
    </a>
</p>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-triangle"></i> <?= $error ?>
    </div>
<?php else: ?>

<?php if ($msg === 'carpeta_ok'): ?>
    <div class="alert alert-success alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>Carpeta creada correctamente.</div>
<?php elseif ($msg === 'upload_ok'): ?>
    <div class="alert alert-success alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>Archivo subido correctamente.</div>
<?php elseif ($msg === 'delete_ok'): ?>
    <div class="alert alert-warning alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>Elemento eliminado.</div>
<?php elseif ($msg === 'error'): ?>
    <div class="alert alert-danger alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>Ocurrió un error. Verifique los datos e intente nuevamente.</div>
<?php elseif ($msg === 'error_tipo'): ?>
    <div class="alert alert-danger alert-dismissible"><button class="close" data-dismiss="alert">&times;</button>Tipo de archivo no permitido.</div>
<?php endif; ?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="repositorio.php?id=<?= $id ?>"><i class="fa fa-home"></i> Raíz</a></li>
    <?php foreach ($breadcrumb as $bc): ?>
        <li><a href="repositorio.php?id=<?= $id ?>&ruta=<?= urlencode($bc['ruta']) ?>"><?= htmlspecialchars($bc['nombre']) ?></a></li>
    <?php endforeach; ?>
</ol>

<!-- Botones de acción -->
<?php if ($puede_escribir): ?>
<div style="margin-bottom:12px;">
    <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#modalCarpeta">
        <i class="fa fa-folder" style="color:#f39c12;"></i> Nueva Carpeta
    </button>
    <button class="btn btn-sm btn-primary" style="margin-left:5px;" data-toggle="modal" data-target="#modalSubir">
        <i class="fa fa-upload"></i> Subir Archivo
    </button>
</div>
<?php endif; ?>

<!-- Tabla -->
<div class="panel panel-default">
    <div class="panel-body" style="padding:0;">
        <table class="table table-bordered tabla-repo" style="font-size:13px; margin-bottom:0;">
            <thead>
                <tr style="background:#0d2244; color:white;">
                    <th style="width:36px;"></th>
                    <th>Nombre</th>
                    <th style="width:90px;" class="text-right">Tamaño</th>
                    <th style="width:135px;">Modificado</th>
                    <th style="width:<?= $puede_eliminar ? '110' : '80' ?>px;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="<?= $puede_eliminar ? 5 : 4 ?>" class="text-center text-muted" style="padding:35px;">
                        <i class="fa fa-folder-open fa-2x" style="color:#ddd;"></i><br><br>
                        Esta carpeta está vacía.
                        <?php if ($puede_escribir): ?><br><small>Use los botones de arriba para agregar contenido.</small><?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php foreach ($items as $item):
                $sub_ruta = $ruta_rel !== '' ? $ruta_rel . '/' . $item['nombre'] : $item['nombre'];
            ?>
                <tr>
                <?php if ($item['es_dir']): ?>
                    <td class="text-center"><i class="fa fa-folder fa-lg" style="color:#f39c12;"></i></td>
                    <td class="item-nombre">
                        <a href="repositorio.php?id=<?= $id ?>&ruta=<?= urlencode($sub_ruta) ?>">
                            <strong><?= htmlspecialchars($item['nombre']) ?></strong>
                        </a>
                    </td>
                    <td class="text-right text-muted">—</td>
                    <td class="text-muted"><small><?= $item['fecha'] ?></small></td>
                    <td class="text-center">
                        <?php if ($puede_eliminar): ?>
                        <form method="POST" action="acciones/repo_eliminar.php"
                              onsubmit="return confirm('¿Eliminar la carpeta y todo su contenido?');"
                              style="display:inline;">
                            <input type="hidden" name="id_proyecto" value="<?= $id ?>">
                            <input type="hidden" name="ruta_padre"  value="<?= htmlspecialchars($ruta_rel) ?>">
                            <input type="hidden" name="nombre"      value="<?= htmlspecialchars($item['nombre']) ?>">
                            <button type="submit" class="btn btn-danger btn-xs" title="Eliminar"><i class="fa fa-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                <?php else:
                    list($icono, $color) = icono_ext($item['nombre']);
                    $tipo_prev  = tipo_preview($item['nombre']);
                    $url_prev   = 'acciones/repo_descargar.php?id=' . $id . '&ruta=' . urlencode($sub_ruta);
                    $url_dl     = $url_prev . '&forzar=1';
                    $nom_js     = addslashes(htmlspecialchars($item['nombre']));
                ?>
                    <td class="text-center"><i class="fa <?= $icono ?> fa-lg" style="color:<?= $color ?>;"></i></td>
                    <td class="item-nombre">
                        <a href="#" onclick="verPreview('<?= $nom_js ?>','<?= $url_prev ?>','<?= $url_dl ?>','<?= $tipo_prev ?>'); return false;">
                            <?= htmlspecialchars($item['nombre']) ?>
                        </a>
                    </td>
                    <td class="text-right"><small><?= fmt_bytes($item['tamaño']) ?></small></td>
                    <td class="text-muted"><small><?= $item['fecha'] ?></small></td>
                    <td class="text-center">
                        <div class="btn-group btn-group-xs">
                            <a href="#" class="btn btn-default btn-xs"
                               onclick="verPreview('<?= $nom_js ?>','<?= $url_prev ?>','<?= $url_dl ?>','<?= $tipo_prev ?>'); return false;"
                               title="Vista previa"><i class="fa fa-eye"></i></a>
                            <a href="<?= $url_dl ?>" class="btn btn-info btn-xs" title="Descargar"><i class="fa fa-download"></i></a>
                            <?php if ($puede_eliminar): ?>
                            <form method="POST" action="acciones/repo_eliminar.php"
                                  onsubmit="return confirm('¿Eliminar este archivo?');" style="display:inline;">
                                <input type="hidden" name="id_proyecto" value="<?= $id ?>">
                                <input type="hidden" name="ruta_padre"  value="<?= htmlspecialchars($ruta_rel) ?>">
                                <input type="hidden" name="nombre"      value="<?= htmlspecialchars($item['nombre']) ?>">
                                <button type="submit" class="btn btn-danger btn-xs" title="Eliminar"><i class="fa fa-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($puede_escribir): ?>
<!-- Modal: Nueva Carpeta -->
<div class="modal fade" id="modalCarpeta" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="acciones/repo_crear_carpeta.php">
                <input type="hidden" name="id_proyecto" value="<?= $id ?>">
                <input type="hidden" name="ruta_padre"  value="<?= htmlspecialchars($ruta_rel) ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-folder" style="color:#f39c12;"></i> Nueva Carpeta</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Nombre</label>
                        <input type="text" name="nombre_carpeta" class="form-control" autofocus required
                               placeholder="Ej: Contratos 2026">
                        <p class="help-block" style="margin-top:4px;">Solo letras, números, espacios y guiones.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Subir Archivo -->
<div class="modal fade" id="modalSubir" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="acciones/repo_subir_archivo.php" enctype="multipart/form-data">
                <input type="hidden" name="id_proyecto" value="<?= $id ?>">
                <input type="hidden" name="ruta_padre"  value="<?= htmlspecialchars($ruta_rel) ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-upload"></i> Subir Archivo</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Seleccionar archivo</label>
                        <input type="file" name="archivo" required>
                        <p class="help-block" style="margin-top:6px;">
                            Formatos permitidos: PDF, Word, Excel, PowerPoint, imágenes (JPG/PNG/GIF), ZIP, RAR, TXT, CSV.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-upload"></i> Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endif; // fin !$error ?>

<!-- Modal: Vista Previa -->
<div class="modal fade" id="modalPreview" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#0d2244; color:white; padding:10px 15px;">
                <button type="button" class="close" data-dismiss="modal" style="color:white; opacity:1;">&times;</button>
                <h4 class="modal-title" id="preview-titulo" style="font-size:14px;"><i class="fa fa-file-o"></i> —</h4>
            </div>
            <div class="modal-body" id="preview-cuerpo" style="padding:0; min-height:200px;">
                <div class="text-center" style="padding:40px;"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>
            </div>
            <div class="modal-footer" style="padding:8px 15px;">
                <a href="#" id="preview-descargar" class="btn btn-primary btn-sm">
                    <i class="fa fa-download"></i> Descargar
                </a>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

</div><!-- /page-wrapper -->
</div><!-- /wrapper -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
function verPreview(nombre, urlPreview, urlDescarga, tipo) {
    document.getElementById('preview-titulo').innerHTML = '<i class="fa fa-file-o"></i> ' + nombre;
    document.getElementById('preview-descargar').href = urlDescarga;

    var cuerpo = document.getElementById('preview-cuerpo');
    cuerpo.innerHTML = '<div class="text-center" style="padding:40px;"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>';

    if (tipo === 'imagen') {
        var img = new Image();
        img.onload = function() {
            cuerpo.innerHTML = '<div style="text-align:center; padding:10px; background:#f5f5f5;"><img src="' + urlPreview + '" style="max-width:100%; max-height:70vh; box-shadow:0 2px 8px rgba(0,0,0,.2);"></div>';
        };
        img.src = urlPreview;
    } else if (tipo === 'pdf' || tipo === 'texto') {
        cuerpo.innerHTML = '<iframe src="' + urlPreview + '" style="width:100%; height:70vh; border:none; display:block;"></iframe>';
    } else {
        cuerpo.innerHTML = '<div class="text-center" style="padding:50px 20px;">'
            + '<i class="fa fa-file-o fa-5x" style="color:#ccc;"></i><br><br>'
            + '<p class="text-muted">Vista previa no disponible para este tipo de archivo.</p>'
            + '</div>';
    }
    $('#modalPreview').modal('show');
}

// Limpiar iframe al cerrar para detener carga
$('#modalPreview').on('hidden.bs.modal', function() {
    document.getElementById('preview-cuerpo').innerHTML = '';
});
</script>
</body>
</html>
