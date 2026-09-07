<?php
ob_start();
include_once dirname(__DIR__) . '/header_local.php';
require_once dirname(__DIR__) . '/config/helpers.php';

if ($usuario_sesion === 'Invitado' || !in_array($rango_mostrar, ['ADM', 'GER', 'EP'])) {
    die("Acceso denegado.");
}

$id    = intval($_POST['id_proyecto']);
$padre = isset($_POST['ruta_padre']) ? $_POST['ruta_padre'] : '';

// Sanitizar ruta padre
$padre = str_replace(['..', "\0", '\\'], '', $padre);
$padre = trim($padre, '/');
$padre = preg_replace('/[^a-zA-Z0-9 \-\_\.\/áéíóúüÁÉÍÓÚÜñÑ]/u', '', $padre);

$ext_permitidas = ['pdf','doc','docx','xls','xlsx','ppt','pptx','jpg','jpeg','png','gif','zip','rar','txt','csv'];

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    header("Location: ../repositorio.php?id=$id" . ($padre !== '' ? '&ruta=' . urlencode($padre) : '') . "&msg=error");
    exit();
}

$archivo = $_FILES['archivo'];
$ext     = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $ext_permitidas)) {
    header("Location: ../repositorio.php?id=$id" . ($padre !== '' ? '&ruta=' . urlencode($padre) : '') . "&msg=error_tipo");
    exit();
}

// Sanitizar nombre
$nombre_base  = pathinfo($archivo['name'], PATHINFO_FILENAME);
$nombre_limpio = preg_replace('/[^a-zA-Z0-9 \-\_áéíóúüÁÉÍÓÚÜñÑ]/u', '', $nombre_base);
$nombre_limpio = trim($nombre_limpio);
if ($nombre_limpio === '') $nombre_limpio = 'archivo_' . time();
$nombre_final = $nombre_limpio . '.' . $ext;

$base_dir    = dirname(__DIR__) . '/repositorios/' . $id;
$dir_destino = $base_dir . ($padre !== '' ? '/' . $padre : '');

$real_base    = realpath($base_dir);
$real_destino = realpath($dir_destino);

if (!$real_base || !$real_destino || strpos($real_destino, $real_base) !== 0) {
    header("Location: ../repositorio.php?id=$id&msg=error");
    exit();
}

// Evitar sobreescribir archivos existentes
$ruta_final = $real_destino . '/' . $nombre_final;
if (file_exists($ruta_final)) {
    $nombre_final = $nombre_limpio . '_' . time() . '.' . $ext;
    $ruta_final   = $real_destino . '/' . $nombre_final;
}

move_uploaded_file($archivo['tmp_name'], $ruta_final);

$stmt_n = $pdo->prepare("SELECT nombre FROM pry_proyectos WHERE id = ?");
$stmt_n->execute([$id]);
$nom_pry = $stmt_n->fetchColumn();
$ubicacion = $padre !== '' ? ' en /' . $padre : ' en raíz';
registrar_auditoria($pdo, 'REPOSITORIO', $id, $nom_pry,
    'Archivo subido: "' . $nombre_final . '"' . $ubicacion, $usuario_sesion);

header("Location: ../repositorio.php?id=$id" . ($padre !== '' ? '&ruta=' . urlencode($padre) : '') . "&msg=upload_ok");
exit();
