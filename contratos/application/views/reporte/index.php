			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-bar-chart"></i> Reporte de Gestión</h4>
			</div>
            <div class="navbar-action hidden-xs">
                <small class="text-muted" style="margin-right:10px;"><?= date('d/m/Y'); ?></small>
				<button onclick="window.print();" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</button>
			</div>

            <!-- KPI cards -->
            <div class="row" style="margin-bottom:6px;">
                <div class="col-xs-6 col-sm-3">
                    <div class="panel panel-default text-center" style="border-top:3px solid #5bc0de;margin-bottom:8px;">
                        <div class="panel-body" style="padding:12px 8px;">
                            <div style="font-size:32px;font-weight:700;line-height:1;"><?= $total; ?></div>
                            <small class="text-muted">Total contratos</small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="panel panel-default text-center" style="border-top:3px solid #5cb85c;margin-bottom:8px;">
                        <div class="panel-body" style="padding:12px 8px;">
                            <div style="font-size:32px;font-weight:700;line-height:1;"><?= $por_estado['ING']; ?></div>
                            <small class="text-success">Vigentes</small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="panel panel-default text-center" style="border-top:3px solid #f0ad4e;margin-bottom:8px;">
                        <div class="panel-body" style="padding:12px 8px;">
                            <div style="font-size:32px;font-weight:700;line-height:1;"><?= $por_estado['ALE']; ?></div>
                            <small class="text-warning">Por vencer</small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="panel panel-default text-center" style="border-top:3px solid #d9534f;margin-bottom:8px;">
                        <div class="panel-body" style="padding:12px 8px;">
                            <div style="font-size:32px;font-weight:700;line-height:1;"><?= $por_estado['VEN']; ?></div>
                            <small class="text-danger">Vencidos</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monto comprometido + vencen este mes -->
            <div class="panel panel-default" style="margin-bottom:12px;">
                <div class="panel-body" style="padding:8px 15px;">
                    <?php foreach ($montos as $m): ?>
                    <span style="margin-right:24px;">
                        <i class="fa fa-money text-muted"></i>
                        <strong><?= $m['moneda']; ?> <?= number_format($m['total'], 0, ',', '.'); ?></strong>
                        <small class="text-muted">comprometido en <?= $m['contratos']; ?> contratos</small>
                    </span>
                    <?php endforeach; ?>
                    <span>
                        <i class="fa fa-calendar-times-o text-muted"></i>
                        <strong><?= $vencen_mes; ?></strong>
                        <small class="text-muted">vencen este mes</small>
                    </span>
                </div>
            </div>

            <!-- Charts row 1: donut estado + bar proveedores -->
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong><i class="fa fa-pie-chart"></i> Distribución por estado</strong></div>
                        <div class="panel-body text-center">
                            <canvas id="chart-estado" height="220"></canvas>
                            <div style="margin-top:10px;font-size:12px;">
                                <span style="margin-right:10px;"><span style="display:inline-block;width:12px;height:12px;background:#5cb85c;border-radius:2px;"></span> Vigentes</span>
                                <span style="margin-right:10px;"><span style="display:inline-block;width:12px;height:12px;background:#f0ad4e;border-radius:2px;"></span> Por vencer</span>
                                <span><span style="display:inline-block;width:12px;height:12px;background:#d9534f;border-radius:2px;"></span> Vencidos</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong><i class="fa fa-bar-chart"></i> Top proveedores por n&uacute;mero de contratos</strong></div>
                        <div class="panel-body">
                            <canvas id="chart-prov" height="<?= max(120, count($prov_labels) * 22); ?>"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts row 2: vencimientos por mes -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong><i class="fa fa-calendar"></i> Contratos que vencen — pr&oacute;ximos 12 meses</strong></div>
                        <div class="panel-body">
                            <canvas id="chart-venc" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla top proveedores -->
            <?php if (!empty($top_proveedores)): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong><i class="fa fa-list"></i> Detalle top proveedores</strong></div>
                        <div class="panel-body" style="padding:0;">
                            <table class="table table-condensed table-striped table-hover" style="margin-bottom:0;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Raz&oacute;n Social</th>
                                        <th class="text-center">Contratos</th>
                                        <th class="text-right">Participaci&oacute;n</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($top_proveedores as $i => $p): ?>
                                    <tr>
                                        <td><?= $i + 1; ?></td>
                                        <td><?= htmlspecialchars($p['razon']); ?></td>
                                        <td class="text-center"><?= $p['total']; ?></td>
                                        <td class="text-right">
                                            <?php $pct = ($total > 0) ? round($p['total'] / $total * 100, 1) : 0; ?>
                                            <div class="progress" style="margin:0;height:16px;min-width:80px;">
                                                <div class="progress-bar" style="width:<?= $pct; ?>%;min-width:28px;font-size:11px;line-height:16px;"><?= $pct; ?>%</div>
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
            <?php endif; ?>

<style>
@media print {
    .navbar-action, .list-group { display:none; }
    .panel { border:1px solid #ccc !important; page-break-inside:avoid; }
    canvas { max-width:100% !important; }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
(function(){
    var estadosData   = [<?= (int)$por_estado['ING']; ?>, <?= (int)$por_estado['ALE']; ?>, <?= (int)$por_estado['VEN']; ?>];
    var estadosColors = ['#5cb85c','#f0ad4e','#d9534f'];

    new Chart(document.getElementById('chart-estado').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Vigentes','Por vencer','Vencidos'],
            datasets: [{ data: estadosData, backgroundColor: estadosColors, borderWidth: 1 }]
        },
        options: {
            legend: { display: false },
            cutoutPercentage: 55,
            tooltips: {
                callbacks: {
                    label: function(item, data) {
                        var val = data.datasets[0].data[item.index];
                        var total = data.datasets[0].data.reduce(function(a,b){ return a+b; }, 0);
                        var pct = total > 0 ? Math.round(val/total*100) : 0;
                        return data.labels[item.index] + ': ' + val + ' (' + pct + '%)';
                    }
                }
            }
        }
    });

    var provLabels = <?= json_encode($prov_labels); ?>;
    var provData   = <?= json_encode($prov_data); ?>;

    new Chart(document.getElementById('chart-prov').getContext('2d'), {
        type: 'horizontalBar',
        data: {
            labels: provLabels,
            datasets: [{
                label: 'Contratos',
                data: provData,
                backgroundColor: 'rgba(51,122,183,0.7)',
                borderColor: 'rgba(51,122,183,1)',
                borderWidth: 1
            }]
        },
        options: {
            legend: { display: false },
            scales: {
                xAxes: [{ ticks: { beginAtZero: true, precision: 0 } }],
                yAxes: [{ ticks: { fontSize: 11 } }]
            }
        }
    });

    var vencLabels = <?= json_encode($venc_labels); ?>;
    var vencData   = <?= json_encode($venc_data); ?>;

    new Chart(document.getElementById('chart-venc').getContext('2d'), {
        type: 'bar',
        data: {
            labels: vencLabels,
            datasets: [{
                label: 'Contratos que vencen',
                data: vencData,
                backgroundColor: 'rgba(240,173,78,0.7)',
                borderColor: 'rgba(240,173,78,1)',
                borderWidth: 1
            }]
        },
        options: {
            legend: { display: false },
            scales: {
                yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
            }
        }
    });
})();
</script>
