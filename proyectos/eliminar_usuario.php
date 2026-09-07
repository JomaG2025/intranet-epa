<?php
// /intranet/proyectos/eliminar_usuario.php
include_once 'header_local.php';

if ($rango_mostrar !== 'ADM') {
    header("Location: index.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Soft delete
    $pdo->prepare("UPDATE pry_usuario SET eliminado = 1, activo = 0 WHERE id = ?")
        ->execute([$id]);
}

header("Location: usuarios.php?msg=deleted");
exit();
