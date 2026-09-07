<?php
// /intranet/proyectos/editar_privilegio.php
include_once 'header_local.php';

if ($rango_mostrar !== 'ADM') { die("Acceso restringido."); }
$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM pry_privilegio WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) die("Perfil no encontrado.");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configurar Perfil - EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .page-header { border-bottom: 2px solid #eee; margin-bottom: 25px; font-weight: 800; }
        .well { background: white; border-top: 3px solid #337ab7; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .perm-group-title { color: #337ab7; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">
        <h2 class="page-header"><i class="fa fa-cog"></i> Configurar Perfil: <?= htmlspecialchars($p['nombre']) ?></h2>
        <div class="well">
            <form action="acciones/editar_privilegio.php" method="POST">
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="form-group">
                    <label>Nombre del Perfil Institucional</label>
                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($p['nombre']) ?>" required>
                </div>
                <hr>
                <div class="perm-group-title">MATRIZ DE PRIVILEGIOS</div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="checkbox"><label><input type="checkbox" name="p_crear" <?= $p['p_crear'] ? 'checked' : '' ?>> Crear / Eliminar</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="p_editar" <?= $p['p_editar'] ? 'checked' : '' ?>> Editar Tareas</label></div>
                    </div>
                    <div class="col-md-4">
                        <div class="checkbox"><label><input type="checkbox" name="p_aprobar" <?= $p['p_aprobar'] ? 'checked' : '' ?>> Aprobar Hitos</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="p_costos" <?= $p['p_costos'] ? 'checked' : '' ?>> Ver Costos y Curva S</label></div>
                    </div>
                    <div class="col-md-4">
                        <div class="checkbox"><label><input type="checkbox" name="p_documentos" <?= $p['p_documentos'] ? 'checked' : '' ?>> Gestión Documental</label></div>
                        <div class="checkbox"><label><input type="checkbox" name="p_reportes" <?= $p['p_reportes'] ? 'checked' : '' ?>> Generar Reportes</label></div>
                    </div>
                </div>
                <div class="row"><div class="col-md-12"><div class="checkbox"><label><input type="checkbox" name="p_global" <?= $p['p_global'] ? 'checked' : '' ?>> Ver Cartera Global (Toda la EPA)</label></div></div></div>
                <hr>
                <div class="text-right">
                    <a href="privilegios.php" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="font-weight: bold;"><i class="fa fa-save"></i> Actualizar Privilegios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once 'footer_local.php'; ?>
</body>
</html>