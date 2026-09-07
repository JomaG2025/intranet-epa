<?php
// /intranet/proyectos/editar_usuario.php
include_once 'header_local.php';

if ($rango_mostrar !== 'ADM') {
    header("Location: index.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) { header("Location: usuarios.php"); exit(); }

$stmt = $pdo->prepare("SELECT * FROM pry_usuario WHERE id = ? AND eliminado = 0");
$stmt->execute([$id]);
$usuario_actual = $stmt->fetch();
if (!$usuario_actual) { header("Location: usuarios.php"); exit(); }

$privilegios = $pdo->query("SELECT * FROM pry_privilegio ORDER BY id ASC")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_usuario  = trim($_POST['usuario']);
    $id_privilegio  = intval($_POST['id_privilegio']);
    $activo         = isset($_POST['activo']) ? 1 : 0;

    if ($nuevo_usuario === '' || $id_privilegio === 0) {
        $error = 'El nombre de usuario y el perfil son obligatorios.';
    } else {
        // Verificar duplicado (excluir el propio registro)
        $ck = $pdo->prepare("SELECT id FROM pry_usuario WHERE usuario = ? AND eliminado = 0 AND id != ?");
        $ck->execute([$nuevo_usuario, $id]);
        if ($ck->fetch()) {
            $error = 'Ya existe otro usuario con ese nombre.';
        } else {
            $pdo->prepare("UPDATE pry_usuario SET usuario = ?, id_privilegio = ?, activo = ?, modificado = NOW() WHERE id = ?")
                ->execute([$nuevo_usuario, $id_privilegio, $activo, $id]);
            header("Location: usuarios.php?msg=updated");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .page-header { border-bottom: 2px solid #eee; margin: 0 0 20px 0; padding-bottom: 10px; font-weight: 800; color: #333; }
        .panel-default { border-top: 3px solid #f0ad4e; }
        .perm-row { margin: 3px 0; font-size: 12px; color: #555; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">
        <h1 class="page-header"><i class="fa fa-user-circle text-warning"></i> Editar Usuario</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Modificar: <strong><?= htmlspecialchars($usuario_actual['usuario']) ?></strong>
                    </div>
                    <div class="panel-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Nombre de usuario (login)</label>
                                <input type="text" name="usuario" class="form-control"
                                       value="<?= htmlspecialchars(isset($_POST['usuario']) ? $_POST['usuario'] : $usuario_actual['usuario']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Perfil de acceso</label>
                                <select name="id_privilegio" id="sel_privilegio" class="form-control" required onchange="mostrarPermisos(this)">
                                    <option value="">Seleccione un perfil...</option>
                                    <?php foreach ($privilegios as $priv):
                                        $sel_val = isset($_POST['id_privilegio']) ? intval($_POST['id_privilegio']) : $usuario_actual['id_privilegio'];
                                    ?>
                                        <option value="<?= $priv['id'] ?>"
                                            data-crear="<?= $priv['p_crear'] ?>"
                                            data-editar="<?= $priv['p_editar'] ?>"
                                            data-aprobar="<?= $priv['p_aprobar'] ?>"
                                            data-costos="<?= $priv['p_costos'] ?>"
                                            data-documentos="<?= $priv['p_documentos'] ?>"
                                            data-reportes="<?= $priv['p_reportes'] ?>"
                                            data-global="<?= $priv['p_global'] ?>"
                                            <?= ($priv['id'] == $sel_val) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($priv['nombre']) ?> (<?= htmlspecialchars($priv['abrev']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="activo" <?= $usuario_actual['activo'] ? 'checked' : '' ?>> Cuenta activa
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Actualizar</button>
                            <a href="usuarios.php" class="btn btn-default">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-eye"></i> Permisos del perfil seleccionado</div>
                    <div class="panel-body" id="preview-permisos">
                        <p class="text-muted" style="font-size:12px;">Cargando...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once 'footer_local.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
var labels = {
    crear:      'Crear / Eliminar proyectos',
    editar:     'Editar tareas y proyectos',
    aprobar:    'Aprobar hitos',
    costos:     'Ver costos y presupuesto',
    documentos: 'Gestión documental',
    reportes:   'Generar reportes',
    global:     'Ver cartera global (toda la EPA)'
};
function mostrarPermisos(sel) {
    var opt = sel.options[sel.selectedIndex];
    if (!opt.value) {
        document.getElementById('preview-permisos').innerHTML = '<p class="text-muted" style="font-size:12px;">Seleccione un perfil para ver sus permisos.</p>';
        return;
    }
    var html = '<ul class="list-unstyled" style="margin:0;">';
    var keys = ['crear','editar','aprobar','costos','documentos','reportes','global'];
    for (var i = 0; i < keys.length; i++) {
        var k = keys[i];
        var val = opt.getAttribute('data-' + k);
        var icon = (val == '1') ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-muted"></i>';
        html += '<li class="perm-row">' + icon + ' ' + labels[k] + '</li>';
    }
    html += '</ul>';
    document.getElementById('preview-permisos').innerHTML = html;
}
// Auto-render al cargar
window.onload = function() {
    mostrarPermisos(document.getElementById('sel_privilegio'));
};
</script>
</body>
</html>
