<?php
// /intranet/proyectos/exportar_pdf.php — Vista imprimible de la cartera
require_once 'config/conexion.php';

$sql = "SELECT p.*, e.nombre AS estado_nombre,
               GROUP_CONCAT(pe.usuario ORDER BY pe.usuario SEPARATOR ', ') AS encargados
        FROM pry_proyectos p
        LEFT JOIN pry_estados e ON p.id_estado = e.id
        LEFT JOIN pry_proyectos_encargados pe ON pe.id_proyecto = p.id
        WHERE 1=1
        GROUP BY p.id
        ORDER BY p.id DESC";
$proyectos = $pdo->query($sql)->fetchAll();

$total     = count($proyectos);
$total_adj = array_sum(array_column($proyectos, 'presupuesto_adjudicado'));
$total_eje = array_sum(array_column($proyectos, 'presupuesto_ejecutado'));
$pct_global = $total_adj > 0 ? round($total_eje / $total_adj * 100, 1) : 0;

$rate_colors = [
    'RS' => '#27ae60', 'FI' => '#f39c12',
    'OT' => '#e74c3c', 'ER' => '#2980b9', 'SR' => '#95a5a6'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartera de Proyectos EPA — <?= date('d/m/Y') ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; background: #fff; }

        /* Pantalla */
        .no-print { background: #2c3e50; padding: 12px 20px; display: flex; align-items: center; gap: 10px; }
        .no-print button { background: #27ae60; color: white; border: none; padding: 8px 20px; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; }
        .no-print button:hover { background: #219a52; }
        .no-print span { color: #ccc; font-size: 13px; }
        .no-print a { color: #aaa; text-decoration: none; font-size: 13px; }
        .no-print a:hover { color: white; }

        .contenido { padding: 20px 25px; }

        /* Encabezado del reporte */
        .reporte-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 3px solid #2c3e50; padding-bottom: 15px; }
        .reporte-titulo h1 { font-size: 20px; color: #2c3e50; font-weight: bold; }
        .reporte-titulo p { color: #666; font-size: 12px; margin-top: 4px; }
        .reporte-logo { text-align: right; color: #2c3e50; font-weight: bold; font-size: 16px; }

        /* Resumen KPIs */
        .kpis { display: flex; gap: 15px; margin-bottom: 20px; }
        .kpi { flex: 1; border: 1px solid #ddd; border-radius: 4px; padding: 10px 14px; border-top: 3px solid #2c3e50; }
        .kpi .kpi-valor { font-size: 22px; font-weight: bold; color: #2c3e50; }
        .kpi .kpi-label { font-size: 10px; color: #888; text-transform: uppercase; margin-top: 2px; }

        /* Tabla */
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        thead tr { background: #2c3e50; color: white; }
        thead th { padding: 7px 6px; text-align: left; font-size: 10px; font-weight: bold; white-space: nowrap; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody tr:hover { background: #e8f4fd; }
        tbody td { padding: 6px; border-bottom: 1px solid #eee; vertical-align: top; }
        .badge-rate { display: inline-block; padding: 2px 7px; border-radius: 3px; color: white; font-weight: bold; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .muted { color: #aaa; }
        .nombre-col { max-width: 220px; }

        /* Pie de página */
        .pie { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; color: #999; font-size: 10px; display: flex; justify-content: space-between; }

        /* Impresión */
        @media print {
            .no-print { display: none !important; }
            body { font-size: 9px; }
            .contenido { padding: 10px; }
            .kpi .kpi-valor { font-size: 16px; }
            thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-rate { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 1.5cm; size: A4 landscape; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">&#128438; Imprimir / Guardar como PDF</button>
    <span>|</span>
    <a href="exportar_excel.php">&#128196; Exportar a Excel</a>
    <span>|</span>
    <a href="index.php">&#8592; Volver a la cartera</a>
</div>

<div class="contenido">

    <div class="reporte-header">
        <div class="reporte-titulo">
            <h1>Cartera de Proyectos Estratégicos</h1>
            <p>Empresa Portuaria Arica &mdash; Reporte generado el <?= date('d/m/Y \a \l\a\s H:i') ?></p>
        </div>
        <div class="reporte-logo">EPA<br><span style="font-size:11px; font-weight:normal; color:#666;">Puerto Arica</span></div>
    </div>

    <!-- KPIs -->
    <div class="kpis">
        <div class="kpi">
            <div class="kpi-valor"><?= $total ?></div>
            <div class="kpi-label">Total Iniciativas</div>
        </div>
        <div class="kpi">
            <div class="kpi-valor">$<?= number_format($total_adj, 0, ',', '.') ?></div>
            <div class="kpi-label">Presupuesto Adjudicado Total</div>
        </div>
        <div class="kpi">
            <div class="kpi-valor">$<?= number_format($total_eje, 0, ',', '.') ?></div>
            <div class="kpi-label">Presupuesto Ejecutado Total</div>
        </div>
        <div class="kpi">
            <div class="kpi-valor"><?= $pct_global ?>%</div>
            <div class="kpi-label">Ejecución Global</div>
        </div>
    </div>

    <!-- Tabla -->
    <table>
        <thead>
            <tr>
                <th style="width:25px">N°</th>
                <th style="width:75px">BIP</th>
                <th>Nombre de la Iniciativa</th>
                <th style="width:70px">Tipología</th>
                <th style="width:80px">Estado</th>
                <th style="width:35px" class="text-center">RATE</th>
                <th style="width:80px">Gerencia</th>
                <th style="width:90px" class="text-right">Presup. Adj.</th>
                <th style="width:90px" class="text-right">Presup. Ejec.</th>
                <th style="width:45px" class="text-center">% Ejec.</th>
            </tr>
        </thead>
        <tbody>
            <?php $n = 1; foreach ($proyectos as $p):
                $color = isset($rate_colors[$p['rate']]) ? $rate_colors[$p['rate']] : '#95a5a6';
                $pct   = (int)$p['porcentaje_ejecucion'];
            ?>
            <tr>
                <td class="text-center muted"><?= $n++ ?></td>
                <td class="muted"><?= htmlspecialchars($p['cod_bip'] ?: '—') ?></td>
                <td class="nombre-col"><strong><?= htmlspecialchars($p['nombre']) ?></strong>
                    <?php if (!empty($p['encargados'])): ?>
                        <br><span class="muted"><?= htmlspecialchars($p['encargados']) ?></span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['tipologia'] ?: '—') ?></td>
                <td><?= htmlspecialchars($p['estado_nombre'] ?: '—') ?></td>
                <td class="text-center">
                    <span class="badge-rate" style="background:<?= $color ?>;"><?= htmlspecialchars($p['rate'] ?: 'SR') ?></span>
                </td>
                <td><?= htmlspecialchars($p['codigo_gerencia'] ?: '—') ?></td>
                <td class="text-right">
                    <?php if ($p['presupuesto_adjudicado'] > 0): ?>
                        $<?= number_format($p['presupuesto_adjudicado'], 0, ',', '.') ?>
                    <?php else: ?>
                        <span class="muted">—</span>
                    <?php endif; ?>
                </td>
                <td class="text-right">
                    <?php if ($p['presupuesto_ejecutado'] > 0): ?>
                        $<?= number_format($p['presupuesto_ejecutado'], 0, ',', '.') ?>
                    <?php else: ?>
                        <span class="muted">—</span>
                    <?php endif; ?>
                </td>
                <td class="text-center"><?= $pct ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pie">
        <span>Sistema de Gestión de Proyectos EPA &mdash; Confidencial</span>
        <span>Total: <?= $total ?> iniciativas &mdash; <?= date('d/m/Y H:i') ?></span>
    </div>

</div>
</body>
</html>
