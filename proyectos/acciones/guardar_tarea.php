<?php
// /intranet/proyectos/acciones/guardar_tarea.php
$path_header = dirname(__DIR__) . '/header_local.php';
include_once $path_header;

if ($usuario_sesion === 'Invitado') { die("Acceso denegado."); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $id_proyecto = $_POST['id_proyecto'];
        
        $sql = "INSERT INTO pry_tareas (
                    id_proyecto, nombre, fecha_inicio, fecha_fin, 
                    progreso_real, dependencia, es_hito, responsable, costo_estimado
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $id_proyecto,
            $_POST['nombre'],
            $_POST['fecha_inicio'],
            $_POST['fecha_fin'],
            $_POST['progreso'] ?: 0,
            $_POST['dependencia'] ?: null,
            isset($_POST['es_hito']) ? 1 : 0,
            $_POST['responsable'],
            $_POST['costo_estimado'] ?: 0 // Nuevo campo financiero
        ]);

        header("Location: ../ficha.php?id=$id_proyecto&tarea=ok");
        exit();
    } catch (PDOException $e) {
        die("Error al guardar tarea: " . $e->getMessage());
    }
}
?>