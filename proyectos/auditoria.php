<?php
// /intranet/proyectos/auditoria.php
include_once 'header_local.php';

if ($rango_mostrar !== 'ADM') {
    die('<div class="container" style="margin-top:50px;"><div class="alert alert-danger"><i class="fa fa-lock"></i> Acceso restringido a administradores.</div></div>');
}

// ── FILTROS ──────────────────────────────────────────────────────────────────
$f_usuario = isset($_GET['usuario']) ? trim($_GET['usuario']) : '';
$f_accion  = isset($_GET['accion'])  ? trim($_GET['accion'])  : '';
$f_desde   = isset($_GET['desde'])   ? trim($_GET['desde'])   : '';
$f_hasta   = isset($_GET['hasta'])   ? trim($_GET['hasta'])   : '';

$where  = "WHERE 1=1";
$params = [];

if ($f_usuario) { $where .= " AND usuario LIKE ?"; $params[] = "%$f_usuario%"; }
if ($f_accion)  { $where .= " AND accion = ?";     $params[] = $f_accion; }
if ($f_desde)   { $where .= " AND fecha >= ?";     $params[] = $f_desde . ' 00:00:00'; }
if ($f_hasta)   { $where .= " AND fecha <= ?";     $params[] = $f_hasta . ' 23:59:59'; }

$sql = "SELECT * FROM pry_auditoria $where ORDER BY fecha DESC LIMIT 500";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll();

// Lista de usuarios para el select
$usuarios = $pdo->query("SELECT DISTINCT usuario FROM pry_auditoria ORDER BY usuario")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bitácora de Auditoría - EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .page-header { border-bottom: 2px solid #eee; margin: 0 0 20px 0; padding-bottom: 10px; font-weight: 800; color: #333; }
        .filtros-bar { background: white; border: 1px solid #ddd; border-radius: 4px; padding: 12px 15px; margin-bottom: 15px; }
        .badge-crear        { background-color: #5cb85c; }
        .badge-editar       { background-color: #f0ad4e; color: #333; }
        .badge-eliminar     { background-color: #d9534f; }
        .badge-repositorio  { background-color: #5bc0de; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">

        <h1 class="page-header"><i class="fa fa-history text-primary"></i> Bitácora de Auditoría</h1>

        <!-- ── FILTROS ── -->
        <div class="filtros-bar">
            <form method="GET" action="auditoria.php">
                <div class="row">
                    <div class="col-sm-3">
                        <select name="usuario" class="form-control input-sm">
                            <option value="">Todos los usuarios</option>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?= htmlspecialchars($u) ?>" <?= $f_usuario == $u ? 'selected' : '' ?>><?= htmlspecialchars($u) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="accion" class="form-control input-sm">
                            <option value="">Todas las acciones</option>
                            <?php foreach (['CREAR', 'EDITAR', 'ELIMINAR', 'REPOSITORIO'] as $a): ?>
                                <option value="<?= $a ?>" <?= $f_accion == $a ? 'selected' : '' ?>><?= $a ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <input type="date" name="desde" class="form-control input-sm" value="<?= htmlspecialchars($f_desde) ?>" placeholder="Desde">
                    </div>
                    <div class="col-sm-2">
                        <input type="date" name="hasta" class="form-control input-sm" value="<?= htmlspecialchars($f_hasta) ?>" placeholder="Hasta">
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Filtrar</button>
                        <a href="auditoria.php" class="btn btn-default btn-sm"><i class="fa fa-times"></i> Limpiar</a>
                    </div>
                </div>
            </form>
        </div>

        <p class="text-muted" style="font-size:12px;"><i class="fa fa-info-circle"></i> Mostrando los últimos 500 registros.</p>

        <!-- ── TABLA ── -->
        <div class="panel panel-default">
            <div class="panel-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="font-size: 12px; margin-bottom:0;">
                        <thead>
                            <tr style="background:#0d2244; color:white;">
                                <th style="width:140px;">Fecha</th>
                                <th style="width:80px;" class="text-center">Acción</th>
                                <th style="width:120px;">Usuario</th>
                                <th>Proyecto</th>
                                <th>Detalle</th>
                                <th style="width:120px;">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($registros)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:30px;">
                                    <i class="fa fa-search fa-2x"></i><br>No se encontraron registros.
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach ($registros as $r):
                                $clase_badge = strtolower($r['accion']);
                            ?>
                            <tr>
                                <td><small><?= htmlspecialchars($r['fecha']) ?></small></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $clase_badge ?>"><?= htmlspecialchars($r['accion']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($r['usuario']) ?></td>
                                <td>
                                    <?php if ($r['id_proyecto']): ?>
                                        <a href="ficha.php?id=<?= intval($r['id_proyecto']) ?>">
                                            <?= htmlspecialchars($r['nombre_pry'] ?: '#' . $r['id_proyecto']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?= htmlspecialchars($r['detalle'] ?: '—') ?></small></td>
                                <td><small class="text-muted"><?= htmlspecialchars($r['ip'] ?: '—') ?></small></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<?php include_once 'footer_local.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
