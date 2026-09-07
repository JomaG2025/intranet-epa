<?php
// /intranet/proyectos/gantt_cartera.php
include_once 'header_local.php'; 
require_once 'config/conexion.php';

// 1. Obtener proyectos para el filtro
$todos_los_proyectos = $pdo->query("SELECT id, nombre, cod_bip FROM pry_proyectos ORDER BY nombre ASC")->fetchAll();

$proyectos_filtro = isset($_GET['proyectos']) ? $_GET['proyectos'] : [];
$gerencia_filtro = isset($_GET['gerencia']) ? trim($_GET['gerencia']) : '';

$filtro_aplicado = isset($_GET['proyectos']) || isset($_GET['gerencia']);
$hay_filtro_activo = !empty($proyectos_filtro) || !empty($gerencia_filtro);

$tareas = [];

if ($filtro_aplicado && $hay_filtro_activo) {
    $where = " WHERE t.eliminado = 0 ";
    $params = [];

    if (!empty($proyectos_filtro)) {
        $ids = implode(',', array_map('intval', $proyectos_filtro));
        $where .= " AND p.id IN ($ids) ";
    }
    if (!empty($gerencia_filtro)) {
        $where .= " AND p.codigo_gerencia = ? ";
        $params[] = $gerencia_filtro;
    }

    $sql = "SELECT t.*, p.nombre AS proyecto_nombre, p.cod_bip 
            FROM pry_tareas t 
            INNER JOIN pry_proyectos p ON t.id_proyecto = p.id 
            $where 
            ORDER BY p.nombre, t.fecha_inicio ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $tareas = $stmt->fetchAll();
}

// 3. Lógica de Agrupamiento por Bloques
$js_tasks = [];
$grouped_labels = [];
$current_project_id = null;

foreach($tareas as $t) {
    if(!empty($t['fecha_inicio']) && $t['fecha_inicio'] !== '0000-00-00') {
        $js_tasks[] = [
            'id' => 'T-' . $t['id'],
            'name' => htmlspecialchars($t['nombre']),
            'start' => $t['fecha_inicio'],
            'end' => $t['fecha_fin'],
            'progress' => (int)$t['progreso_real']
        ];

        if ($t['id_proyecto'] !== $current_project_id) {
            $grouped_labels[] = [
                'label' => "[" . ($t['cod_bip'] ?: 'S/N') . "] " . htmlspecialchars($t['proyecto_nombre']),
                'task_count' => 1
            ];
            $current_project_id = $t['id_proyecto'];
        } else {
            $grouped_labels[count($grouped_labels) - 1]['task_count']++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gantt Consolidado - EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .page-header { border-bottom: 2px solid #eee; margin-bottom: 20px; font-weight: 800; color: #333; }
        .filter-panel { background: #fff; padding: 20px; border-radius: 4px; border: 1px solid #ddd; margin-bottom: 20px; }
        
        .gantt-wrapper { display: flex; background: #fff; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
        .gantt-labels-column { width: 280px; flex-shrink: 0; background: #f4f6f9; border-right: 1px solid #d1d3e2; padding-top: 60px; }
        
        /* Bloque Rectangular */
        .project-group-block { 
            display: flex; align-items: center; justify-content: center; 
            padding: 10px; border: 1px solid #d1d3e2; border-left: 5px solid #337ab7;
            background: #ffffff; text-align: center; font-size: 11px; color: #337ab7; 
            font-weight: bold; line-height: 1.3; box-sizing: border-box;
        }

        .gantt-chart-column { flex-grow: 1; overflow-x: auto; }
        .gantt .bar-progress { fill: #337ab7 !important; }
        .gantt .bar { fill: #a5c2de !important; }
    </style>
</head>
<body>

<div id="wrapper">
    <div id="page-wrapper">
        <h1 class="page-header"><i class="fa fa-tasks text-primary"></i> Carta Gantt Consolidada</h1>

        <div class="filter-panel">
            <form method="GET" action="gantt_cartera.php" id="filtroForm">
                <div class="row">

                    <!-- SELECTOR DE PROYECTOS -->
                    <div class="col-md-6">
                        <label>
                            Proyectos
                            <span id="badge-sel" class="badge" style="background:#4c6ef5; display:none; margin-left:6px;"></span>
                        </label>
                        <div style="border:1.5px solid #e2e8f0; border-radius:8px; background:#fff; overflow:hidden;">
                            <!-- Buscador -->
                            <div style="padding:7px 8px; border-bottom:1px solid #f1f5f9; background:#fafbff;">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon" style="background:#fff; border:none; padding-right:2px;">
                                        <i class="fa fa-search" style="color:#94a3b8;"></i>
                                    </span>
                                    <input type="text" id="buscar-proy" class="form-control"
                                           placeholder="Buscar proyecto..."
                                           style="border:none; box-shadow:none; background:#fff;">
                                </div>
                            </div>
                            <!-- Acciones rápidas -->
                            <div style="padding:4px 12px; border-bottom:1px solid #f1f5f9; font-size:11px;">
                                <a href="#" id="sel-todos" style="color:#4c6ef5;">Seleccionar todos</a>
                                <span style="color:#ddd; margin:0 6px;">|</span>
                                <a href="#" id="sel-ninguno" style="color:#94a3b8;">Limpiar</a>
                            </div>
                            <!-- Lista con scroll -->
                            <div id="lista-proyectos" style="max-height:195px; overflow-y:auto; padding:6px 10px;">
                                <?php foreach($todos_los_proyectos as $proy): ?>
                                <div class="proy-item" style="margin:2px 0;">
                                    <label style="font-weight:400 !important; cursor:pointer; display:flex; align-items:flex-start; gap:7px; margin:0; padding:3px 0;">
                                        <input type="checkbox" name="proyectos[]" value="<?= $proy['id'] ?>"
                                               <?= in_array($proy['id'], $proyectos_filtro) ? 'checked' : '' ?>
                                               style="margin-top:2px; flex-shrink:0;">
                                        <span>
                                            <span style="font-size:10px; color:#94a3b8; font-weight:600;">[<?= htmlspecialchars($proy['cod_bip'] ?: 'S/N') ?>]</span>
                                            <span style="color:#1e293b; font-size:12px;"><?= htmlspecialchars($proy['nombre']) ?></span>
                                        </span>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                                <div id="sin-resultados" style="display:none; color:#94a3b8; text-align:center; padding:12px; font-size:12px;">
                                    <i class="fa fa-search"></i> Sin resultados.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GERENCIA + BOTONES -->
                    <div class="col-md-3">
                        <label>Gerencia:</label>
                        <select name="gerencia" class="form-control">
                            <option value="">-- Todas --</option>
                            <option value="GDS" <?= $gerencia_filtro == 'GDS' ? 'selected' : '' ?>>GDS</option>
                            <option value="GCL" <?= $gerencia_filtro == 'GCL' ? 'selected' : '' ?>>GCL</option>
                        </select>
                    </div>
                    <div class="col-md-3" style="display:flex; flex-direction:column; justify-content:flex-end; padding-bottom:2px;">
                        <button type="submit" class="btn btn-primary btn-block" style="margin-bottom:8px;">
                            <i class="fa fa-filter"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-default btn-block" id="btnLimpiar">
                            <i class="fa fa-times"></i> Limpiar Todo
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <div class="gantt-wrapper">
            <div class="gantt-labels-column">
                <?php if ($filtro_aplicado && $hay_filtro_activo): ?>
                <?php foreach($grouped_labels as $gp): 
                    $block_height = $gp['task_count'] * 38; 
                ?>
                    <div class="project-group-block" style="height: <?= $block_height ?>px;">
                        <span><?= $gp['label'] ?></span>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="gantt-chart-column">
                <?php if (!$filtro_aplicado || !$hay_filtro_activo): ?>
                    <div style="padding: 60px 40px; text-align: center; color: #aaa;">
                        <i class="fa fa-filter" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                        <p style="font-size: 16px;">Seleccione un proyecto o gerencia y haga clic en <strong>Filtrar</strong> para visualizar el Gantt.</p>
                    </div>
                <?php else: ?>
                <div id="gantt-consolidado"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

<script>
$(document).ready(function() {

    // Limpiar Todo
    $('#btnLimpiar').on('click', function() {
        window.location.href = 'gantt_cartera.php';
    });

    // Badge contador de proyectos seleccionados
    function actualizarBadge() {
        var n = $('.proy-item input:checked').length;
        if (n > 0) {
            $('#badge-sel').text(n + ' seleccionado' + (n !== 1 ? 's' : '')).show();
        } else {
            $('#badge-sel').hide();
        }
    }
    $('.proy-item input').on('change', actualizarBadge);
    actualizarBadge();

    // Buscador
    $('#buscar-proy').on('input', function() {
        var q = $(this).val().toLowerCase();
        var hayResultados = false;
        $('.proy-item').each(function() {
            var match = $(this).text().toLowerCase().indexOf(q) > -1;
            $(this).toggle(match);
            if (match) hayResultados = true;
        });
        $('#sin-resultados').toggle(!hayResultados);
    });

    // Seleccionar todos (solo visibles)
    $('#sel-todos').on('click', function(e) {
        e.preventDefault();
        $('.proy-item:visible input').prop('checked', true);
        actualizarBadge();
    });

    // Limpiar selección
    $('#sel-ninguno').on('click', function(e) {
        e.preventDefault();
        $('.proy-item input').prop('checked', false);
        actualizarBadge();
    });

    const tasks = <?= json_encode($js_tasks) ?>;
    if(tasks.length > 0) {
        new Gantt("#gantt-consolidado", tasks, {
            view_mode: 'Month',
            bar_height: 25,
            padding: 13,
            language: 'es', // Meses en español
            date_format: 'YYYY-MM-DD',
            custom_popup_html: function(task) {
                return `<div style="padding: 10px; width: 180px;"><strong>${task.name}</strong><br><small>Avance: ${task.progress}%</small></div>`;
            }
        });
    }
});
</script>
</body>
</html>