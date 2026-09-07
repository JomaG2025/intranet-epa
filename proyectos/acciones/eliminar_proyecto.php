<?php
// /intranet/proyectos/acciones/eliminar_proyecto.php
ob_start();
$path_header = dirname(__DIR__) . '/header_local.php';
include_once $path_header;
require_once dirname(__DIR__) . '/config/helpers.php';

// Blindaje de Seguridad: Solo administradores pueden borrar
if ($rango_mostrar !== 'ADM') {
    die("Acceso denegado: No tiene privilegios para eliminar registros.");
}

$id_pry = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_pry > 0) {
    try {
        $pdo->beginTransaction();

        // Leer nombre antes de borrar para la auditoría
        $stmt_nom = $pdo->prepare("SELECT nombre FROM pry_proyectos WHERE id = ?");
        $stmt_nom->execute([$id_pry]);
        $nombre_pry = $stmt_nom->fetchColumn();

        $pdo->prepare("DELETE FROM pry_tareas WHERE id_proyecto = ?")->execute([$id_pry]);
        $pdo->prepare("DELETE FROM pry_proyectos WHERE id = ?")->execute([$id_pry]);

        $pdo->commit();
        registrar_auditoria($pdo, 'ELIMINAR', $id_pry, $nombre_pry,
            'Proyecto eliminado definitivamente.',
            $usuario_sesion);
        header("Location: ../index.php?msg=delete_ok");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error crítico al eliminar: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
}
?>