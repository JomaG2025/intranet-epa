<?php
// /intranet/proyectos/acciones/guardar_presup_mod.php
$path_header = dirname(__DIR__) . '/header_local.php';
include_once $path_header;
require_once dirname(__DIR__) . '/config/conexion.php';
require_once dirname(__DIR__) . '/config/helpers.php';

if (!isset($_SESSION['permisos']['p_editar']) || !$_SESSION['permisos']['p_editar']) {
    die("Sin permisos.");
}

$id_pry = isset($_POST['id_proyecto']) ? intval($_POST['id_proyecto']) : 0;
$monto  = isset($_POST['monto'])       ? (float)$_POST['monto']       : null;
$fecha  = (isset($_POST['fecha']) && $_POST['fecha'] !== '') ? $_POST['fecha'] : null;
$desc   = isset($_POST['descripcion']) ? trim($_POST['descripcion'])   : null;

if ($id_pry <= 0 || $monto === null) die("Datos incompletos.");

$pdo->prepare("INSERT INTO pry_presupuesto_mod (id_proyecto, descripcion, monto, fecha, usuario) VALUES (?, ?, ?, ?, ?)")
    ->execute([$id_pry, $desc ?: null, $monto, $fecha, $usuario_sesion]);

$stmt_n = $pdo->prepare("SELECT nombre FROM pry_proyectos WHERE id = ?");
$stmt_n->execute([$id_pry]);
$nombre = $stmt_n->fetchColumn();
registrar_auditoria($pdo, 'MOD_PRESUPUESTO', $id_pry, $nombre,
    'Modificación presupuestaria: M$' . $monto . (($desc) ? ' — ' . $desc : ''), $usuario_sesion);

header("Location: ../ficha.php?id=$id_pry");
exit();
?>
