<?php
// /intranet/proyectos/acciones/editar_privilegio.php
require_once '../header_local.php';

// Seguridad: Solo el administrador puede procesar cambios de privilegios
if ($rango_mostrar !== 'ADM') { die("Sin permisos."); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    // Los checkboxes solo se envían si están marcados, por eso usamos isset()
    $p_crear      = isset($_POST['p_crear']) ? 1 : 0;
    $p_editar     = isset($_POST['p_editar']) ? 1 : 0;
    $p_aprobar    = isset($_POST['p_aprobar']) ? 1 : 0;
    $p_costos     = isset($_POST['p_costos']) ? 1 : 0;
    $p_documentos = isset($_POST['p_documentos']) ? 1 : 0; // NUEVO: Captura el permiso documental
    $p_reportes   = isset($_POST['p_reportes']) ? 1 : 0;
    $p_global     = isset($_POST['p_global']) ? 1 : 0;

    // Actualizamos la tabla incluyendo la nueva columna p_documentos
    $sql = "UPDATE pry_privilegio SET 
                nombre = ?, 
                p_crear = ?, 
                p_editar = ?, 
                p_aprobar = ?, 
                p_costos = ?, 
                p_documentos = ?, 
                p_reportes = ?, 
                p_global = ?
            WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nombre'], 
        $p_crear, 
        $p_editar, 
        $p_aprobar, 
        $p_costos, 
        $p_documentos, // NUEVO: Parámetro para la base de datos
        $p_reportes, 
        $p_global, 
        $id
    ]);

    // Redirección con éxito
    header("Location: ../privilegios.php?edit=ok");
    exit();
}