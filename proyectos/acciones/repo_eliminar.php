<?php
ob_start();
include_once dirname(__DIR__) . '/header_local.php';
require_once dirname(__DIR__) . '/config/helpers.php';

if ($usuario_sesion === 'Invitado' || !in_array($rango_mostrar, ['ADM', 'GER'])) {
    die("Acceso denegado: Solo ADM y GER pueden eliminar archivos.");
}

$id     = intval($_POST['id_proyecto']);
$padre  = isset($_POST['ruta_padre']) ? $_POST['ruta_padre'] : '';
$nombre = isset($_POST['nombre'])     ? $_POST['nombre']     : '';

// Sanitizaciones
$padre  = str_replace(['..', "\0", '\\'], '', $padre);
$padre  = trim($padre, '/');
$nombre = preg_replace('/[^a-zA-Z0-9 \-\_\.áéíóúüÁÉÍÓÚÜñÑ]/u', '', $nombre);
$nombre = trim($nombre);

if ($nombre === '' || $id <= 0) {
    header("Location: ../repositorio.php?id=$id&msg=error");
    exit();
}

$base_dir      = dirname(__DIR__) . '/repositorios/' . $id;
$ruta_objetivo = $base_dir . ($padre !== '' ? '/' . $padre : '') . '/' . $nombre;
$real_base     = realpath($base_dir);
$real_objetivo = realpath($ruta_objetivo);

if (!$real_base || !$real_objetivo || strpos($real_objetivo, $real_base) !== 0) {
    header("Location: ../repositorio.php?id=$id&msg=error");
    exit();
}

$es_carpeta = is_dir($real_objetivo);
if ($es_carpeta) {
    eliminar_dir_recursivo($real_objetivo);
} elseif (is_file($real_objetivo)) {
    unlink($real_objetivo);
}

$stmt_n = $pdo->prepare("SELECT nombre FROM pry_proyectos WHERE id = ?");
$stmt_n->execute([$id]);
$nom_pry = $stmt_n->fetchColumn();
$tipo_elem = $es_carpeta ? 'Carpeta' : 'Archivo';
$ubicacion = $padre !== '' ? ' en /' . $padre : ' en raíz';
registrar_auditoria($pdo, 'REPOSITORIO', $id, $nom_pry,
    $tipo_elem . ' eliminado: "' . $nombre . '"' . $ubicacion, $usuario_sesion);

$redir = $padre !== '' ? '&ruta=' . urlencode($padre) : '';
header("Location: ../repositorio.php?id=$id{$redir}&msg=delete_ok");
exit();

function eliminar_dir_recursivo($dir) {
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        $ruta = $dir . '/' . $item;
        is_dir($ruta) ? eliminar_dir_recursivo($ruta) : unlink($ruta);
    }
    rmdir($dir);
}
