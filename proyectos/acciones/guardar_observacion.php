<?php
// /intranet/proyectos/acciones/guardar_observacion.php
$path_header = dirname(__DIR__) . '/header_local.php';
include_once $path_header;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die("Acceso no permitido."); }

$id_proyecto = intval($_POST['id_proyecto']);
if ($id_proyecto <= 0) { die("Proyecto no válido."); }

try {
    $pdo->prepare("INSERT INTO pry_observaciones
                    (id_proyecto, tipo, descripcion, criticidad, responsable,
                     fecha_levantamiento, fecha_compromiso, estado, usuario_registro)
                   VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente', ?)")
        ->execute([
            $id_proyecto,
            $_POST['tipo'],
            $_POST['descripcion'],
            $_POST['criticidad'],
            $_POST['responsable']     ?: null,
            $_POST['fecha_levantamiento'],
            $_POST['fecha_compromiso'] ?: null,
            $usuario_sesion,
        ]);

    header("Location: ../ficha.php?id=$id_proyecto&obs=ok");
    exit();

} catch (PDOException $e) {
    die("Error al guardar observación: " . $e->getMessage());
}
?>
