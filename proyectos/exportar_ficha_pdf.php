<?php
// /intranet/proyectos/exportar_ficha_pdf.php — Vista imprimible de proyecto individual
require_once 'config/conexion.php';
require_once 'config/helpers.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { die("ID no válido."); }

$stmt = $pdo->prepare("SELECT p.*, e.nombre AS estado_nombre FROM pry_proyectos p LEFT JOIN pry_estados e ON p.id_estado = e.id WHERE p.id = ?");
$stmt->execute([$id]);
$pry = $stmt->fetch();
if (!$pry) { die("Proyecto no encontrado."); }

$stmt_enc = $pdo->prepare("SELECT usuario FROM pry_proyectos_encargados WHERE id_proyecto = ?");
$stmt_enc->execute([$id]);
$encargados = implode(', ', $stmt_enc->fetchAll(PDO::FETCH_COLUMN));

$stmt_t = $pdo->prepare("SELECT * FROM pry_tareas WHERE id_proyecto = ? AND eliminado = 0 ORDER BY fecha_inicio ASC");
$stmt_t->execute([$id]);
$tareas = $stmt_t->fetchAll();

$total_estimado = array_sum(array_column($tareas, 'costo_estimado'));
$total_ev = 0;
foreach ($tareas as $t) {
    $total_ev += (float)$t['costo_estimado'] * ((int)$t['progreso_real'] / 100);
}

$pres_adj = isset($pry['presupuesto_adjudicado']) ? (float)$pry['presupuesto_adjudicado'] : 0;
$pres_eje = isset($pry['presupuesto_ejecutado'])  ? (float)$pry['presupuesto_ejecutado']  : 0;
$pres_ip  = isset($pry['presupuesto_ip'])         ? (float)$pry['presupuesto_ip']         : 0;
$pct_eje  = $pres_adj > 0 ? round($pres_eje / $pres_adj * 100, 1) : (int)$pry['porcentaje_ejecucion'];

$rate_colors = [
    'RS' => '#27ae60', 'FI' => '#f39c12',
    'OT' => '#e74c3c', 'ER' => '#2980b9', 'SR' => '#95a5a6'
];
$rate_color = isset($rate_colors[$pry['rate']]) ? $rate_colors[$pry['rate']] : '#95a5a6';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha: <?= htmlspecialchars($pry['nombre']) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; background: #fff; }

        /* Pantalla */
        .no-print { background: #2c3e50; padding: 12px 20px; display: flex; align-items: center; gap: 10px; }
        .no-print button { background: #27ae60; color: white; border: none; padding: 8px 20px; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; }
        .no-print a { color: #aaa; text-decoration: none; font-size: 13px; }
        .no-print a:hover { color: white; }
        .no-print span { color: #555; }

        .contenido { padding: 20px 25px; max-width: 900px; margin: 0 auto; }

        /* Header del reporte */
        .reporte-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 3px solid #2c3e50; }
        .reporte-titulo h1 { font-size: 16px; color: #2c3e50; font-weight: bold; line-height: 1.3; }
        .reporte-titulo p { color: #888; font-size: 10px; margin-top: 4px; }
        .badge-rate { display: inline-block; padding: 4px 14px; border-radius: 4px; color: white; font-weight: bold; font-size: 14px; }

        /* Sección */
        .seccion { margin-bottom: 16px; }
        .seccion-titulo { background: #2c3e50; color: white; padding: 5px 10px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px 20px; }
        .grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 8px 20px; }
        .campo { }
        .campo .label { font-size: 9px; color: #888; text-transform: uppercase; font-weight: bold; margin-bottom: 2px; }
        .campo .valor { font-size: 11px; color: #333; }
        .campo .valor-grande { font-size: 16px; font-weight: bold; color: #2c3e50; }

        /* Tabla de tareas */
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        thead tr { background: #34495e; color: white; }
        thead th { padding: 5px 7px; text-align: left; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody td { padding: 5px 7px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Barra de progreso */
        .progress-wrap { background: #eee; border-radius: 3px; height: 8px; margin-top: 4px; }
        .progress-bar { height: 8px; border-radius: 3px; background: #27ae60; }

        /* Pie */
        .pie { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; color: #999; font-size: 9px; display: flex; justify-content: space-between; }

        /* Impresión */
        @media print {
            .no-print { display: none !important; }
            .contenido { padding: 10px; max-width: 100%; }
            body { font-size: 9px; }
            .seccion-titulo { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-rate { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 1.5cm; size: A4; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">&#128438; Imprimir / Guardar como PDF</button>
    <span>|</span>
    <a href="ficha.php?id=<?= $id ?>">&#8592; Volver a la ficha</a>
    <span>|</span>
    <a href="exportar_excel.php">&#128196; Exportar cartera completa a Excel</a>
</div>

<div class="contenido">

    <!-- Encabezado -->
    <div class="reporte-header">
        <div class="reporte-titulo">
            <h1><?= htmlspecialchars($pry['nombre']) ?></h1>
            <p>
                <?php if ($pry['cod_bip']): ?>BIP: <?= htmlspecialchars($pry['cod_bip']) ?> &mdash; <?php endif; ?>
                Ficha generada el <?= date('d/m/Y \a \l\a\s H:i') ?>
            </p>
        </div>
        <div>
            <span class="badge-rate" style="background:<?= $rate_color ?>;"><?= htmlspecialchars($pry['rate'] ?: 'SR') ?></span>
            <br><span style="font-size:10px; color:#888; margin-top:4px; display:block; text-align:center;">RATE</span>
        </div>
    </div>

    <!-- Identificación -->
    <div class="seccion">
        <div class="seccion-titulo">Identificación del Proyecto</div>
        <div class="grid-4">
            <div class="campo"><div class="label">Tipología</div><div class="valor"><?= htmlspecialchars($pry['tipologia'] ?: '—') ?></div></div>
            <div class="campo"><div class="label">Etapa Postulación</div><div class="valor"><?= htmlspecialchars($pry['etapa_postulacion'] ?: '—') ?></div></div>
            <div class="campo"><div class="label">Estado</div><div class="valor"><?= htmlspecialchars($pry['estado_nombre'] ?: '—') ?></div></div>
            <div class="campo"><div class="label">Clasificación</div><div class="valor"><?= htmlspecialchars($pry['clasificacion'] ?: '—') ?></div></div>
        </div>
    </div>

    <!-- Estrategia -->
    <div class="seccion">
        <div class="seccion-titulo">Estrategia y Organización</div>
        <div class="grid-2" style="margin-bottom:8px;">
            <div class="campo"><div class="label">Prioridad Estratégica</div><div class="valor"><?= htmlspecialchars($pry['prioridad_estrategica'] ?: '—') ?></div></div>
            <div class="campo"><div class="label">Fuente de Financiamiento</div><div class="valor"><?= htmlspecialchars($pry['fuente_financiamiento'] ?: '—') ?></div></div>
        </div>
        <div class="grid-2">
            <div class="campo"><div class="label">Alineación Estratégica</div><div class="valor"><?= htmlspecialchars($pry['alineacion_estrategica'] ?: '—') ?></div></div>
            <div class="campo"><div class="label">Gerencia Responsable</div><div class="valor"><?= htmlspecialchars($pry['codigo_gerencia'] ?: '—') ?></div></div>
        </div>
        <?php if (!empty($encargados)): ?>
        <div class="campo" style="margin-top:8px;"><div class="label">Encargados</div><div class="valor"><?= htmlspecialchars($encargados) ?></div></div>
        <?php endif; ?>
    </div>

    <!-- Presupuesto -->
    <div class="seccion">
        <div class="seccion-titulo">Presupuesto y Avance</div>
        <div class="grid-4">
            <div class="campo">
                <div class="label">Presupuesto IP</div>
                <div class="valor-grande">$<?= number_format($pres_ip, 0, ',', '.') ?></div>
            </div>
            <div class="campo">
                <div class="label">Presupuesto Adjudicado</div>
                <div class="valor-grande" style="color:#2980b9;">$<?= number_format($pres_adj, 0, ',', '.') ?></div>
            </div>
            <div class="campo">
                <div class="label">Presupuesto Ejecutado</div>
                <div class="valor-grande" style="color:#27ae60;">$<?= number_format($pres_eje, 0, ',', '.') ?></div>
            </div>
            <div class="campo">
                <div class="label">% Ejecución Presupuestaria</div>
                <div class="valor-grande" style="color:<?= $pct_eje >= 80 ? '#27ae60' : ($pct_eje >= 40 ? '#f39c12' : '#e74c3c') ?>;"><?= $pct_eje ?>%</div>
                <?php if ($pres_adj > 0): ?>
                <div class="progress-wrap"><div class="progress-bar" style="width:<?= min($pct_eje, 100) ?>%; background:<?= $pct_eje >= 80 ? '#27ae60' : ($pct_eje >= 40 ? '#f39c12' : '#e74c3c') ?>;"></div></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="grid-2" style="margin-top:10px;">
            <div class="campo">
                <div class="label">Fecha Inicio</div>
                <div class="valor"><?= (!empty($pry['fecha_inicio']) && $pry['fecha_inicio'] !== '0000-00-00') ? $pry['fecha_inicio'] : '—' ?></div>
            </div>
            <div class="campo">
                <div class="label">Fecha Entrega Estimada</div>
                <div class="valor"><?= (!empty($pry['fecha_entrega_estimada']) && $pry['fecha_entrega_estimada'] !== '0000-00-00') ? $pry['fecha_entrega_estimada'] : '—' ?></div>
            </div>
        </div>
    </div>

    <?php if (!empty($pry['descripcion'])): ?>
    <div class="seccion">
        <div class="seccion-titulo">Descripción</div>
        <p style="font-size:11px; line-height:1.5;"><?= nl2br(htmlspecialchars($pry['descripcion'])) ?></p>
    </div>
    <?php endif; ?>

    <?php if (!empty($pry['observaciones'])): ?>
    <div class="seccion">
        <div class="seccion-titulo">Observaciones</div>
        <p style="font-size:11px; color:#c0392b; line-height:1.5;"><?= nl2br(htmlspecialchars($pry['observaciones'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- Actividades -->
    <?php if (!empty($tareas)): ?>
    <div class="seccion">
        <div class="seccion-titulo">Actividades / Tareas (<?= count($tareas) ?>)</div>
        <table>
            <thead>
                <tr>
                    <th>Actividad</th>
                    <th style="width:80px">Inicio</th>
                    <th style="width:80px">Término</th>
                    <th style="width:100px" class="text-right">Costo Estimado</th>
                    <th style="width:60px" class="text-center">Avance</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tareas as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['nombre']) ?></td>
                    <td><?= ($t['fecha_inicio'] && $t['fecha_inicio'] !== '0000-00-00') ? $t['fecha_inicio'] : '—' ?></td>
                    <td><?= ($t['fecha_fin']    && $t['fecha_fin']    !== '0000-00-00') ? $t['fecha_fin']    : '—' ?></td>
                    <td class="text-right">$<?= number_format($t['costo_estimado'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= (int)$t['progreso_real'] ?>%</td>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; background:#eaf2ff;">
                    <td colspan="3">TOTAL</td>
                    <td class="text-right">$<?= number_format($total_estimado, 0, ',', '.') ?></td>
                    <td class="text-center">—</td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="pie">
        <span>Sistema de Gestión de Proyectos EPA &mdash; Documento confidencial</span>
        <span>Generado: <?= date('d/m/Y H:i') ?></span>
    </div>

</div>
</body>
</html>
