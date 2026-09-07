<?php
// /intranet/proyectos/editar_tarea.php
include_once 'header_local.php'; 
require_once 'config/conexion.php';

$id_tarea = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM pry_tareas WHERE id = ? AND eliminado = 0");
$stmt->execute([$id_tarea]);
$tarea = $stmt->fetch();
if (!$tarea) { die("Tarea no encontrada."); }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Actividad - EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        #sidebar-simulado { width: 250px; position: fixed; height: 100%; background: #0d2244; left: 0; top: 0; }
        #page-wrapper { margin-left: 250px; padding: 25px; min-height: 100vh; }
        .user-panel img { width: 60px !important; height: 60px !important; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">
        <h2 class="page-header">Editar Actividad Financiera</h2>
        <form action="acciones/editar_tarea.php" method="POST">
            <input type="hidden" name="id" value="<?= $id_tarea ?>">
            <input type="hidden" name="id_proyecto" value="<?= $tarea['id_proyecto'] ?>">
            
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($tarea['nombre']) ?>" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label>Inicio</label><input type="date" name="fecha_inicio" class="form-control" value="<?= $tarea['fecha_inicio'] ?>" required></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label>Fin</label><input type="date" name="fecha_fin" class="form-control" value="<?= $tarea['fecha_fin'] ?>" required></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Inversión Estimada ($)</label>
                        <input type="number" name="costo_estimado" class="form-control" value="<?= $tarea['costo_estimado'] ?>" step="0.01">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label>% Avance</label><input type="number" name="progreso" class="form-control" value="<?= $tarea['progreso_real'] ?>"></div>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-warning">Guardar Cambios Financieros</button>
            <a href="ficha.php?id=<?= $tarea['id_proyecto'] ?>" class="btn btn-default">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
