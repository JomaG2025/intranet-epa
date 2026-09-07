<?php
// /intranet/proyectos/nuevo.php
include_once 'header_local.php';
require_once 'config/conexion.php';

$estados    = $pdo->query("SELECT id, nombre FROM pry_estados ORDER BY id")->fetchAll();
$tipologias = ['Iniciativa', 'Estudio', 'Programa', 'Proyecto'];
$etapas     = ['Perfil', 'Prefactibilidad', 'Factibilidad', 'Diseño', 'Ejecución', 'Revaluación'];
$rates      = ['SR'  => 'SR — Sin RATE', 'RS' => 'RS — Recomendado Satisfactoriamente',
               'RSA' => 'RSA — RS de Arrastre',
               'FI'  => 'FI — Falta Información', 'OT' => 'OT — Objetado Técnicamente', 'ER' => 'ER — En Revisión'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Iniciativa BIP - EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .page-header { border-bottom: 2px solid #eee; margin-bottom: 20px; font-weight: 800; color: #333; }
        .badge-modulo { background: #0d2244; color: white; padding: 5px 14px; border-radius: 3px;
                        font-size: 12px; font-weight: bold; display: inline-block; margin-bottom: 15px; }
        .table-idi th { background: #0d2244; color: white; font-size: 11px; padding: 7px 8px; }
        .table-idi td { padding: 4px 5px; }
        .table-idi td input, .table-idi td select { width: 100%; box-sizing: border-box; }
        .table-idi tfoot td { background: #f5f5f5; font-weight: bold; font-size: 12px; padding: 6px 8px; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">
        <h1 class="page-header"><i class="fa fa-plus-circle text-primary"></i> Registrar Nueva Iniciativa</h1>
        <span class="badge-modulo"><i class="fa fa-bank"></i> MÓDULO 1: Banco Integrado de Proyectos (BIP)</span>
        <p class="text-muted" style="font-size:12px; margin-bottom:18px;">
            Complete los datos BIP. Los módulos de Gestión Interna y Seguimiento se habilitarán tras la aprobación financiera de GDS.
        </p>

        <form action="acciones/guardar_proyecto.php" method="POST">

            <!-- IDENTIFICACIÓN BIP -->
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-info-circle"></i> Identificación BIP</div>
                <div class="panel-body">

                    <!-- RATE como primer campo visible -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Estado RATE</strong> <small class="text-muted">(primer campo visible)</small></label>
                                <select name="rate" class="form-control">
                                    <?php foreach ($rates as $val => $label): ?>
                                        <option value="<?= $val ?>"><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estado Inicial</label>
                                <select name="id_estado" class="form-control">
                                    <?php foreach ($estados as $e): ?>
                                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>% Avance Inicial</label>
                                <input type="number" name="avance" class="form-control" value="0" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nombre de la Iniciativa *</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Código BIP</label>
                                <input type="text" name="cod_bip" class="form-control" placeholder="Ej: 40073852">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipología</label>
                                <select name="tipologia" class="form-control">
                                    <option value="">— Seleccionar —</option>
                                    <?php foreach ($tipologias as $t): ?>
                                        <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Etapa</label>
                                <select name="etapa_postulacion" class="form-control">
                                    <option value="">— Seleccionar —</option>
                                    <?php foreach ($etapas as $et): ?>
                                        <option value="<?= htmlspecialchars($et) ?>"><?= htmlspecialchars($et) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Encargado de Formulación</label>
                                <input type="text" name="encargado_formulacion" class="form-control" placeholder="Responsable de la formulación BIP">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Entrega Estimada</label>
                                <input type="date" name="fecha_entrega_estimada" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Presupuesto IP (M$)</label>
                                <input type="number" name="presupuesto_ip" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción / Objetivo</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el objetivo de la iniciativa..."></textarea>
                    </div>

                </div>
            </div>

            <!-- FICHA IDI — Multi-período -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-table"></i> Ficha IDI — Solicitud de Financiamiento
                    <small class="text-muted" style="font-weight:normal;">&nbsp;Cada período tiene sus propios años y fuentes</small>
                </div>
                <div class="panel-body" style="padding:10px 12px;">
                    <div id="contenedorIDI"></div>
                    <button type="button" class="btn btn-primary btn-sm" onclick="agregarPeriodoIDI()" style="margin-top:4px;">
                        <i class="fa fa-plus"></i> Agregar período
                    </button>
                </div>
            </div>

            <div class="text-right" style="margin-bottom: 30px;">
                <a href="index.php" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar Iniciativa BIP</button>
            </div>

        </form>
    </div>
</div>
<?php include_once 'footer_local.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
// ── Ficha IDI — Multi-período ─────────────────────────────────────────────
var _idxIDI = 0;

function _optsAnio(sel, desde, hasta) {
    var s = '';
    for (var y = desde; y <= hasta; y++) {
        s += '<option value="' + y + '"' + (y == sel ? ' selected' : '') + ' style="color:#000;background:#fff;">' + y + '</option>';
    }
    return s;
}

function agregarPeriodoIDI(anioP, anioS, filas) {
    anioP = anioP || 2025;
    anioS = anioS || 2026;
    _idxIDI++;
    var tid = 'idi_' + _idxIDI;
    var html =
        '<div class="idi-bloque" id="bloque_' + tid + '" style="margin-bottom:12px;border:1px solid #ddd;border-radius:4px;overflow:hidden;">' +
        '<div class="table-responsive">' +
        '<table class="table table-bordered table-idi" id="' + tid + '" style="margin:0;font-size:12px;">' +
        '<thead><tr>' +
        '<th style="width:120px;">Fuente</th>' +
        '<th>Asignación Presupuestaria (Ítem)</th>' +
        '<th style="width:70px;">Moneda</th>' +
        '<th style="width:140px;">Pagado al 31/12/' +
            '<select class="sel-anio-p" onchange="_actualizarAniosIDI(\'' + tid + '\')" style="background:transparent;border:1px solid rgba(255,255,255,0.5);color:white;font-size:11px;padding:1px 2px;border-radius:3px;cursor:pointer;">' +
            _optsAnio(anioP, 2023, 2030) + '</select></th>' +
        '<th style="width:130px;">Solicitado año ' +
            '<select class="sel-anio-s" onchange="_actualizarAniosIDI(\'' + tid + '\')" style="background:transparent;border:1px solid rgba(255,255,255,0.5);color:white;font-size:11px;padding:1px 2px;border-radius:3px;cursor:pointer;">' +
            _optsAnio(anioS, 2024, 2032) + '</select></th>' +
        '<th style="width:110px;">Solicitado años sig.</th>' +
        '<th style="width:110px;">Costo Total</th>' +
        '<th style="width:36px;"></th>' +
        '</tr></thead>' +
        '<tbody id="body_' + tid + '"></tbody>' +
        '<tfoot><tr>' +
        '<td colspan="3"><strong>Total</strong></td>' +
        '<td class="tot-p">0</td><td class="tot-s">0</td><td class="tot-ss">0</td><td class="tot-c">0</td>' +
        '<td></td></tr></tfoot>' +
        '</table></div>' +
        '<div style="padding:6px 10px;background:#f9f9f9;display:flex;gap:8px;align-items:center;">' +
        '<button type="button" class="btn btn-default btn-xs" onclick="_nuevaFilaIDI(\'' + tid + '\')"><i class="fa fa-plus"></i> Agregar fila</button>' +
        '<button type="button" class="btn btn-danger btn-xs" onclick="_eliminarPeriodoIDI(\'' + tid + '\')" style="margin-left:auto;"><i class="fa fa-trash"></i> Eliminar período</button>' +
        '</div></div>';
    $('#contenedorIDI').append(html);
    if (filas && filas.length) {
        $.each(filas, function(i, f) { _nuevaFilaIDI(tid, f); });
    } else {
        _nuevaFilaIDI(tid);
    }
}

function _actualizarAniosIDI(tid) {
    var ap = $('#' + tid).find('.sel-anio-p').val();
    var as = $('#' + tid).find('.sel-anio-s').val();
    $('#body_' + tid + ' .h-anio-p').val(ap);
    $('#body_' + tid + ' .h-anio-s').val(as);
}

function _nuevaFilaIDI(tid, data) {
    var ap = $('#' + tid).find('.sel-anio-p').val() || 2025;
    var as = $('#' + tid).find('.sel-anio-s').val() || 2026;
    data = data || {};
    var monM = (!data.moneda || data.moneda === 'M$') ? ' selected' : '';
    var monU = (data.moneda === 'US$') ? ' selected' : '';
    var monF = (data.moneda === 'UF')  ? ' selected' : '';
    var fila =
        '<tr>' +
        '<td><input type="text" name="idi_fuente[]" class="form-control input-sm" value="' + (data.fuente || '') + '" placeholder="Ej: Propio"></td>' +
        '<td><input type="text" name="idi_asignacion[]" class="form-control input-sm" value="' + (data.asignacion || '') + '" placeholder="Ítem presupuestario"></td>' +
        '<td><select name="idi_moneda[]" class="form-control input-sm"><option' + monM + '>M$</option><option' + monU + '>US$</option><option' + monF + '>UF</option></select></td>' +
        '<td><input type="number" name="idi_pagado[]"    class="form-control input-sm idi-num" step="1" value="' + (data.pagado_2025 || 0) + '" min="0"></td>' +
        '<td><input type="number" name="idi_solic2026[]" class="form-control input-sm idi-num" step="1" value="' + (data.solic_2026 || 0)  + '" min="0"></td>' +
        '<td><input type="number" name="idi_solicSig[]"  class="form-control input-sm idi-num" step="1" value="' + (data.solic_sig || 0)   + '" min="0"></td>' +
        '<td><input type="number" name="idi_costo[]"     class="form-control input-sm" step="1" value="' + (data.costo_total || 0) + '" readonly style="background:#f9f9f9;"></td>' +
        '<td class="text-center">' +
            '<input type="hidden" name="idi_anio_pagado[]" class="h-anio-p" value="' + ap + '">' +
            '<input type="hidden" name="idi_anio_solic[]"  class="h-anio-s" value="' + as + '">' +
            '<button type="button" class="btn btn-danger btn-xs" onclick="_eliminarFilaIDI(this,\'' + tid + '\')"><i class="fa fa-times"></i></button>' +
        '</td>' +
        '</tr>';
    var $body = $('#body_' + tid);
    $body.append(fila);
    $body.find('tr:last .idi-num').on('input', function() { _recalcularFilaIDI($(this).closest('tr'), tid); });
    _recalcularFilaIDI($body.find('tr:last'), tid);
}

function _eliminarFilaIDI(btn, tid) { $(btn).closest('tr').remove(); _calcularTotalesIDI(tid); }

function _eliminarPeriodoIDI(tid) {
    if (confirm('¿Eliminar este período y todas sus filas?')) { $('#bloque_' + tid).remove(); }
}

function _recalcularFilaIDI(fila, tid) {
    var p  = parseFloat(fila.find('[name="idi_pagado[]"]').val())    || 0;
    var s2 = parseFloat(fila.find('[name="idi_solic2026[]"]').val()) || 0;
    var ss = parseFloat(fila.find('[name="idi_solicSig[]"]').val())  || 0;
    fila.find('[name="idi_costo[]"]').val(Math.round(p + s2 + ss));
    _calcularTotalesIDI(tid);
}

function _calcularTotalesIDI(tid) {
    var totP=0, totS2=0, totSs=0, totC=0;
    $('#body_' + tid + ' tr').each(function() {
        totP  += parseFloat($(this).find('[name="idi_pagado[]"]').val())    || 0;
        totS2 += parseFloat($(this).find('[name="idi_solic2026[]"]').val()) || 0;
        totSs += parseFloat($(this).find('[name="idi_solicSig[]"]').val())  || 0;
        totC  += parseFloat($(this).find('[name="idi_costo[]"]').val())     || 0;
    });
    var $b = $('#bloque_' + tid);
    $b.find('.tot-p').text(Math.round(totP));
    $b.find('.tot-s').text(Math.round(totS2));
    $b.find('.tot-ss').text(Math.round(totSs));
    $b.find('.tot-c').text(Math.round(totC));
}

// Iniciar con un período vacío
agregarPeriodoIDI(2025, 2026);
</script>
</body>
</html>
