<?php
// /intranet/proyectos/acciones/aprobar_gds.php
$path_header = dirname(__DIR__) . '/header_local.php';
include_once $path_header;
require_once dirname(__DIR__) . '/config/conexion.php';
require_once dirname(__DIR__) . '/config/helpers.php';

if ($rango_mostrar !== 'ADM' && $rango_mostrar !== 'GER') {
    die("Sin permisos para esta operación.");
}

$id     = isset($_POST['id'])     ? intval($_POST['id'])   : 0;
$accion = isset($_POST['accion']) ? trim($_POST['accion']) : '';

if ($id <= 0) die("ID de proyecto inválido.");

$stmt = $pdo->prepare("SELECT nombre, aprobacion_gds FROM pry_proyectos WHERE id = ?");
$stmt->execute([$id]);
$pry = $stmt->fetch();
if (!$pry) die("Proyecto no encontrado.");

if ($accion === 'aprobar') {
    $pdo->prepare("UPDATE pry_proyectos SET aprobacion_gds = 1, aprobacion_gds_fecha = CURDATE(), aprobacion_gds_usuario = ? WHERE id = ?")
        ->execute([$usuario_sesion, $id]);
    registrar_auditoria($pdo, 'APROBAR_GDS', $id, $pry['nombre'], 'Aprobación financiera GDS otorgada.', $usuario_sesion);

} elseif ($accion === 'revocar' && $rango_mostrar === 'ADM') {
    $pdo->prepare("UPDATE pry_proyectos SET aprobacion_gds = 0, aprobacion_gds_fecha = NULL, aprobacion_gds_usuario = NULL WHERE id = ?")
        ->execute([$id]);
    registrar_auditoria($pdo, 'REVOCAR_GDS', $id, $pry['nombre'], 'Aprobación financiera GDS revocada.', $usuario_sesion);
}

header("Location: ../editar.php?id=$id");
exit();
?>
