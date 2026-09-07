<?php
// /intranet/proyectos/dashboard.php
include_once 'header_local.php';
require_once 'config/conexion.php';
require_once 'config/helpers.php';

// ── FILTROS ──────────────────────────────────────────────────────────────────
$f_gerencia = isset($_GET['gerencia']) ? trim($_GET['gerencia']) : '';
$f_estado   = isset($_GET['estado'])   ? intval($_GET['estado'])  : 0;
$f_etapa    = isset($_GET['etapa'])    ? trim($_GET['etapa'])     : '';

$where  = "WHERE 1=1";
$params = [];
if ($f_gerencia) { $where .= " AND p.codigo_gerencia = ?";    $params[] = $f_gerencia; }
if ($f_estado)   { $where .= " AND p.id_estado = ?";          $params[] = $f_estado; }
if ($f_etapa)    { $where .= " AND p.etapa_postulacion = ?";   $params[] = $f_etapa; }

// ── DATOS PARA FILTROS ───────────────────────────────────────────────────────
$gerencias = $pdo->query("SELECT DISTINCT codigo_gerencia FROM pry_proyectos WHERE codigo_gerencia IS NOT NULL ORDER BY codigo_gerencia")->fetchAll(PDO::FETCH_COLUMN);
$estados   = $pdo->query("SELECT id, nombre FROM pry_estados ORDER BY id")->fetchAll();
$etapas    = $pdo->query("SELECT DISTINCT etapa_postulacion FROM pry_proyectos WHERE etapa_postulacion IS NOT NULL ORDER BY etapa_postulacion")->fetchAll(PDO::FETCH_COLUMN);

// ── KPIs PRINCIPALES ────────────────────────────────────────────────────────
$sql_kpi = "SELECT
                COUNT(*)                        AS total,
                SUM(presupuesto_adjudicado)     AS total_adj,
                SUM(presupuesto_ejecutado)      AS total_eje,
                AVG(porcentaje_ejecucion)       AS avg_avance
            FROM pry_proyectos p $where";
$stmt = $pdo->prepare($sql_kpi); $stmt->execute($params); $kpi = $stmt->fetch();

$total       = (int)$kpi['total'];
$total_adj   = (float)$kpi['total_adj'];
$total_eje   = (float)$kpi['total_eje'];
$pct_global  = $total_adj > 0 ? round($total_eje / $total_adj * 100, 1) : 0;
$avg_avance  = round((float)$kpi['avg_avance'], 1);

// Proyectos con obs. críticas pendientes
$sql_obs = "SELECT COUNT(DISTINCT p.id) FROM pry_proyectos p
            INNER JOIN pry_observaciones o ON o.id_proyecto = p.id
            $where AND o.criticidad = 'Alta' AND o.estado != 'Resuelto' AND o.eliminado = 0";

$stmt_obs = $pdo->prepare($sql_obs); $stmt_obs->execute($params);
$con_obs_criticas = (int)$stmt_obs->fetchColumn();

// Próximos vencimientos de tareas (30 días)
$sql_venc = "SELECT COUNT(*) FROM pry_tareas t
             INNER JOIN pry_proyectos p ON p.id = t.id_proyecto
             $where AND t.progreso_real < 100
             AND t.fecha_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
$stmt_venc = $pdo->prepare($sql_venc); $stmt_venc->execute($params);
$proximos_venc = (int)$stmt_venc->fetchColumn();

// Proyectos con bajo avance presupuestario (< 20% y tienen presupuesto adjudicado)
$sql_bajo = "SELECT COUNT(*) FROM pry_proyectos p $where
             AND presupuesto_adjudicado > 0
             AND (presupuesto_ejecutado / presupuesto_adjudicado * 100) < 20";
$stmt_bajo = $pdo->prepare($sql_bajo); $stmt_bajo->execute($params);
$bajo_avance = (int)$stmt_bajo->fetchColumn();

// ── GRÁFICO: DISTRIBUCIÓN POR ESTADO ────────────────────────────────────────
$where_est = str_replace("WHERE 1=1", "1=1", $where);
$sql_est = "SELECT e.nombre, COUNT(p.id) AS total
            FROM pry_estados e
            LEFT JOIN pry_proyectos p ON p.id_estado = e.id AND $where_est
            GROUP BY e.id ORDER BY total DESC";
$stmt_est = $pdo->prepare($sql_est); $stmt_est->execute($params);
$por_estado = $stmt_est->fetchAll();

// ── GRÁFICO: DISTRIBUCIÓN POR RATE ──────────────────────────────────────────
$sql_rate = "SELECT COALESCE(rate,'SR') AS rate, COUNT(*) AS total FROM pry_proyectos p $where GROUP BY rate ORDER BY total DESC";
$stmt_rate = $pdo->prepare($sql_rate); $stmt_rate->execute($params);
$por_rate = $stmt_rate->fetchAll();

// ── GRÁFICO: EJECUCIÓN POR GERENCIA ─────────────────────────────────────────
$sql_ger = "SELECT codigo_gerencia,
                   SUM(presupuesto_adjudicado) AS adj,
                   SUM(presupuesto_ejecutado)  AS eje
            FROM pry_proyectos p $where AND codigo_gerencia IS NOT NULL
            GROUP BY codigo_gerencia ORDER BY adj DESC LIMIT 10";
$stmt_ger = $pdo->prepare($sql_ger); $stmt_ger->execute($params);
$por_gerencia = $stmt_ger->fetchAll();

// ── TABLA: PROYECTOS CON ALERTAS ─────────────────────────────────────────────
$sql_alerta = "SELECT p.id, p.nombre, p.cod_bip, p.rate, p.porcentaje_ejecucion,
                      p.presupuesto_adjudicado, p.presupuesto_ejecutado,
                      e.nombre AS estado_nombre,
                      (SELECT COUNT(*) FROM pry_observaciones o WHERE o.id_proyecto = p.id
                       AND o.criticidad = 'Alta' AND o.estado != 'Resuelto' AND o.eliminado = 0) AS obs_criticas,
                      (SELECT COUNT(*) FROM pry_tareas t WHERE t.id_proyecto = p.id
                       AND t.eliminado = 0 AND t.progreso_real < 100
                       AND t.fecha_fin < CURDATE()) AS tareas_vencidas
               FROM pry_proyectos p
               LEFT JOIN pry_estados e ON e.id = p.id_estado
               $where
               HAVING obs_criticas > 0 OR tareas_vencidas > 0
               ORDER BY obs_criticas DESC, tareas_vencidas DESC
               LIMIT 10";
$stmt_alerta = $pdo->prepare($sql_alerta); $stmt_alerta->execute($params);
$proyectos_alerta = $stmt_alerta->fetchAll();

// ── TABLA: PRÓXIMOS VENCIMIENTOS ─────────────────────────────────────────────
$sql_prox = "SELECT t.nombre AS tarea, t.fecha_fin,
                    p.id AS id_proyecto, p.nombre AS proyecto,
                    DATEDIFF(t.fecha_fin, CURDATE()) AS dias_restantes
             FROM pry_tareas t
             INNER JOIN pry_proyectos p ON p.id = t.id_proyecto
             $where AND t.progreso_real < 100
             AND t.fecha_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
             ORDER BY t.fecha_fin ASC LIMIT 8";
$stmt_prox = $pdo->prepare($sql_prox); $stmt_prox->execute($params);
$proximos = $stmt_prox->fetchAll();

// ── SEMÁFORO DE AVANCE ───────────────────────────────────────────────────────
$sql_salud = "SELECT p.fecha_inicio, p.fecha_entrega_estimada, p.porcentaje_ejecucion,
                     e.nombre AS estado_nombre
              FROM pry_proyectos p
              LEFT JOIN pry_estados e ON e.id = p.id_estado
              $where";
$stmt_salud = $pdo->prepare($sql_salud);
$stmt_salud->execute($params);

$salud_verde = 0; $salud_amarillo = 0; $salud_rojo = 0; $salud_gris = 0;
foreach ($stmt_salud->fetchAll() as $ps) {
    $s = calcular_semaforo_avance(
        $ps['porcentaje_ejecucion'],
        $ps['fecha_inicio'],
        $ps['fecha_entrega_estimada'],
        $ps['estado_nombre']
    );
    if ($s['clase'] === 'success')     $salud_verde++;
    elseif ($s['clase'] === 'warning') $salud_amarillo++;
    elseif ($s['clase'] === 'danger')  $salud_rojo++;
    else                               $salud_gris++;
}

// Colores para gráficos
$rate_colores = ['RS'=>'#27ae60','FI'=>'#f39c12','OT'=>'#e74c3c','ER'=>'#2980b9','SR'=>'#95a5a6'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Ejecutivo - Proyectos EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f0f2f5; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        #page-wrapper { margin-left: 250px; padding: 25px; min-height: 100vh; }
        .page-header { border-bottom: 2px solid #eee; margin-bottom: 20px; font-weight: 800; color: #333; background:white; padding: 15px 20px; border-radius: 4px; }

        /* KPI Cards */
        .kpi-card { background: white; border-radius: 6px; padding: 18px 20px; margin-bottom: 20px;
                    box-shadow: 0 1px 4px rgba(0,0,0,0.08); border-left: 5px solid #ccc; }
        .kpi-card .kpi-label { color: #888; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
        .kpi-card .kpi-valor { font-size: 26px; font-weight: bold; color: #0d2244; line-height: 1; }
        .kpi-card .kpi-sub { font-size: 11px; color: #aaa; margin-top: 4px; }

        @media print {
            #sidebar-simulado, .filtros, .no-print { display: none !important; }
            #page-wrapper { margin-left: 0 !important; padding: 10px !important; }
            body { background: white !important; }
            .kpi-card, .dash-panel { box-shadow: none !important; border: 1px solid #ddd !important; }
            @page { margin: 1.5cm; size: A4 landscape; }
        }

        /* Panels */
        .dash-panel { background: white; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .dash-panel .dp-header { padding: 12px 16px; border-bottom: 1px solid #f0f0f0; font-weight: bold; font-size: 13px; color: #0d2244; text-transform: uppercase; letter-spacing: .4px; }
        .dash-panel .dp-body { padding: 15px; }

        /* Filtros */
        .filtros { background: white; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
        .filtros select, .filtros .btn { margin-right: 8px; }

        /* Tabla alertas */
        .tabla-alertas { font-size: 12px; }
        .tabla-alertas th { background: #f8f9fa; font-size: 11px; text-transform: uppercase; color: #666; }
        .tabla-alertas td { vertical-align: middle; }

        /* Badge vencimiento */
        .dias-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: bold; color: white; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">

        <div class="page-header">
            <i class="fa fa-tachometer text-primary"></i> Dashboard Ejecutivo de Cartera
            <small class="text-muted" style="font-size:13px; font-weight:normal; margin-left:10px;">
                Actualizado: <?= date('d/m/Y H:i') ?>
            </small>
            <span class="pull-right" style="margin-top:-4px;">
                <button onclick="window.print()" class="btn btn-default btn-sm no-print">
                    <i class="fa fa-print"></i> Imprimir Dashboard
                </button>
                <a href="exportar_pdf.php" target="_blank" class="btn btn-success btn-sm no-print">
                    <i class="fa fa-file-excel-o"></i> Exportar Cartera
                </a>
            </span>
        </div>

        <!-- ── FILTROS ── -->
        <div class="filtros">
            <form method="GET" action="dashboard.php" style="display:flex; align-items:center; flex-wrap:wrap; gap:8px;">
                <strong style="font-size:12px; color:#555;"><i class="fa fa-filter"></i> Filtrar:</strong>

                <select name="gerencia" class="form-control" style="width:auto; display:inline-block; font-size:12px;">
                    <option value="">Todas las Gerencias</option>
                    <?php foreach($gerencias as $g): ?>
                        <option value="<?= htmlspecialchars($g) ?>" <?= $f_gerencia == $g ? 'selected' : '' ?>><?= htmlspecialchars($g) ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="estado" class="form-control" style="width:auto; display:inline-block; font-size:12px;">
                    <option value="">Todos los Estados</option>
                    <?php foreach($estados as $e): ?>
                        <option value="<?= $e['id'] ?>" <?= $f_estado == $e['id'] ? 'selected' : '' ?>><?= htmlspecialchars($e['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="etapa" class="form-control" style="width:auto; display:inline-block; font-size:12px;">
                    <option value="">Todas las Etapas</option>
                    <?php foreach($etapas as $et): ?>
                        <option value="<?= htmlspecialchars($et) ?>" <?= $f_etapa == $et ? 'selected' : '' ?>><?= htmlspecialchars($et) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Aplicar</button>
                <?php if ($f_gerencia || $f_estado || $f_etapa): ?>
                    <a href="dashboard.php" class="btn btn-default btn-sm"><i class="fa fa-times"></i> Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- ── KPIs ── -->
        <div class="row">
            <div class="col-md-2">
                <div class="kpi-card" style="border-left-color:#0d2244;">
                    <div class="kpi-label">Total Iniciativas</div>
                    <div class="kpi-valor"><?= $total ?></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="kpi-card" style="border-left-color:#2980b9;">
                    <div class="kpi-label">Presupuesto Adjudicado</div>
                    <div class="kpi-valor" style="font-size:18px;">$<?= number_format($total_adj/1000000, 1, ',', '.') ?>M</div>
                    <div class="kpi-sub"><?= number_format($total_adj, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="kpi-card" style="border-left-color:#27ae60;">
                    <div class="kpi-label">Presupuesto Ejecutado</div>
                    <div class="kpi-valor" style="font-size:18px; color:#27ae60;">$<?= number_format($total_eje/1000000, 1, ',', '.') ?>M</div>
                    <div class="kpi-sub"><?= $pct_global ?>% de ejecución</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="kpi-card" style="border-left-color:#f39c12;">
                    <div class="kpi-label">Avance Físico Promedio</div>
                    <div class="kpi-valor" style="color:#f39c12;"><?= $avg_avance ?>%</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="kpi-card" style="border-left-color:#e74c3c;">
                    <div class="kpi-label">Obs. Críticas Pendientes</div>
                    <div class="kpi-valor" style="color:#e74c3c;"><?= $con_obs_criticas ?></div>
                    <div class="kpi-sub">proyecto<?= $con_obs_criticas != 1 ? 's' : '' ?> afectado<?= $con_obs_criticas != 1 ? 's' : '' ?></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="kpi-card" style="border-left-color:#8e44ad;">
                    <div class="kpi-label">Vencimientos (30 días)</div>
                    <div class="kpi-valor" style="color:#8e44ad;"><?= $proximos_venc ?></div>
                    <div class="kpi-sub">tarea<?= $proximos_venc != 1 ? 's' : '' ?> próxima<?= $proximos_venc != 1 ? 's' : '' ?></div>
                </div>
            </div>
        </div>

        <!-- ── SEMÁFORO DE AVANCE ── -->
        <div class="dash-panel" style="margin-bottom:20px;">
            <div class="dp-header"><i class="fa fa-heartbeat"></i> Semáforo de Avance (físico vs planificado por fechas)</div>
            <div class="dp-body">
                <div class="row text-center">
                    <div class="col-xs-3">
                        <div style="background:#5cb85c; color:white; border-radius:6px; padding:15px 10px;">
                            <div style="font-size:32px; font-weight:bold; line-height:1;"><?= $salud_verde ?></div>
                            <div style="font-size:11px; margin-top:6px;"><i class="fa fa-check-circle"></i> Al día</div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div style="background:#f0ad4e; color:white; border-radius:6px; padding:15px 10px;">
                            <div style="font-size:32px; font-weight:bold; line-height:1;"><?= $salud_amarillo ?></div>
                            <div style="font-size:11px; margin-top:6px;"><i class="fa fa-exclamation-circle"></i> Leve retraso</div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div style="background:#d9534f; color:white; border-radius:6px; padding:15px 10px;">
                            <div style="font-size:32px; font-weight:bold; line-height:1;"><?= $salud_rojo ?></div>
                            <div style="font-size:11px; margin-top:6px;"><i class="fa fa-times-circle"></i> Retraso crítico</div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div style="background:#aaa; color:white; border-radius:6px; padding:15px 10px;">
                            <div style="font-size:32px; font-weight:bold; line-height:1;"><?= $salud_gris ?></div>
                            <div style="font-size:11px; margin-top:6px;"><i class="fa fa-circle-o"></i> Sin fechas / N/A</div>
                        </div>
                    </div>
                </div>
                <p class="text-muted" style="font-size:10px; margin:10px 0 0 0; text-align:right;">
                    <i class="fa fa-info-circle"></i> Compara % avance real vs % esperado según fechas de inicio y entrega.
                </p>
            </div>
        </div>

        <!-- ── FILA DE GRÁFICOS ── -->
        <div class="row">
            <!-- Distribución por Estado -->
            <div class="col-md-4">
                <div class="dash-panel">
                    <div class="dp-header"><i class="fa fa-pie-chart"></i> Por Estado</div>
                    <div class="dp-body" style="text-align:center;">
                        <canvas id="chartEstados" style="max-height:220px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Distribución por RATE -->
            <div class="col-md-4">
                <div class="dash-panel">
                    <div class="dp-header"><i class="fa fa-circle"></i> Por RATE</div>
                    <div class="dp-body" style="text-align:center;">
                        <canvas id="chartRate" style="max-height:220px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ejecución por Gerencia -->
            <div class="col-md-4">
                <div class="dash-panel">
                    <div class="dp-header"><i class="fa fa-bar-chart"></i> Ejecución por Gerencia</div>
                    <div class="dp-body">
                        <canvas id="chartGerencia" style="max-height:220px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── FILA: ALERTAS + PRÓXIMOS VENCIMIENTOS ── -->
        <div class="row">
            <!-- Proyectos con alertas -->
            <div class="col-md-7">
                <div class="dash-panel">
                    <div class="dp-header" style="color:#e74c3c;"><i class="fa fa-exclamation-triangle"></i> Proyectos con Alertas</div>
                    <div class="dp-body" style="padding:0;">
                        <?php if (empty($proyectos_alerta)): ?>
                            <p class="text-center text-muted" style="padding:20px;"><i class="fa fa-check-circle text-success"></i> Sin alertas activas.</p>
                        <?php else: ?>
                        <table class="table tabla-alertas" style="margin-bottom:0;">
                            <thead><tr><th>Proyecto</th><th class="text-center">Obs. Críticas</th><th class="text-center">Tareas Vencidas</th><th class="text-center">% Ejec.</th></tr></thead>
                            <tbody>
                            <?php foreach ($proyectos_alerta as $pa):
                                $pct_pa = ($pa['presupuesto_adjudicado'] > 0)
                                    ? round($pa['presupuesto_ejecutado'] / $pa['presupuesto_adjudicado'] * 100, 0)
                                    : (int)$pa['porcentaje_ejecucion'];
                            ?>
                            <tr>
                                <td>
                                    <a href="ficha.php?id=<?= $pa['id'] ?>"><?= htmlspecialchars($pa['nombre']) ?></a>
                                    <br><small class="text-muted"><?= htmlspecialchars($pa['estado_nombre'] ?: '—') ?></small>
                                </td>
                                <td class="text-center">
                                    <?php if ($pa['obs_criticas'] > 0): ?>
                                        <span class="badge" style="background:#e74c3c;"><?= $pa['obs_criticas'] ?></span>
                                    <?php else: ?><span class="text-muted">—</span><?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($pa['tareas_vencidas'] > 0): ?>
                                        <span class="badge" style="background:#f39c12;"><?= $pa['tareas_vencidas'] ?></span>
                                    <?php else: ?><span class="text-muted">—</span><?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span style="font-weight:bold; color:<?= $pct_pa >= 60 ? '#27ae60' : ($pct_pa >= 30 ? '#f39c12' : '#e74c3c') ?>;"><?= $pct_pa ?>%</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Próximos vencimientos -->
            <div class="col-md-5">
                <div class="dash-panel">
                    <div class="dp-header" style="color:#8e44ad;"><i class="fa fa-clock-o"></i> Próximos Vencimientos (30 días)</div>
                    <div class="dp-body" style="padding:0;">
                        <?php if (empty($proximos)): ?>
                            <p class="text-center text-muted" style="padding:20px;"><i class="fa fa-calendar-check-o"></i> Sin vencimientos próximos.</p>
                        <?php else: ?>
                        <table class="table tabla-alertas" style="margin-bottom:0;">
                            <thead><tr><th>Tarea / Proyecto</th><th class="text-center">Vence</th></tr></thead>
                            <tbody>
                            <?php foreach ($proximos as $pv):
                                $dias = (int)$pv['dias_restantes'];
                                $color = $dias <= 7 ? '#e74c3c' : ($dias <= 15 ? '#f39c12' : '#8e44ad');
                            ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($pv['tarea']) ?></strong>
                                    <br><small class="text-muted"><a href="ficha.php?id=<?= $pv['id_proyecto'] ?>"><?= htmlspecialchars($pv['proyecto']) ?></a></small>
                                </td>
                                <td class="text-center">
                                    <span class="dias-badge" style="background:<?= $color ?>;"><?= $dias ?>d</span>
                                    <br><small class="text-muted"><?= date('d/m', strtotime($pv['fecha_fin'])) ?></small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php include_once 'footer_local.php'; ?>

<script>
// ── GRÁFICO: ESTADOS ──────────────────────────────────────────────────────
new Chart(document.getElementById('chartEstados'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($por_estado, 'nombre')) ?>,
        datasets: [{ data: <?= json_encode(array_column($por_estado, 'total')) ?>,
            backgroundColor: ['#3498db','#2ecc71','#f39c12','#e74c3c','#9b59b6','#1abc9c','#e67e22','#34495e']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
});

// ── GRÁFICO: RATE ────────────────────────────────────────────────────────
<?php
$rate_labels = array_column($por_rate, 'rate');
$rate_vals   = array_column($por_rate, 'total');
$rate_bg     = array_map(function($r) use ($rate_colores) {
    return isset($rate_colores[$r]) ? $rate_colores[$r] : '#95a5a6';
}, $rate_labels);
?>
new Chart(document.getElementById('chartRate'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($rate_labels) ?>,
        datasets: [{ data: <?= json_encode($rate_vals) ?>, backgroundColor: <?= json_encode($rate_bg) ?> }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
});

// ── GRÁFICO: GERENCIA ────────────────────────────────────────────────────
new Chart(document.getElementById('chartGerencia'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($por_gerencia, 'codigo_gerencia')) ?>,
        datasets: [
            { label: 'Adjudicado', data: <?= json_encode(array_column($por_gerencia, 'adj')) ?>, backgroundColor: 'rgba(41,128,185,0.7)' },
            { label: 'Ejecutado',  data: <?= json_encode(array_column($por_gerencia, 'eje')) ?>, backgroundColor: 'rgba(39,174,96,0.7)'  }
        ]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
        scales: {
            x: { ticks: { callback: function(v) { return '$' + (v/1000000).toFixed(1) + 'M'; }, font: { size: 10 } } },
            y: { ticks: { font: { size: 10 } } }
        }
    }
});
</script>
</body>
</html>
