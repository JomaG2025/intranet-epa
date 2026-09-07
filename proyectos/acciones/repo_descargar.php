<?php
ob_start(); // captura el HTML del sidebar para no corromper el archivo
include_once dirname(__DIR__) . '/header_local.php';

if ($usuario_sesion === 'Invitado') {
    ob_end_clean();
    die("Acceso denegado.");
}

$id   = isset($_GET['id'])   ? intval($_GET['id'])   : 0;
$ruta = isset($_GET['ruta']) ? $_GET['ruta']         : '';

$ruta = str_replace(['..', "\0", '\\'], '', $ruta);
$ruta = trim($ruta, '/');
$ruta = preg_replace('/[^a-zA-Z0-9 \-\_\.\/áéíóúüÁÉÍÓÚÜñÑ]/u', '', $ruta);

$base_dir    = dirname(__DIR__) . '/repositorios/' . $id;
$real_base   = realpath($base_dir);
$real_objeto = realpath($base_dir . '/' . $ruta);

if (!$real_base || !$real_objeto || strpos($real_objeto, $real_base) !== 0 || !is_file($real_objeto)) {
    die("Archivo no encontrado.");
}

$ext  = strtolower(pathinfo($real_objeto, PATHINFO_EXTENSION));
$mime_map = [
    'pdf'  => 'application/pdf',
    'doc'  => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls'  => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'ppt'  => 'application/vnd.ms-powerpoint',
    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'zip'  => 'application/zip',
    'rar'  => 'application/x-rar-compressed',
    'txt'  => 'text/plain',
    'csv'  => 'text/csv',
];
$mime = isset($mime_map[$ext]) ? $mime_map[$ext] : 'application/octet-stream';

ob_end_clean(); // descarta el HTML del sidebar antes de enviar el archivo

$forzar = isset($_GET['forzar']) && $_GET['forzar'] == '1';
header('Content-Type: '        . $mime);
header('Content-Disposition: ' . ($forzar ? 'attachment' : 'inline') . '; filename="' . basename($real_objeto) . '"');
header('Content-Length: '      . filesize($real_objeto));
readfile($real_objeto);
exit();
