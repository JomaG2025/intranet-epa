<?php 
// /intranet/proyectos/ficha.php
include_once 'header_local.php'; 
require_once 'config/conexion.php';
require_once 'config/helpers.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { die("ID no válido."); }

// 1. Obtención de datos
$pry = $pdo->prepare("SELECT p.*, e.nombre AS estado_nombre FROM pry_proyectos p LEFT JOIN pry_estados e ON p.id_estado = e.id WHERE p.id = ?");
$pry->execute([$id]);
$pry = $pry->fetch();

// Historial de estados
$stmt_h = $pdo->prepare("SELECT * FROM pry_historial_estados WHERE id_proyecto = ? ORDER BY fecha DESC");
$stmt_h->execute([$id]);
$historial = $stmt_h->fetchAll();

// Observaciones y riesgos
$stmt_obs = $pdo->prepare("SELECT * FROM pry_observaciones WHERE id_proyecto = ? AND eliminado = 0 ORDER BY criticidad = 'Alta' DESC, fecha_registro DESC");
$stmt_obs->execute([$id]);
$observaciones = $stmt_obs->fetchAll();

// Ficha IDI
$stmt_idi = $pdo->prepare("SELECT * FROM pry_ficha_idi WHERE id_proyecto = ? ORDER BY orden");
$stmt_idi->execute([$id]);
$idi_rows = $stmt_idi->fetchAll();

// Modificaciones presupuesto adjudicado
$stmt_pmod = $pdo->prepare("SELECT * FROM pry_presupuesto_mod WHERE id_proyecto = ? ORDER BY fecha DESC, created_at DESC");
$stmt_pmod->execute([$id]);
$pres_mods = $stmt_pmod->fetchAll();

$stmt_t = $pdo->prepare("SELECT * FROM pry_tareas WHERE id_proyecto = ? AND eliminado = 0 ORDER BY fecha_inicio ASC");
$stmt_t->execute([$id]);
$tareas_db = $stmt_t->fetchAll();

// 2. Preparación de datos para Curva S
$labels_curva = []; $data_pv = []; $data_ev = [];

$inicio_f = ($pry['fecha_inicio'] && $pry['fecha_inicio'] !== '0000-00-00') ? $pry['fecha_inicio'] : date('Y-m-d');
$fin_f    = ($pry['fecha_entrega_estimada'] && $pry['fecha_entrega_estimada'] !== '0000-00-00') ? $pry['fecha_entrega_estimada'] : date('Y-m-d', strtotime('+6 months'));

$periodo = new DatePeriod(new DateTime($inicio_f), new DateInterval('P1M'), new DateTime($fin_f));
foreach ($periodo as $dt) {
    $corte = $dt->format('Y-m-t');
    $labels_curva[] = $dt->format('M Y');
    $acum_p = 0; $acum_r = 0;
    foreach ($tareas_db as $t) {
        if ($t['fecha_fin'] <= $corte) $acum_p += (float)$t['costo_estimado'];
        $acum_r += ((float)$t['costo_estimado'] * ((int)$t['progreso_real'] / 100));
    }
    $data_pv[] = $acum_p;
    $data_ev[] = $acum_r;
}

// 3. Total EV para el panel de resumen
$total_ev = 0;
foreach ($tareas_db as $t) {
    $total_ev += ((float)$t['costo_estimado'] * ((int)$t['progreso_real'] / 100));
}

// 4. Tareas para el Gantt (solo las que tienen fechas válidas)
$js_tasks = [];
foreach ($tareas_db as $t) {
    if (!empty($t['fecha_inicio']) && $t['fecha_inicio'] !== '0000-00-00'
     && !empty($t['fecha_fin'])   && $t['fecha_fin']   !== '0000-00-00') {
        $js_tasks[] = [
            'id'           => 'T-' . $t['id'],
            'name'         => $t['nombre'],
            'start'        => $t['fecha_inicio'],
            'end'          => $t['fecha_fin'],
            'progress'     => (int)$t['progreso_real'],
            'dependencies' => ''
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pry['nombre']) ?> - EPA</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Frappe Gantt — versión fija para estabilidad -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">

    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }

        /* SIDEBAR */
        #sidebar-simulado { 
            width: 250px !important; position: fixed !important; height: 100% !important;
            background: #0d2244 !important; color: white !important; padding-top: 20px !important;
            z-index: 1000 !important; left: 0 !important; top: 0 !important; overflow-y: auto;
        }
        .user-panel { padding: 20px; text-align: center; border-bottom: 1px solid #3e4f5f; margin-bottom: 10px; }
        .user-panel img { width: 60px !important; height: 60px !important; border-radius: 50% !important; border: 2px solid #34495e !important; margin-bottom: 10px !important; object-fit: cover; }
        #sidebar-simulado a { color: #d1d1d1 !important; padding: 15px 20px !important; display: block !important; text-decoration: none !important; border-bottom: 1px solid #34495e !important; font-size: 14px !important; }
        #sidebar-simulado a:hover { background: #1a252f; color: white; text-decoration: none; }
        #sidebar-simulado i { margin-right: 10px; }

        /* CONTENIDO */
        #page-wrapper { margin-left: 250px !important; padding: 25px !important; background: white !important; min-height: 100vh !important; }

        /* PANELES */
        .custom-panel { border: 1px solid #ddd; margin-bottom: 20px; border-radius: 4px; background: #fff; box-shadow: 0 1px 1px rgba(0,0,0,.05); }
        .panel-heading { background: #f8f9fa; font-weight: bold; padding: 10px 15px; border-bottom: 1px solid #ddd; text-transform: uppercase; font-size: 11px; letter-spacing: .5px; }
        .info-label { color: #888; font-size: 10px; text-transform: uppercase; font-weight: bold; margin-bottom: 2px; }

        /* GANTT */
        .gantt-container { overflow-x: auto; overflow-y: hidden; background: #fff; min-height: 200px; padding: 10px; }
        #gantt-mensaje { padding: 20px; text-align: center; color: #999; display: none; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">

        <h2 class="page-header" style="font-weight: bold; color: #333;">
            <?= htmlspecialchars($pry['nombre']) ?>
        </h2>

        <!-- ── BOTONES DE ACCIÓN ── -->
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-12 text-right">
                <a href="exportar_ficha_pdf.php?id=<?= $id ?>" class="btn btn-default btn-sm" target="_blank">
                    <i class="fa fa-print"></i> Exportar PDF
                </a>
                <a href="repositorio.php?id=<?= $id ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-folder-open" style="color:#f39c12;"></i> Repositorio
                </a>
                <a href="editar.php?id=<?= $id ?>" class="btn btn-warning btn-sm" style="color:white;">
                    <i class="fa fa-pencil"></i> Editar Iniciativa
                </a>
            </div>
        </div>

        <!-- ── FICHA DE DATOS ── -->
        <div class="custom-panel" style="margin-bottom:20px;">
            <div class="panel-heading"><i class="fa fa-file-text-o"></i> Ficha de la Iniciativa</div>
            <div class="panel-body">

                <!-- Estado + RATE + Salud -->
                <?php
                $salud_pry = calcular_semaforo_avance(
                    $pry['porcentaje_ejecucion'],
                    $pry['fecha_inicio'],
                    $pry['fecha_entrega_estimada'],
                    $pry['estado_nombre']
                );
                $sem_pry = obtener_semaforo($pry['rate']);
                ?>
                <div class="row" style="margin-bottom:18px; padding-bottom:15px; border-bottom:1px solid #eee;">
                    <div class="col-md-4">
                        <p class="info-label">Estado</p>
                        <span class="label label-default" style="font-size:13px; padding:5px 10px; font-weight:bold;">
                            <?= htmlspecialchars($pry['estado_nombre'] ?: 'Sin asignar') ?>
                        </span>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label">RATE</p>
                        <span class="label <?= $sem_pry['clase'] ?>" style="font-size:13px; padding:5px 10px;">
                            <i class="fa <?= $sem_pry['icono'] ?>"></i>
                            <?= htmlspecialchars($pry['rate'] ?: 'SR') ?> — <?= $sem_pry['texto'] ?>
                        </span>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label">Salud del Proyecto</p>
                        <span class="label label-<?= $salud_pry['clase'] ?>" style="font-size:13px; padding:5px 10px;">
                            <i class="fa <?= $salud_pry['icono'] ?>"></i> <?= $salud_pry['texto'] ?>
                        </span>
                        <?php if (!empty($pry['fecha_entrega_estimada']) && $pry['fecha_entrega_estimada'] !== '0000-00-00'): ?>
                            <br><small class="text-muted" style="font-size:10px;">
                                Entrega estimada: <?= date('d/m/Y', strtotime($pry['fecha_entrega_estimada'])) ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <p class="info-label">Código BIP</p>
                        <p><?= htmlspecialchars($pry['cod_bip'] ?: '—') ?></p>
                    </div>
                    <div class="col-md-2">
                        <p class="info-label">Tipología</p>
                        <p><?= htmlspecialchars($pry['tipologia'] ?: '—') ?></p>
                    </div>
                    <div class="col-md-2">
                        <p class="info-label">Etapa</p>
                        <p><?= htmlspecialchars($pry['etapa_postulacion'] ?: '—') ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="info-label">Encargado de Formulación</p>
                        <p><?= htmlspecialchars(isset($pry['encargado_formulacion']) ? ($pry['encargado_formulacion'] ?: '—') : '—') ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="info-label">Aprobación Financiera GDS</p>
                        <p>
                        <?php if (!empty($pry['aprobacion_gds'])): ?>
                            <span class="label label-success"><i class="fa fa-check-circle"></i> Aprobado</span>
                            <?php if (!empty($pry['aprobacion_gds_fecha']) && $pry['aprobacion_gds_fecha'] !== '0000-00-00'): ?>
                                <br><small class="text-muted"><?= date('d/m/Y', strtotime($pry['aprobacion_gds_fecha'])) ?> — <?= htmlspecialchars($pry['aprobacion_gds_usuario']) ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="label label-warning"><i class="fa fa-clock-o"></i> Pendiente</span>
                        <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <p class="info-label">Clasificación</p>
                        <p><?= htmlspecialchars($pry['clasificacion'] ?: '—') ?></p>
                    </div>
                    <?php if (!empty($pry['solicitud_ip'])): ?>
                    <div class="col-md-3">
                        <p class="info-label">Solicitud IP</p>
                        <p><?= htmlspecialchars($pry['solicitud_ip']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <p class="info-label">Prioridad Estratégica</p>
                        <p><?= htmlspecialchars($pry['prioridad_estrategica'] ?: '—') ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="info-label">Fuente de Financiamiento</p>
                        <p><?= htmlspecialchars($pry['fuente_financiamiento'] ?: '—') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="info-label">Alineación Estratégica</p>
                        <p><?= htmlspecialchars($pry['alineacion_estrategica'] ?: '—') ?></p>
                    </div>
                </div>
                <?php if (!empty($pry['descripcion'])): ?>
                <div class="row">
                    <div class="col-md-12">
                        <p class="info-label">Descripción</p>
                        <p><?= nl2br(htmlspecialchars($pry['descripcion'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($pry['observaciones'])): ?>
                <div class="row">
                    <div class="col-md-12">
                        <p class="info-label">Observaciones</p>
                        <p style="color:#c0392b;"><?= nl2br(htmlspecialchars($pry['observaciones'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── RESUMEN FINANCIERO ── -->
        <?php
        $pres_ip  = isset($pry['presupuesto_ip'])         ? (float)$pry['presupuesto_ip']         : 0;
        $pres_adj = isset($pry['presupuesto_adjudicado']) ? (float)$pry['presupuesto_adjudicado'] : 0;
        $pres_eje = isset($pry['presupuesto_ejecutado'])  ? (float)$pry['presupuesto_ejecutado']  : 0;
        $pct_eje  = ($pres_adj > 0) ? round($pres_eje / $pres_adj * 100, 1) : (int)$pry['porcentaje_ejecucion'];
        ?>
        <?php if ($pres_ip > 0 || $pres_adj > 0 || $pres_eje > 0): ?>
        <div class="custom-panel" style="margin-bottom:20px;">
            <div class="panel-heading"><i class="fa fa-dollar"></i> Presupuesto</div>
            <div class="panel-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <p class="info-label">Presupuesto IP</p>
                        <p style="font-size:18px; font-weight:bold; color:#555;">$<?= number_format($pres_ip, 0, ',', '.') ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="info-label">Presupuesto Adjudicado</p>
                        <p style="font-size:18px; font-weight:bold; color:#337ab7;">$<?= number_format($pres_adj, 0, ',', '.') ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="info-label">Presupuesto Ejecutado</p>
                        <p style="font-size:18px; font-weight:bold; color:#5cb85c;">$<?= number_format($pres_eje, 0, ',', '.') ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="info-label">% Ejecución Presupuestaria</p>
                        <p style="font-size:18px; font-weight:bold; color:<?= $pct_eje >= 80 ? '#5cb85c' : ($pct_eje >= 40 ? '#f0ad4e' : '#d9534f') ?>;">
                            <?= $pct_eje ?>%
                        </p>
                    </div>
                </div>
                <?php if ($pres_adj > 0): ?>
                <div class="progress" style="margin-bottom:0; margin-top:5px;">
                    <div class="progress-bar progress-bar-success" style="width: <?= min($pct_eje, 100) ?>%"><?= $pct_eje ?>%</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── FICHA IDI ── -->
        <?php if (!empty($idi_rows)):
            // Agrupar filas por (anio_pagado, anio_solic)
            $idi_grupos = [];
            $idi_orden_grupos = [];
            foreach ($idi_rows as $fila) {
                $ap  = isset($fila['anio_pagado']) && $fila['anio_pagado'] ? (int)$fila['anio_pagado'] : 2025;
                $as  = isset($fila['anio_solic'])  && $fila['anio_solic']  ? (int)$fila['anio_solic']  : 2026;
                $key = $ap . '_' . $as;
                if (!isset($idi_grupos[$key])) {
                    $idi_grupos[$key] = ['anio_p' => $ap, 'anio_s' => $as, 'filas' => []];
                    $idi_orden_grupos[] = $key;
                }
                $idi_grupos[$key]['filas'][] = $fila;
            }
        ?>
        <div class="custom-panel" style="margin-bottom:20px;">
            <div class="panel-heading"><i class="fa fa-table"></i> Ficha IDI — Solicitud de Financiamiento</div>
            <?php foreach ($idi_orden_grupos as $gkey):
                $g = $idi_grupos[$gkey];
            ?>
            <div class="table-responsive" style="margin-bottom:0;">
                <table class="table table-bordered" style="font-size:12px; margin-bottom:0;">
                    <thead>
                        <tr style="background:#0d2244; color:white;">
                            <th>Fuente</th>
                            <th>Asignación Presupuestaria (Ítem)</th>
                            <th>Moneda</th>
                            <th class="text-right">Pagado al 31/12/<?= $g['anio_p'] ?></th>
                            <th class="text-right">Solicitado año <?= $g['anio_s'] ?></th>
                            <th class="text-right">Solicitado años sig.</th>
                            <th class="text-right">Costo Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($g['filas'] as $idi): ?>
                        <tr>
                            <td><?= htmlspecialchars($idi['fuente'] ?: '—') ?></td>
                            <td><?= htmlspecialchars($idi['asignacion'] ?: '—') ?></td>
                            <td><?= htmlspecialchars($idi['moneda']) ?></td>
                            <td class="text-right"><?= number_format($idi['pagado_2025'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($idi['solic_2026'], 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($idi['solic_sig'], 0, ',', '.') ?></td>
                            <td class="text-right"><strong><?= number_format($idi['costo_total'], 0, ',', '.') ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:#f5f5f5; font-weight:bold;">
                            <td colspan="3">Subtotal período</td>
                            <td class="text-right"><?= number_format(array_sum(array_column($g['filas'], 'pagado_2025')), 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format(array_sum(array_column($g['filas'], 'solic_2026')), 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format(array_sum(array_column($g['filas'], 'solic_sig')), 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format(array_sum(array_column($g['filas'], 'costo_total')), 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php endforeach; ?>
            <?php if (count($idi_orden_grupos) > 1): ?>
            <div style="padding:8px 14px; background:#eaf0fb; border-top:2px solid #0d2244; font-size:12px; font-weight:bold;">
                <span>Total general:</span>
                <span style="float:right; margin-right:10px;"><?= number_format(array_sum(array_column($idi_rows, 'costo_total')), 0, ',', '.') ?> (Costo Total)</span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- ── LICITACIÓN ── -->
        <?php if (!empty($pry['lic_tipo']) || !empty($pry['lic_empresa']) || !empty($pry['lic_estado'])): ?>
        <div class="custom-panel" style="margin-bottom:20px;">
            <div class="panel-heading"><i class="fa fa-gavel"></i> Proceso de Contratación / Licitación</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2">
                        <p class="info-label">Tipo</p>
                        <p><?= htmlspecialchars($pry['lic_tipo'] ?: '—') ?></p>
                    </div>
                    <div class="col-md-2">
                        <p class="info-label">Estado</p>
                        <p>
                        <?php
                        $lic_col = ['Inicio' => 'label-default', 'Desarrollo' => 'label-warning', 'Terminado' => 'label-success'];
                        $le = isset($pry['lic_estado']) ? $pry['lic_estado'] : '';
                        $le_clase = isset($lic_col[$le]) ? $lic_col[$le] : 'label-default';
                        ?>
                        <?php if ($le): ?><span class="label <?= $le_clase ?>"><?= htmlspecialchars($le) ?></span><?php else: ?>—<?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <p class="info-label">Monto (M$)</p>
                        <p><?= !empty($pry['lic_monto']) ? number_format($pry['lic_monto'], 0, ',', '.') : '—' ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="info-label">Empresa Adjudicada</p>
                        <p><?= htmlspecialchars($pry['lic_empresa'] ?: '—') ?></p>
                    </div>
                    <div class="col-md-2">
                        <p class="info-label">F. Entrega Estimada</p>
                        <p><?= (!empty($pry['lic_fecha_entrega']) && $pry['lic_fecha_entrega'] !== '0000-00-00') ? date('d/m/Y', strtotime($pry['lic_fecha_entrega'])) : '—' ?></p>
                    </div>
                    <div class="col-md-1">
                        <p class="info-label">Prórroga</p>
                        <p><?= (!empty($pry['lic_fecha_prorroga']) && $pry['lic_fecha_prorroga'] !== '0000-00-00') ? date('d/m/Y', strtotime($pry['lic_fecha_prorroga'])) : '—' ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── MODIFICACIONES PRESUPUESTO ADJUDICADO ── -->
        <div class="custom-panel" style="margin-bottom:20px;">
            <div class="panel-heading" style="display:flex; justify-content:space-between; align-items:center;">
                <span><i class="fa fa-history"></i> Modificaciones de Presupuesto Adjudicado</span>
                <?php if ($_SESSION['permisos']['p_editar']): ?>
                <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalPresupMod">
                    <i class="fa fa-plus"></i> Agregar modificación
                </button>
                <?php endif; ?>
            </div>
            <div class="panel-body" style="padding:0;">
                <?php if (empty($pres_mods)): ?>
                    <p class="text-center text-muted" style="padding:15px; margin:0;"><i class="fa fa-info-circle"></i> Sin modificaciones registradas.</p>
                <?php else: ?>
                <table class="table table-striped table-hover" style="font-size:12px; margin-bottom:0;">
                    <thead>
                        <tr class="active">
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th class="text-right">Monto (M$)</th>
                            <th>Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pres_mods as $pm): ?>
                        <tr>
                            <td><small><?= !empty($pm['fecha']) ? date('d/m/Y', strtotime($pm['fecha'])) : '—' ?></small></td>
                            <td><?= htmlspecialchars($pm['descripcion'] ?: '—') ?></td>
                            <td class="text-right"><strong><?= number_format($pm['monto'], 0, ',', '.') ?></strong></td>
                            <td><small><?= htmlspecialchars($pm['usuario'] ?: '—') ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── RESUMEN ── -->
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-6">
                <div class="well well-sm text-center" style="background:#fff; border-top:3px solid #337ab7;">
                    <div class="info-label">Inversión Total Planificada</div>
                    <div style="font-size:24px; font-weight:bold; color:#337ab7;">
                        $<?= number_format(array_sum(array_column($tareas_db, 'costo_estimado')), 0, ',', '.') ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="well well-sm text-center" style="background:#fff; border-top:3px solid #5cb85c;">
                    <div class="info-label">Valor Ganado (Ejecución Real)</div>
                    <div style="font-size:24px; font-weight:bold; color:#5cb85c;">
                        $<?= number_format($total_ev, 0, ',', '.') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── CURVA S ── -->
        <div class="custom-panel">
            <div class="panel-heading"><i class="fa fa-line-chart"></i> Curva S: Inversión vs Valor Ganado</div>
            <div class="panel-body">
                <canvas id="chartCurvaS" style="max-height:350px;"></canvas>
            </div>
        </div>

        <!-- ── CRONOGRAMA GANTT ── -->
        <div class="custom-panel">
            <div class="panel-heading"><i class="fa fa-tasks"></i> Cronograma Visual</div>
            <div class="gantt-container">
                <div id="gantt-proyecto"></div>
                <div id="gantt-mensaje">
                    <i class="fa fa-info-circle"></i> Sin tareas con fechas asignadas.
                </div>
            </div>
        </div>

        <!-- ── BOTÓN NUEVA TAREA ── -->
        <div class="row" style="margin-bottom:15px;">
            <div class="col-md-12 text-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#modalTarea">
                    <i class="fa fa-plus"></i> Nueva Tarea Financiera
                </button>
            </div>
        </div>

        <!-- ── TABLA DE COSTOS ── -->
        <div class="custom-panel">
            <div class="panel-heading">Desglose de Costos por Actividad</div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="margin-bottom:0;">
                    <thead>
                        <tr class="active">
                            <th>Actividad</th>
                            <th>Costo Estimado</th>
                            <th class="text-center">Avance</th>
                            <th class="text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($tareas_db): foreach ($tareas_db as $t): ?>
                        <tr>
                            <td><?= htmlspecialchars($t['nombre']) ?></td>
                            <td><strong>$<?= number_format($t['costo_estimado'], 0, ',', '.') ?></strong></td>
                            <td class="text-center"><?= (int)$t['progreso_real'] ?>%</td>
                            <td class="text-right">
                                <a href="editar_tarea.php?id=<?= (int)$t['id'] ?>" class="btn btn-xs btn-default">
                                    <i class="fa fa-pencil"></i> Editar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-center text-muted">No hay tareas con costos asignados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── OBSERVACIONES Y RIESGOS ── -->
        <?php
        $crit_colores = ['Alta' => '#e74c3c', 'Media' => '#f39c12', 'Baja' => '#27ae60'];
        $est_colores  = ['Pendiente' => 'label-danger', 'En proceso' => 'label-warning', 'Resuelto' => 'label-success'];
        $obs_pendientes_criticas = 0;
        foreach ($observaciones as $o) {
            if ($o['criticidad'] === 'Alta' && $o['estado'] !== 'Resuelto') $obs_pendientes_criticas++;
        }
        ?>
        <div class="custom-panel" style="margin-bottom:20px;">
            <div class="panel-heading" style="display:flex; justify-content:space-between; align-items:center;">
                <span><i class="fa fa-exclamation-triangle"></i> Observaciones y Riesgos
                    <?php if ($obs_pendientes_criticas > 0): ?>
                        <span class="badge" style="background:#e74c3c; margin-left:6px;"><?= $obs_pendientes_criticas ?> crítica<?= $obs_pendientes_criticas > 1 ? 's' : '' ?></span>
                    <?php endif; ?>
                </span>
                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modalObservacion">
                    <i class="fa fa-plus"></i> Nueva Observación
                </button>
            </div>
            <div class="panel-body" style="padding:0;">
                <?php if (empty($observaciones)): ?>
                    <p class="text-center text-muted" style="padding:20px;"><i class="fa fa-check-circle text-success"></i> Sin observaciones registradas.</p>
                <?php else: ?>
                <table class="table table-hover" style="margin-bottom:0; font-size:13px;">
                    <thead>
                        <tr class="active">
                            <th style="width:90px">Tipo</th>
                            <th>Descripción</th>
                            <th style="width:70px" class="text-center">Criticidad</th>
                            <th style="width:100px">Responsable</th>
                            <th style="width:90px">F. Compromiso</th>
                            <th style="width:90px" class="text-center">Estado</th>
                            <th style="width:80px" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($observaciones as $o):
                        $color_crit = isset($crit_colores[$o['criticidad']]) ? $crit_colores[$o['criticidad']] : '#aaa';
                        $clase_est  = isset($est_colores[$o['estado']]) ? $est_colores[$o['estado']] : 'label-default';
                        $vencida    = ($o['estado'] !== 'Resuelto' && !empty($o['fecha_compromiso']) && $o['fecha_compromiso'] < date('Y-m-d'));
                    ?>
                    <tr <?= $vencida ? 'style="background:#fff5f5;"' : '' ?>>
                        <td><small><?= htmlspecialchars($o['tipo']) ?></small></td>
                        <td>
                            <?= htmlspecialchars($o['descripcion']) ?>
                            <?php if (!empty($o['resolucion'])): ?>
                                <br><small class="text-success"><i class="fa fa-check"></i> <?= htmlspecialchars($o['resolucion']) ?></small>
                            <?php endif; ?>
                            <?php if ($vencida): ?>
                                <br><small class="text-danger"><i class="fa fa-clock-o"></i> Vencida</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span style="display:inline-block; padding:2px 8px; border-radius:3px; color:white; font-size:11px; font-weight:bold; background:<?= $color_crit ?>;">
                                <?= htmlspecialchars($o['criticidad']) ?>
                            </span>
                        </td>
                        <td><small><?= htmlspecialchars($o['responsable'] ?: '—') ?></small></td>
                        <td><small><?= !empty($o['fecha_compromiso']) ? date('d/m/Y', strtotime($o['fecha_compromiso'])) : '—' ?></small></td>
                        <td class="text-center"><span class="label <?= $clase_est ?>"><?= htmlspecialchars($o['estado']) ?></span></td>
                        <td class="text-center">
                            <?php if ($o['estado'] !== 'Resuelto'): ?>
                            <button class="btn btn-xs btn-default"
                                data-toggle="modal" data-target="#modalCambioObs"
                                data-id="<?= $o['id'] ?>"
                                data-estado="<?= htmlspecialchars($o['estado']) ?>"
                                data-desc="<?= htmlspecialchars(substr($o['descripcion'], 0, 60)) ?>">
                                <i class="fa fa-refresh"></i>
                            </button>
                            <?php else: ?>
                            <span class="text-muted"><i class="fa fa-check"></i></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── HISTORIAL DE ESTADOS ── -->
        <?php if (!empty($historial)): ?>
        <div class="custom-panel" style="margin-bottom:20px;">
            <div class="panel-heading"><i class="fa fa-history"></i> Historial de Cambios de Estado</div>
            <div class="panel-body" style="padding: 10px 15px;">
                <div style="position:relative; padding-left: 20px; border-left: 2px solid #ddd;">
                    <?php foreach ($historial as $h): ?>
                    <div style="margin-bottom: 16px; position: relative;">
                        <span style="position:absolute; left:-27px; top:2px; background:#337ab7; border-radius:50%; width:14px; height:14px; display:inline-block; border:2px solid #fff; box-shadow:0 0 0 2px #337ab7;"></span>
                        <div style="background:#f8f9fa; border:1px solid #e8e8e8; border-radius:4px; padding:8px 12px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:6px;">
                                <div>
                                    <?php if ($h['estado_anterior']): ?>
                                        <span class="label label-default"><?= htmlspecialchars($h['estado_anterior']) ?></span>
                                        <i class="fa fa-arrow-right" style="color:#aaa; margin:0 6px; font-size:11px;"></i>
                                    <?php endif; ?>
                                    <span class="label label-primary"><?= htmlspecialchars($h['estado_nuevo']) ?></span>
                                </div>
                                <small class="text-muted">
                                    <i class="fa fa-user"></i> <?= htmlspecialchars($h['usuario']) ?>
                                    &nbsp;&nbsp;
                                    <i class="fa fa-clock-o"></i> <?= date('d/m/Y H:i', strtotime($h['fecha'])) ?>
                                </small>
                            </div>
                            <?php if (!empty($h['comentario'])): ?>
                            <div style="margin-top:5px; color:#555; font-size:12px;">
                                <i class="fa fa-comment-o text-muted"></i> <?= htmlspecialchars($h['comentario']) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- ── MODAL NUEVA TAREA ── -->
<div class="modal fade" id="modalTarea" tabindex="-1">
    <div class="modal-dialog">
        <form action="acciones/guardar_tarea.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Agregar Actividad Financiera</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_proyecto" value="<?= $id ?>">
                    <div class="form-group">
                        <label>Nombre de la Tarea</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Fecha Término</label>
                            <input type="date" name="fecha_fin" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label>Inversión Estimada ($)</label>
                        <input type="number" name="costo_estimado" class="form-control" placeholder="Ej: 5000000">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar Tarea</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL NUEVA OBSERVACIÓN ── -->
<div class="modal fade" id="modalObservacion" tabindex="-1">
    <div class="modal-dialog">
        <form action="acciones/guardar_observacion.php" method="POST">
            <input type="hidden" name="id_proyecto" value="<?= $id ?>">
            <div class="modal-content">
                <div class="modal-header" style="background:#c0392b; color:white; border-radius:4px 4px 0 0;">
                    <button type="button" class="close" data-dismiss="modal" style="color:white;"><span>&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Nueva Observación / Riesgo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control" required>
                                    <option>Observación</option>
                                    <option>Riesgo</option>
                                    <option>Nudo Crítico</option>
                                    <option>Cuello de Botella</option>
                                    <option>Dependencia Externa</option>
                                    <option>Restricción Presupuestaria</option>
                                    <option>Problema de Tramitación</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Criticidad</label>
                                <select name="criticidad" class="form-control" required>
                                    <option value="Alta">Alta</option>
                                    <option value="Media" selected>Media</option>
                                    <option value="Baja">Baja</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descripción *</label>
                        <textarea name="descripcion" class="form-control" rows="3" required placeholder="Describe el problema, riesgo u observación..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Responsable de Resolución</label>
                                <input type="text" name="responsable" class="form-control" placeholder="Nombre o área">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Compromiso Solución</label>
                                <input type="date" name="fecha_compromiso" class="form-control">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="fecha_levantamiento" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> Registrar Observación</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL CAMBIO ESTADO OBSERVACIÓN ── -->
<div class="modal fade" id="modalCambioObs" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="acciones/cambiar_estado_obs.php" method="POST">
            <input type="hidden" name="id_obs" id="cambio_id_obs">
            <input type="hidden" name="id_proyecto" value="<?= $id ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-refresh"></i> Actualizar Estado</h4>
                </div>
                <div class="modal-body">
                    <p id="cambio_desc" class="text-muted" style="font-size:12px; margin-bottom:12px;"></p>
                    <div class="form-group">
                        <label>Nuevo Estado</label>
                        <select name="nuevo_estado" id="cambio_nuevo_estado" class="form-control">
                            <option value="Pendiente">Pendiente</option>
                            <option value="En proceso">En proceso</option>
                            <option value="Resuelto">Resuelto</option>
                        </select>
                    </div>
                    <div class="form-group" id="bloque_resolucion" style="display:none;">
                        <label>Comentario de resolución</label>
                        <textarea name="resolucion" class="form-control" rows="2" placeholder="Describe cómo se resolvió..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL MODIFICACIÓN PRESUPUESTO ── -->
<div class="modal fade" id="modalPresupMod" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="acciones/guardar_presup_mod.php" method="POST">
            <input type="hidden" name="id_proyecto" value="<?= $id ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-history"></i> Agregar Modificación Presupuestaria</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fecha de modificación</label>
                        <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" placeholder="Ej: Ampliación de contrato, modificación de alcance..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Monto (M$) *</label>
                        <input type="number" name="monto" class="form-control" required placeholder="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ── SCRIPTS ── -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- Chart.js sin versión fija = v3+ actual, igual que el código original que funcionaba -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Frappe Gantt — el archivo correcto para @0.6.1 es frappe-gantt.min.js -->
<!-- Si jsdelivr falla, carga desde unpkg como fallback -->
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"
        onerror="this.onerror=null;var s=document.createElement('script');s.src='https://unpkg.com/frappe-gantt@0.6.1/dist/frappe-gantt.min.js';document.head.appendChild(s);"></script>

<script>
    // ── 1. CURVA S — sintaxis Chart.js v3+ ───────────────────────────────
    new Chart(document.getElementById('chartCurvaS'), {
        type: 'line',
        data: {
            labels: <?= json_encode($labels_curva) ?>,
            datasets: [
                { 
                    label: 'Inversión Planificada (PV)', 
                    data: <?= json_encode($data_pv) ?>, 
                    borderColor: '#337ab7', 
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    pointRadius: 4,
                    fill: false,
                    tension: 0.1
                },
                { 
                    label: 'Valor Ganado (EV)', 
                    data: <?= json_encode($data_ev) ?>, 
                    borderColor: '#5cb85c', 
                    backgroundColor: 'rgba(92,184,92,0.1)', 
                    borderWidth: 3,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.1
                }
            ]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            scales: { 
                y: {                          // v3+: "y" en lugar de "yAxes[{}]"
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '$' + value.toLocaleString('de-DE'); }
                    }
                } 
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + context.parsed.y.toLocaleString('de-DE');
                        }
                    }
                }
            }
        }
    });

    // ── 2. CRONOGRAMA GANTT ───────────────────────────────────────────────
    var tasks = <?= json_encode($js_tasks, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    if (tasks.length > 0) {
        try {
            // frappe-gantt expone el constructor como "Gantt" en @0.6.x
            // y como "frappe.Gantt" en algunas builds — detectamos cuál existe
            var GanttConstructor = (typeof Gantt !== 'undefined') ? Gantt
                                 : (typeof frappe !== 'undefined' && frappe.Gantt) ? frappe.Gantt
                                 : null;

            if (!GanttConstructor) {
                throw new Error('La librería Gantt no se cargó correctamente.');
            }

            new GanttConstructor("#gantt-proyecto", tasks, {
                view_mode: 'Month',
                bar_height: 22,
                padding: 18,
                date_format: 'YYYY-MM-DD',
                custom_popup_html: null
            });
        } catch(e) {
            document.getElementById('gantt-proyecto').innerHTML = '';
            document.getElementById('gantt-mensaje').style.display = 'block';
            document.getElementById('gantt-mensaje').innerHTML =
                '<i class="fa fa-exclamation-triangle text-warning"></i> Error al renderizar el cronograma: ' + e.message;
        }
    } else {
        document.getElementById('gantt-mensaje').style.display = 'block';
    }

    // ── 3. MODAL CAMBIO ESTADO OBSERVACIÓN ───────────────────────────────
    $('#modalCambioObs').on('show.bs.modal', function(e) {
        var btn = $(e.relatedTarget);
        $('#cambio_id_obs').val(btn.data('id'));
        $('#cambio_desc').text(btn.data('desc'));
        var estadoActual = btn.data('estado');
        $('#cambio_nuevo_estado').val(estadoActual);
        $('#bloque_resolucion').toggle(estadoActual === 'Resuelto');
    });
    $('#cambio_nuevo_estado').on('change', function() {
        $('#bloque_resolucion').toggle($(this).val() === 'Resuelto');
    });
</script>
</body>
</html>
