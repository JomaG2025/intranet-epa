<?php
ob_start();
include_once dirname(__DIR__) . '/header_local.php';
require_once dirname(__DIR__) . '/config/helpers.php';

if ($usuario_sesion === 'Invitado' || !in_array($rango_mostrar, ['ADM', 'GER', 'EP'])) {
    die("Acceso denegado.");
}

$id     = intval($_POST['id_proyecto']);
$padre  = isset($_POST['ruta_padre'])    ? $_POST['ruta_padre']    : '';
$nombre = isset($_POST['nombre_carpeta']) ? $_POST['nombre_carpeta'] : '';

// Sanitizar nombre de carpeta
$nombre = preg_replace('/[^a-zA-Z0-9 \-\_áéíóúüÁÉÍÓÚÜñÑ]/u', '', $nombre);
$nombre = trim($nombre);

// Sanitizar ruta padre
$padre = str_replace(['..', "\0", '\\'], '', $padre);
$padre = trim($padre, '/');
$padre = preg_replace('/[^a-zA-Z0-9 \-\_\.\/áéíóúüÁÉÍÓÚÜñÑ]/u', '', $padre);

if ($nombre === '' || $id <= 0) {
    header("Location: ../repositorio.php?id=$id&msg=error");
    exit();
}

$base_dir  = dirname(__DIR__) . '/repositorios/' . $id;
$dir_padre = $base_dir . ($padre !== '' ? '/' . $padre : '');

$real_base  = realpath($base_dir);
$real_padre = realpath($dir_padre);

if (!$real_base || !$real_padre || strpos($real_padre, $real_base) !== 0) {
    header("Location: ../repositorio.php?id=$id&msg=error");
    exit();
}

$nueva = $real_padre . '/' . $nombre;
if (!is_dir($nueva)) {
    mkdir($nueva, 0755);
}

$stmt_n = $pdo->prepare("SELECT nombre FROM pry_proyectos WHERE id = ?");
$stmt_n->execute([$id]);
$nom_pry = $stmt_n->fetchColumn();
$ubicacion = $padre !== '' ? ' en /' . $padre : ' en raíz';
registrar_auditoria($pdo, 'REPOSITORIO', $id, $nom_pry,
    'Carpeta creada: "' . $nombre . '"' . $ubicacion, $usuario_sesion);

$redir = $padre !== '' ? '&ruta=' . urlencode($padre) : '';
header("Location: ../repositorio.php?id=$id{$redir}&msg=carpeta_ok");
exit();
