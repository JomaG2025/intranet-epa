<?php
// /intranet/proyectos/index.php
include_once 'header_local.php';
require_once 'config/conexion.php';
require_once 'config/helpers.php';

// ── FILTROS ──────────────────────────────────────────────────────────────────
$f_buscar   = isset($_GET['buscar'])   ? trim($_GET['buscar'])    : '';
$f_estado   = isset($_GET['estado'])   ? intval($_GET['estado'])  : 0;
$f_rate     = isset($_GET['rate'])     ? trim($_GET['rate'])      : '';
$f_gerencia = isset($_GET['gerencia']) ? trim($_GET['gerencia'])  : '';
$f_etapa    = isset($_GET['etapa'])    ? trim($_GET['etapa'])     : '';
$f_orden    = isset($_GET['orden'])   ? trim($_GET['orden'])    : '';

$ordenes_validos = [
    'nombre_asc'  => 'p.nombre ASC',
    'nombre_desc' => 'p.nombre DESC',
    'bip_asc'     => 'p.cod_bip ASC',
    'bip_desc'    => 'p.cod_bip DESC',
    'tipologia'   => 'p.tipologia ASC',
    'estado'      => 'e.nombre ASC',
    'gerencia'    => 'p.codigo_gerencia ASC',
    'rate'        => 'p.rate ASC',
    'avance_desc' => 'p.porcentaje_ejecucion DESC',
    'avance_asc'  => 'p.porcentaje_ejecucion ASC',
];
$order_by = isset($ordenes_validos[$f_orden]) ? $ordenes_validos[$f_orden] : 'p.id DESC';

$hay_filtro = $f_buscar || $f_estado || $f_rate || $f_gerencia || $f_etapa;

// ── OPCIONES PARA SELECTS ────────────────────────────────────────────────────
$estados   = $pdo->query("SELECT id, nombre FROM pry_estados ORDER BY id")->fetchAll();
$gerencias = $pdo->query("SELECT DISTINCT codigo_gerencia FROM pry_proyectos WHERE codigo_gerencia IS NOT NULL ORDER BY codigo_gerencia")->fetchAll(PDO::FETCH_COLUMN);
$etapas    = $pdo->query("SELECT DISTINCT etapa_postulacion FROM pry_proyectos WHERE etapa_postulacion IS NOT NULL ORDER BY etapa_postulacion")->fetchAll(PDO::FETCH_COLUMN);

// ── CONSULTA CON FILTROS ─────────────────────────────────────────────────────
$where  = "WHERE 1=1";
$params = [];

if ($f_buscar) {
    $where   .= " AND (p.nombre LIKE ? OR p.cod_bip LIKE ?)";
    $params[] = "%$f_buscar%";
    $params[] = "%$f_buscar%";
}
if ($f_estado)   { $where .= " AND p.id_estado = ?";           $params[] = $f_estado; }
if ($f_rate)     { $where .= " AND p.rate = ?";                $params[] = $f_rate; }
if ($f_gerencia) { $where .= " AND p.codigo_gerencia = ?";     $params[] = $f_gerencia; }
if ($f_etapa)    { $where .= " AND p.etapa_postulacion = ?";   $params[] = $f_etapa; }

$sql = "SELECT p.*, e.nombre AS estado_nombre,
               (SELECT COUNT(*) FROM pry_observaciones o
                WHERE o.id_proyecto = p.id
                AND o.criticidad = 'Alta' AND o.estado != 'Resuelto' AND o.eliminado = 0) AS obs_criticas
        FROM pry_proyectos p
        LEFT JOIN pry_estados e ON p.id_estado = e.id
        $where
        ORDER BY $order_by";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$proyectos = $stmt->fetchAll();
$total_resultado = count($proyectos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Estratégica de Proyectos - EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .page-header { border-bottom: 2px solid #eee; margin: 0 0 20px 0; padding-bottom: 10px; font-weight: 800; color: #333; }
        .label-rate { min-width: 38px; display: inline-block; padding: 4px 7px; border-radius: 4px; font-weight: bold; text-align: center; }
        .filtros-bar { background: white; border: 1px solid #ddd; border-radius: 4px; padding: 12px 15px; margin-bottom: 15px; }
        .filtros-bar .form-control { font-size: 12px; }
        .resultado-count { font-size: 12px; color: #888; margin-bottom: 8px; }
        .highlight { background-color: #fff3cd; }
        th a { color: inherit; text-decoration: none; }
        th a:hover { text-decoration: underline; }
        .col-avance { width: 80px; }
        .progress { margin-bottom: 0; height: 10px; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">

        <h1 class="page-header"><i class="fa fa-briefcase text-primary"></i> Gestión Estratégica de Proyectos</h1>

        <!-- ── ACCIONES ── -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-sm-6">
                <?php if ($_SESSION['permisos']['p_crear']): ?>
                    <a href="nuevo.php" class="btn btn-primary btn-sm" style="font-weight:bold;"><i class="fa fa-plus"></i> Nueva Iniciativa</a>
                <?php endif; ?>
            </div>
            <div class="col-sm-6 text-right">
                <a href="exportar_excel.php<?= $hay_filtro ? '?' . http_build_query($_GET) : '' ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-file-excel-o"></i> Excel
                </a>
                <a href="exportar_pdf.php<?= $hay_filtro ? '?' . http_build_query($_GET) : '' ?>" class="btn btn-default btn-sm" target="_blank">
                    <i class="fa fa-print"></i> PDF
                </a>
            </div>
        </div>

        <!-- ── FILTROS ── -->
        <div class="filtros-bar">
            <form method="GET" action="index.php">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" name="buscar" class="form-control" placeholder="Nombre o Código BIP..." value="<?= htmlspecialchars($f_buscar) ?>">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <select name="estado" class="form-control input-sm">
                            <option value="">Todos los estados</option>
                            <?php foreach ($estados as $e): ?>
                                <option value="<?= $e['id'] ?>" <?= $f_estado == $e['id'] ? 'selected' : '' ?>><?= htmlspecialchars($e['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <select name="rate" class="form-control input-sm">
                            <option value="">RATE</option>
                            <?php foreach (['RS','RSA','FI','OT','ER','SR'] as $r): ?>
                                <option value="<?= $r ?>" <?= $f_rate == $r ? 'selected' : '' ?>><?= $r ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="gerencia" class="form-control input-sm">
                            <option value="">Todas las gerencias</option>
                            <?php foreach ($gerencias as $g): ?>
                                <option value="<?= htmlspecialchars($g) ?>" <?= $f_gerencia == $g ? 'selected' : '' ?>><?= htmlspecialchars($g) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="etapa" class="form-control input-sm">
                            <option value="">Todas las etapas</option>
                            <?php foreach ($etapas as $et): ?>
                                <option value="<?= htmlspecialchars($et) ?>" <?= $f_etapa == $et ? 'selected' : '' ?>><?= htmlspecialchars($et) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>
                        <?php if ($hay_filtro): ?>
                            <a href="index.php" class="btn btn-default btn-sm" title="Limpiar filtros"><i class="fa fa-times"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row" style="margin-top:8px;">
                    <div class="col-sm-3">
                        <select name="orden" class="form-control input-sm">
                            <option value="">Ordenar: Más recientes primero</option>
                            <option value="nombre_asc"  <?= $f_orden=='nombre_asc'  ? 'selected':'' ?>>Nombre A → Z</option>
                            <option value="nombre_desc" <?= $f_orden=='nombre_desc' ? 'selected':'' ?>>Nombre Z → A</option>
                            <option value="bip_asc"     <?= $f_orden=='bip_asc'     ? 'selected':'' ?>>BIP A → Z</option>
                            <option value="bip_desc"    <?= $f_orden=='bip_desc'    ? 'selected':'' ?>>BIP Z → A</option>
                            <option value="tipologia"   <?= $f_orden=='tipologia'   ? 'selected':'' ?>>Tipología</option>
                            <option value="estado"      <?= $f_orden=='estado'      ? 'selected':'' ?>>Estado</option>
                            <option value="gerencia"    <?= $f_orden=='gerencia'    ? 'selected':'' ?>>Gerencia</option>
                            <option value="rate"        <?= $f_orden=='rate'        ? 'selected':'' ?>>RATE</option>
                            <option value="avance_desc" <?= $f_orden=='avance_desc' ? 'selected':'' ?>>Mayor avance primero</option>
                            <option value="avance_asc"  <?= $f_orden=='avance_asc'  ? 'selected':'' ?>>Menor avance primero</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- ── RESULTADO ── -->
        <div class="resultado-count">
            <?php if ($hay_filtro): ?>
                <i class="fa fa-filter"></i> <strong><?= $total_resultado ?></strong> resultado<?= $total_resultado != 1 ? 's' : '' ?> encontrado<?= $total_resultado != 1 ? 's' : '' ?>.
                <a href="index.php">Ver todos</a>
            <?php else: ?>
                <i class="fa fa-list"></i> <strong><?= $total_resultado ?></strong> iniciativa<?= $total_resultado != 1 ? 's' : '' ?> en cartera.
            <?php endif; ?>
        </div>

        <!-- ── TABLA ── -->
        <div class="panel panel-default">
            <div class="panel-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="font-size: 12px; margin-bottom:0;">
                        <thead>
                            <tr style="background:#0d2244; color:white;">
                                <th style="width:90px;">BIP</th>
                                <th>NOMBRE DE LA INICIATIVA</th>
                                <th style="width:80px;">TIPOLOGÍA</th>
                                <th style="width:110px;">ESTADO</th>
                                <th style="width:100px;">GERENCIA</th>
                                <th style="width:45px;" class="text-center">RATE</th>
                                <th style="width:90px;" class="text-center">% AVANCE</th>
                                <th style="width:95px;" class="text-center">SALUD</th>
                                <th style="width:80px;" class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($proyectos)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="padding:30px;">
                                    <i class="fa fa-search fa-2x"></i><br>
                                    No se encontraron iniciativas con los filtros aplicados.
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach ($proyectos as $p):
                                $sem    = obtener_semaforo($p['rate']);
                                $salud  = calcular_semaforo_avance($p['porcentaje_ejecucion'], $p['fecha_inicio'], $p['fecha_entrega_estimada'], $p['estado_nombre']);
                                $pct    = min((int)$p['porcentaje_ejecucion'], 100);
                                $color_pct  = $pct >= 80 ? '#27ae60' : ($pct >= 40 ? '#f39c12' : '#e74c3c');
                                $fila_clase = $p['obs_criticas'] > 0 ? 'highlight' : '';
                            ?>
                            <tr class="<?= $fila_clase ?>">
                                <td><small class="text-muted"><?= htmlspecialchars($p['cod_bip'] ?: 'S/N') ?></small></td>
                                <td>
                                    <a href="ficha.php?id=<?= $p['id'] ?>" style="color:#0d2244; font-weight:bold;">
                                        <?php if ($f_buscar): ?>
                                            <?= str_ireplace($f_buscar, '<mark>'.$f_buscar.'</mark>', htmlspecialchars($p['nombre'])) ?>
                                        <?php else: ?>
                                            <?= htmlspecialchars($p['nombre']) ?>
                                        <?php endif; ?>
                                    </a>
                                    <?php if ($p['obs_criticas'] > 0): ?>
                                        <span class="label label-danger" style="margin-left:5px; font-size:10px;" title="<?= $p['obs_criticas'] ?> obs. crítica(s) pendiente(s)">
                                            <i class="fa fa-exclamation-triangle"></i> <?= $p['obs_criticas'] ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?= htmlspecialchars($p['tipologia'] ?: '—') ?></small></td>
                                <td><small><?= htmlspecialchars($p['estado_nombre'] ?: 'Sin asignar') ?></small></td>
                                <td><small><?= htmlspecialchars($p['codigo_gerencia'] ?: '—') ?></small></td>
                                <td class="text-center">
                                    <span class="label <?= $sem['clase'] ?> label-rate"><?= htmlspecialchars($p['rate'] ?: 'SR') ?></span>
                                </td>
                                <td class="text-center col-avance">
                                    <small style="font-weight:bold; color:<?= $color_pct ?>;"><?= $pct ?>%</small>
                                    <div class="progress" style="margin-top:3px;">
                                        <div class="progress-bar" style="width:<?= $pct ?>%; background:<?= $color_pct ?>;"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span title="<?= htmlspecialchars($salud['texto']) ?>"
                                          style="display:inline-block; width:14px; height:14px; border-radius:50%; background:<?= $salud['color'] ?>; vertical-align:middle;"></span>
                                    <small style="color:<?= $salud['color'] ?>; font-weight:bold;"><?= htmlspecialchars($salud['texto']) ?></small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="ficha.php?id=<?= $p['id'] ?>" class="btn btn-default btn-xs" title="Ver detalle"><i class="fa fa-search"></i></a>
                                        <?php if ($_SESSION['permisos']['p_editar']): ?>
                                            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-xs" title="Editar"><i class="fa fa-pencil"></i></a>
                                        <?php endif; ?>
                                        <?php if ($_SESSION['permisos']['p_crear']): ?>
                                            <a href="acciones/eliminar_proyecto.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-xs" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar esta iniciativa?');"><i class="fa fa-trash"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </td>
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
