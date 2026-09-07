<?php
// /intranet/proyectos/acciones/cambiar_estado_obs.php
// Cambia el estado de una observación (Pendiente → En proceso → Resuelto)
$path_header = dirname(__DIR__) . '/header_local.php';
include_once $path_header;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die("Acceso no permitido."); }

$id_obs     = intval($_POST['id_obs']);
$id_proyecto = intval($_POST['id_proyecto']);
$nuevo_estado = $_POST['nuevo_estado'];
$resolucion   = isset($_POST['resolucion']) ? trim($_POST['resolucion']) : null;

$estados_validos = ['Pendiente', 'En proceso', 'Resuelto'];
if (!in_array($nuevo_estado, $estados_validos)) { die("Estado no válido."); }

try {
    $pdo->prepare("UPDATE pry_observaciones SET estado = ?, resolucion = ? WHERE id = ?")
        ->execute([$nuevo_estado, $resolucion ?: null, $id_obs]);

    header("Location: ../ficha.php?id=$id_proyecto&obs=updated");
    exit();

} catch (PDOException $e) {
    die("Error al actualizar observación: " . $e->getMessage());
}
?>
