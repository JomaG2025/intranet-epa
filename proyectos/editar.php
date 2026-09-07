<?php
// /intranet/proyectos/editar.php
include_once 'header_local.php';
require_once 'config/conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM pry_proyectos WHERE id = ?");
$stmt->execute([$id]);
$pry = $stmt->fetch();
if (!$pry) { die("Proyecto no encontrado."); }

// Encargados actuales
$stmt_enc = $pdo->prepare("SELECT usuario FROM pry_proyectos_encargados WHERE id_proyecto = ?");
$stmt_enc->execute([$id]);
$encargados_array = $stmt_enc->fetchAll(PDO::FETCH_COLUMN);

// Usuarios disponibles para encargados
$usuarios_sistema = $pdo->query("
    SELECT u.usuario FROM pry_usuario u
    INNER JOIN pry_privilegio p ON u.id_privilegio = p.id
    WHERE u.activo = 1 AND p.abrev != 'CON'
    ORDER BY u.usuario
")->fetchAll(PDO::FETCH_COLUMN);

// Filas IDI existentes
$stmt_idi = $pdo->prepare("SELECT * FROM pry_ficha_idi WHERE id_proyecto = ? ORDER BY orden");
$stmt_idi->execute([$id]);
$idi_rows = $stmt_idi->fetchAll();

// Catálogos
$estados    = $pdo->query("SELECT * FROM pry_estados ORDER BY id")->fetchAll();
$tipologias = ['Iniciativa', 'Estudio', 'Programa', 'Proyecto'];
$etapas     = ['Perfil', 'Prefactibilidad', 'Factibilidad', 'Diseño', 'Ejecución', 'Revaluación'];
$rates      = ['SR'  => 'SR — Sin RATE', 'RS' => 'RS — Recomendado Satisfactoriamente',
               'RSA' => 'RSA — RS de Arrastre',
               'FI'  => 'FI — Falta Información', 'OT' => 'OT — Objetado Técnicamente', 'ER' => 'ER — En Revisión'];
$alineaciones_opciones = [
    'Plan de Relicitación',
    'Plan Estratégico',
    'Plan Maestro',
    'PSyVC',
    'Política de Ciberseguridad',
    'Calendario Referencial de Inversiones',
    'Otros instrumentos estratégicos',
    'Vinculación con el medio',
];
$alineaciones_actuales = (isset($pry['alineacion_estrategica']) && $pry['alineacion_estrategica'] !== '')
    ? array_map('trim', explode(',', $pry['alineacion_estrategica'])) : [];
$fuentes_actuales = (isset($pry['fuente_financiamiento']) && $pry['fuente_financiamiento'] !== '')
    ? array_map('trim', explode(',', $pry['fuente_financiamiento'])) : [];

$aprobado = !empty($pry['aprobacion_gds']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar: <?= htmlspecialchars($pry['nombre']) ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        #page-wrapper { margin-left: 250px; padding: 25px; background: white; min-height: 100vh; }
        .page-header { border-bottom: 2px solid #eee; margin-bottom: 20px; font-weight: 800; color: #333; }

        /* Tabs */
        .nav-tabs > li > a { font-weight: bold; font-size: 13px; }
        .nav-tabs > li.active > a { border-top: 3px solid #0d2244; }
        .tab-locked > a { color: #bbb !important; cursor: not-allowed !important; pointer-events: none; }
        .tab-pane-locked { position: relative; }
        .lock-overlay { background: rgba(248,248,248,0.85); position: absolute; top: 0; left: 0; right: 0; bottom: 0;
                        z-index: 10; display: flex; align-items: center; justify-content: center; min-height: 120px; }

        /* Ficha IDI */
        .table-idi th { background: #0d2244; color: white; font-size: 11px; padding: 7px 8px; }
        .table-idi td { padding: 4px 5px; }
        .table-idi td input, .table-idi td select { width: 100%; box-sizing: border-box; }
        .table-idi tfoot td { background: #f5f5f5; font-weight: bold; font-size: 12px; padding: 6px 8px; }

        /* Aprobación */
        .panel-aprobacion-ok  { border-left: 4px solid #5cb85c; }
        .panel-aprobacion-pen { border-left: 4px solid #f0ad4e; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">
        <h1 class="page-header"><i class="fa fa-pencil text-warning"></i> Editar Iniciativa</h1>
        <p class="text-muted" style="margin-top:-10px; margin-bottom:15px; font-size:13px;">
            <strong><?= htmlspecialchars($pry['nombre']) ?></strong>
        </p>

        <!-- ── APROBACIÓN GDS (fuera del form principal para evitar anidamiento) ── -->
        <div class="panel <?= $aprobado ? 'panel-aprobacion-ok' : 'panel-aprobacion-pen' ?> panel-default" style="margin-bottom:15px;">
            <div class="panel-heading"><i class="fa fa-check-circle"></i> Aprobación Financiera GDS</div>
            <div class="panel-body" style="padding:12px 15px;">
                <?php if ($aprobado): ?>
                    <span class="text-success" style="font-size:14px;">
                        <i class="fa fa-check-circle fa-lg"></i>
                        <strong>Aprobado</strong> por <?= htmlspecialchars($pry['aprobacion_gds_usuario']) ?>
                        <?php if (!empty($pry['aprobacion_gds_fecha']) && $pry['aprobacion_gds_fecha'] !== '0000-00-00'): ?>
                            el <?= date('d/m/Y', strtotime($pry['aprobacion_gds_fecha'])) ?>
                        <?php endif; ?>
                    </span>
                    &nbsp;
                    <span class="label label-success"><i class="fa fa-unlock"></i> Módulos 2 y 3 habilitados</span>
                    <?php if ($rango_mostrar === 'ADM'): ?>
                        <form action="acciones/aprobar_gds.php" method="POST" style="display:inline; margin-left:15px;"
                              onsubmit="return confirm('¿Revocar la aprobación? Los módulos 2 y 3 quedarán bloqueados.');">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="hidden" name="accion" value="revocar">
                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Revocar aprobación</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-muted" style="font-size:13px;">
                        <i class="fa fa-clock-o"></i>
                        <strong>Pendiente de aprobación.</strong>
                        Los Módulos 2 y 3 se habilitarán una vez que GDS otorgue la aprobación financiera.
                    </span>
                    &nbsp;
                    <?php if ($rango_mostrar === 'ADM' || $rango_mostrar === 'GER'): ?>
                        <form action="acciones/aprobar_gds.php" method="POST" style="display:inline; margin-left:10px;">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="hidden" name="accion" value="aprobar">
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Otorgar aprobación GDS</button>
                        </form>
                    <?php else: ?>
                        <span class="label label-default"><i class="fa fa-lock"></i> Solo ADM/GER puede aprobar</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── TABS DE MÓDULOS ── -->
        <ul class="nav nav-tabs" id="modulosTabs" style="margin-bottom:0;">
            <li class="active">
                <a href="#tab1" data-toggle="tab"><i class="fa fa-bank"></i> Módulo 1: BIP</a>
            </li>
            <li class="<?= !$aprobado ? 'tab-locked' : '' ?>" id="tabLi2">
                <a href="#tab2" data-toggle="tab">
                    <i class="fa fa-cogs"></i> Módulo 2: Gestión Interna
                    <?php if (!$aprobado): ?><i class="fa fa-lock" style="font-size:11px; margin-left:4px;"></i><?php endif; ?>
                </a>
            </li>
            <li class="<?= !$aprobado ? 'tab-locked' : '' ?>" id="tabLi3">
                <a href="#tab3" data-toggle="tab">
                    <i class="fa fa-bar-chart"></i> Módulo 3: Seguimiento y Avance
                    <?php if (!$aprobado): ?><i class="fa fa-lock" style="font-size:11px; margin-left:4px;"></i><?php endif; ?>
                </a>
            </li>
        </ul>

        <form action="acciones/editar_proyecto.php" method="POST" id="formEditar">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="tab-content" style="border:1px solid #ddd; border-top:none; padding:20px; background:#fff;">

            <!-- ═══════════════════════════════════════════════════════════════ -->
            <!-- TAB 1: MÓDULO 1 — BIP                                          -->
            <!-- ═══════════════════════════════════════════════════════════════ -->
            <div id="tab1" class="tab-pane active">

                <!-- Identificación BIP -->
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-info-circle"></i> Identificación BIP</div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Nombre de la Iniciativa *</label>
                                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($pry['nombre']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Código BIP</label>
                                    <input type="text" name="cod_bip" class="form-control" value="<?= htmlspecialchars($pry['cod_bip']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><strong>Estado RATE</strong></label>
                                    <select name="rate" class="form-control">
                                        <?php foreach ($rates as $val => $label): ?>
                                            <option value="<?= $val ?>" <?= ($pry['rate'] == $val) ? 'selected' : '' ?>><?= $label ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control"
                                           value="<?= (!empty($pry['fecha_inicio']) && $pry['fecha_inicio'] !== '0000-00-00') ? $pry['fecha_inicio'] : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Entrega Estimada</label>
                                    <input type="date" name="fecha_entrega_estimada" class="form-control"
                                           value="<?= (!empty($pry['fecha_entrega_estimada']) && $pry['fecha_entrega_estimada'] !== '0000-00-00') ? $pry['fecha_entrega_estimada'] : '' ?>">
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
                                            <option value="<?= htmlspecialchars($t) ?>" <?= ($pry['tipologia'] == $t) ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
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
                                            <option value="<?= htmlspecialchars($et) ?>" <?= ($pry['etapa_postulacion'] == $et) ? 'selected' : '' ?>><?= htmlspecialchars($et) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Encargado de Formulación</label>
                                    <select name="encargado_formulacion" class="form-control">
                                        <option value="">— Seleccionar —</option>
                                        <?php foreach ($usuarios_sistema as $usr): ?>
                                            <option value="<?= htmlspecialchars($usr) ?>"
                                                <?= (isset($pry['encargado_formulacion']) && $pry['encargado_formulacion'] === $usr) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($usr) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Presupuesto IP (M$)</label>
                                    <input type="number" name="presupuesto_ip" class="form-control"
                                           value="<?= (int)(isset($pry['presupuesto_ip']) ? $pry['presupuesto_ip'] : 0) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Descripción / Objetivo</label>
                            <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($pry['descripcion']) ?></textarea>
                        </div>

                    </div>
                </div>

                <!-- Ficha IDI — Multi-período -->
                <?php
                $idi_anio_p_def = isset($pry['idi_anio_pagado']) && $pry['idi_anio_pagado'] ? (int)$pry['idi_anio_pagado'] : 2025;
                $idi_anio_s_def = isset($pry['idi_anio_solic'])  && $pry['idi_anio_solic']  ? (int)$pry['idi_anio_solic']  : 2026;
                $idi_json = json_encode(array_values($idi_rows));
                ?>
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

                <div class="text-right" style="margin-bottom:10px;">
                    <a href="ficha.php?id=<?= $id ?>" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-warning" style="color:white; font-weight:bold;">
                        <i class="fa fa-save"></i> Guardar Módulo 1
                    </button>
                </div>

            </div><!-- /tab1 -->

            <!-- ═══════════════════════════════════════════════════════════════ -->
            <!-- TAB 2: MÓDULO 2 — GESTIÓN INTERNA                              -->
            <!-- ═══════════════════════════════════════════════════════════════ -->
            <div id="tab2" class="tab-pane tab-pane-locked">
                <?php if (!$aprobado): ?>
                <div class="lock-overlay">
                    <div class="text-center text-muted">
                        <i class="fa fa-lock fa-3x" style="margin-bottom:12px; color:#ccc;"></i>
                        <p style="font-size:14px;"><strong>Módulo bloqueado</strong></p>
                        <p style="font-size:12px;">Requiere aprobación financiera GDS (Módulo 1)</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Estrategia y Organización -->
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-sitemap"></i> Estrategia y Organización</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Gerencia</label>
                                    <select name="codigo_gerencia" class="form-control" <?= !$aprobado ? 'disabled' : '' ?>>
                                        <option value="">— Sin asignar —</option>
                                        <?php foreach (['GDS', 'GCL'] as $ger): ?>
                                            <option value="<?= $ger ?>" <?= (isset($pry['codigo_gerencia']) && $pry['codigo_gerencia'] === $ger) ? 'selected' : '' ?>><?= $ger ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Clasificación</label>
                                    <select name="clasificacion" class="form-control" <?= !$aprobado ? 'disabled' : '' ?>>
                                        <option value="">— Seleccionar —</option>
                                        <?php foreach ([
                                            'Gestión Ambiental', 'Gestión de Normalización',
                                            'Infraestructura Marítimo-Portuaria', 'Integración Tecnológica',
                                            'Vinculación Territorial', 'Zona de Extensión y Apoyo',
                                        ] as $cls): ?>
                                            <option value="<?= htmlspecialchars($cls) ?>"
                                                <?= (isset($pry['clasificacion']) && $pry['clasificacion'] === $cls) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cls) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Prioridad Estratégica</label>
                                    <select name="prioridad_estrategica" class="form-control" <?= !$aprobado ? 'disabled' : '' ?>>
                                        <option value="">— Seleccionar —</option>
                                        <?php foreach (['Baja', 'Media', 'Alta'] as $pri): ?>
                                            <option value="<?= $pri ?>" <?= (isset($pry['prioridad_estrategica']) && $pry['prioridad_estrategica'] === $pri) ? 'selected' : '' ?>><?= $pri ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fuente de Financiamiento</label>
                                    <div style="border:1px solid #ccc; border-radius:4px; padding:8px 12px; background:#fff;">
                                        <?php foreach (['Pedze', 'Propio', 'Sectorial'] as $fte): ?>
                                            <div class="checkbox" style="margin:3px 0;">
                                                <label>
                                                    <input type="checkbox" name="fuente_financiamiento[]"
                                                           value="<?= htmlspecialchars($fte) ?>"
                                                           <?= in_array($fte, $fuentes_actuales) ? 'checked' : '' ?>
                                                           <?= !$aprobado ? 'disabled' : '' ?>>
                                                    <?= htmlspecialchars($fte) ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alineación Estratégica <small class="text-muted">(puede seleccionar más de uno)</small></label>
                            <div style="border:1px solid #ccc; border-radius:4px; padding:10px 15px; background:#fff; column-count:2; column-gap:20px;">
                                <?php foreach ($alineaciones_opciones as $aln): ?>
                                    <div class="checkbox" style="margin:4px 0; break-inside:avoid;">
                                        <label>
                                            <input type="checkbox" name="alineacion_estrategica[]"
                                                   value="<?= htmlspecialchars($aln) ?>"
                                                   <?= in_array($aln, $alineaciones_actuales) ? 'checked' : '' ?>
                                                   <?= !$aprobado ? 'disabled' : '' ?>>
                                            <?= htmlspecialchars($aln) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Encargado del Proyecto</label>
                            <?php if ($aprobado && ($rango_mostrar === 'ADM' || $rango_mostrar === 'GER')): ?>
                                <div style="border:1px solid #ccc; border-radius:4px; padding:10px 15px; background:#fff; column-count:2; column-gap:20px;">
                                    <?php foreach ($usuarios_sistema as $usr): ?>
                                        <div class="checkbox" style="margin:4px 0; break-inside:avoid;">
                                            <label>
                                                <input type="checkbox" name="encargados[]"
                                                       value="<?= htmlspecialchars($usr) ?>"
                                                       <?= in_array($usr, $encargados_array) ? 'checked' : '' ?>>
                                                <?= htmlspecialchars($usr) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if (empty($usuarios_sistema)): ?>
                                        <span class="text-muted">No hay usuarios registrados.</span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div style="padding:6px 0;">
                                    <?php if (empty($encargados_array)): ?>
                                        <span class="text-muted"><em>Sin encargados asignados.</em></span>
                                    <?php else: ?>
                                        <?php foreach ($encargados_array as $enc): ?>
                                            <span class="label label-info" style="font-size:13px; margin-right:6px; margin-bottom:4px; padding:5px 10px; display:inline-block;"><?= htmlspecialchars($enc) ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($aprobado): ?>
                                    <?php foreach ($encargados_array as $enc): ?>
                                        <input type="hidden" name="encargados[]" value="<?= htmlspecialchars($enc) ?>">
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <!-- Solicitud IP -->
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-file-text-o"></i> Solicitud de Identificación Presupuestaria (IP)</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>N° / Referencia de Solicitud IP</label>
                                    <input type="text" name="solicitud_ip" class="form-control"
                                           value="<?= htmlspecialchars(isset($pry['solicitud_ip']) ? $pry['solicitud_ip'] : '') ?>"
                                           placeholder="Ej: IP-2026-045"
                                           <?= !$aprobado ? 'disabled' : '' ?>>
                                    <p class="help-block" style="font-size:11px;">Número o referencia de la Identificación Presupuestaria asociada a esta iniciativa.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($aprobado): ?>
                <div class="text-right" style="margin-bottom:10px;">
                    <a href="ficha.php?id=<?= $id ?>" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-warning" style="color:white; font-weight:bold;">
                        <i class="fa fa-save"></i> Guardar Módulos 1 y 2
                    </button>
                </div>
                <?php endif; ?>

            </div><!-- /tab2 -->

            <!-- ═══════════════════════════════════════════════════════════════ -->
            <!-- TAB 3: MÓDULO 3 — SEGUIMIENTO Y AVANCE                         -->
            <!-- ═══════════════════════════════════════════════════════════════ -->
            <div id="tab3" class="tab-pane tab-pane-locked">
                <?php if (!$aprobado): ?>
                <div class="lock-overlay">
                    <div class="text-center text-muted">
                        <i class="fa fa-lock fa-3x" style="margin-bottom:12px; color:#ccc;"></i>
                        <p style="font-size:14px;"><strong>Módulo bloqueado</strong></p>
                        <p style="font-size:12px;">Requiere aprobación financiera GDS (Módulo 1)</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Avance General -->
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-bar-chart"></i> Avance General</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado Actual</label>
                                    <select name="id_estado" id="sel_estado" class="form-control" <?= !$aprobado ? 'disabled' : '' ?>>
                                        <?php foreach ($estados as $e): ?>
                                            <option value="<?= $e['id'] ?>" <?= ($pry['id_estado'] == $e['id']) ? 'selected' : '' ?>><?= htmlspecialchars($e['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>% Ejecución Física</label>
                                    <input type="number" name="avance" class="form-control"
                                           value="<?= (int)$pry['porcentaje_ejecucion'] ?>" min="0" max="100"
                                           <?= !$aprobado ? 'disabled' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div id="bloque_comentario" style="display:none;">
                            <div class="form-group">
                                <label><i class="fa fa-comment-o"></i> Motivo del cambio de estado <small class="text-muted">(opcional)</small></label>
                                <textarea name="comentario_estado" id="comentario_estado" class="form-control" rows="2" placeholder="Ej: Se adjudicó contrato el 10/05/2026..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Proceso de Contratación -->
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-gavel"></i> Proceso de Contratación / Licitación</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo de Licitación</label>
                                    <select name="lic_tipo" class="form-control" <?= !$aprobado ? 'disabled' : '' ?>>
                                        <option value="">— Sin asignar —</option>
                                        <?php foreach (['Pública', 'Privada'] as $lt): ?>
                                            <option value="<?= $lt ?>" <?= (isset($pry['lic_tipo']) && $pry['lic_tipo'] === $lt) ? 'selected' : '' ?>><?= $lt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Estado de la Licitación</label>
                                    <select name="lic_estado" class="form-control" <?= !$aprobado ? 'disabled' : '' ?>>
                                        <option value="">— Sin asignar —</option>
                                        <?php foreach (['Inicio', 'Desarrollo', 'Terminado'] as $le): ?>
                                            <option value="<?= $le ?>" <?= (isset($pry['lic_estado']) && $pry['lic_estado'] === $le) ? 'selected' : '' ?>><?= $le ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Monto Licitación (M$)</label>
                                    <input type="number" name="lic_monto" class="form-control"
                                           value="<?= isset($pry['lic_monto']) ? (float)$pry['lic_monto'] : '' ?>"
                                           placeholder="0" <?= !$aprobado ? 'disabled' : '' ?>>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Empresa Adjudicada</label>
                                    <input type="text" name="lic_empresa" class="form-control"
                                           value="<?= htmlspecialchars(isset($pry['lic_empresa']) ? $pry['lic_empresa'] : '') ?>"
                                           placeholder="Nombre de la empresa" <?= !$aprobado ? 'disabled' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Estimada de Entrega</label>
                                    <input type="date" name="lic_fecha_entrega" class="form-control"
                                           value="<?= (!empty($pry['lic_fecha_entrega']) && $pry['lic_fecha_entrega'] !== '0000-00-00') ? $pry['lic_fecha_entrega'] : '' ?>"
                                           <?= !$aprobado ? 'disabled' : '' ?>>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha de Prórroga</label>
                                    <input type="date" name="lic_fecha_prorroga" class="form-control"
                                           value="<?= (!empty($pry['lic_fecha_prorroga']) && $pry['lic_fecha_prorroga'] !== '0000-00-00') ? $pry['lic_fecha_prorroga'] : '' ?>"
                                           <?= !$aprobado ? 'disabled' : '' ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Presupuesto Adjudicado -->
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-dollar"></i> Presupuesto Adjudicado</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Presupuesto Adjudicado (M$)</label>
                                    <input type="number" name="presupuesto_adjudicado" class="form-control"
                                           value="<?= (int)(isset($pry['presupuesto_adjudicado']) ? $pry['presupuesto_adjudicado'] : 0) ?>"
                                           <?= !$aprobado ? 'disabled' : '' ?>>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Presupuesto Ejecutado (M$)</label>
                                    <input type="number" name="presupuesto_ejecutado" class="form-control"
                                           value="<?= (int)(isset($pry['presupuesto_ejecutado']) ? $pry['presupuesto_ejecutado'] : 0) ?>"
                                           <?= !$aprobado ? 'disabled' : '' ?>>
                                </div>
                            </div>
                        </div>
                        <?php if ($aprobado): ?>
                        <p class="text-muted" style="font-size:12px; margin-bottom:0;">
                            <i class="fa fa-info-circle"></i>
                            Para registrar modificaciones presupuestarias históricas, use el botón "Agregar modificación" en la <a href="ficha.php?id=<?= $id ?>">ficha del proyecto</a>.
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Observaciones Generales -->
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-comment"></i> Observaciones Generales</div>
                    <div class="panel-body">
                        <textarea name="observaciones" class="form-control" rows="3" <?= !$aprobado ? 'disabled' : '' ?>><?= htmlspecialchars(isset($pry['observaciones']) ? $pry['observaciones'] : '') ?></textarea>
                    </div>
                </div>

                <?php if ($aprobado): ?>
                <div class="text-right" style="margin-bottom:10px;">
                    <a href="ficha.php?id=<?= $id ?>" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-warning" style="color:white; font-weight:bold;">
                        <i class="fa fa-save"></i> Guardar todos los módulos
                    </button>
                </div>
                <?php endif; ?>

            </div><!-- /tab3 -->

        </div><!-- /tab-content -->
        </form>

    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
var aprobado = <?= $aprobado ? 'true' : 'false' ?>;

// ── Bloqueo de tabs ───────────────────────────────────────────────────────
$('#modulosTabs a[data-toggle="tab"]').on('click', function(e) {
    var target = $(this).attr('href');
    if (!aprobado && (target === '#tab2' || target === '#tab3')) {
        e.preventDefault();
        e.stopPropagation();
        alert('Este módulo requiere aprobación financiera de GDS.\nComplete el Módulo 1 y solicite la aprobación antes de continuar.');
    }
});

// ── Historial de estado ───────────────────────────────────────────────────
var estadoOriginal = <?= (int)$pry['id_estado'] ?>;
if (document.getElementById('sel_estado')) {
    document.getElementById('sel_estado').addEventListener('change', function() {
        var bloque = document.getElementById('bloque_comentario');
        bloque.style.display = (parseInt(this.value) !== estadoOriginal) ? 'block' : 'none';
        if (parseInt(this.value) === estadoOriginal) {
            document.getElementById('comentario_estado').value = '';
        }
    });
}

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
    anioP = anioP || <?= $idi_anio_p_def ?>;
    anioS = anioS || <?= $idi_anio_s_def ?>;
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
    var ap = $('#' + tid).find('.sel-anio-p').val() || <?= $idi_anio_p_def ?>;
    var as = $('#' + tid).find('.sel-anio-s').val() || <?= $idi_anio_s_def ?>;
    data = data || {};
    var monM = (!data.moneda || data.moneda === 'M$') ? ' selected' : '';
    var monU = (data.moneda === 'US$') ? ' selected' : '';
    var monF = (data.moneda === 'UF')  ? ' selected' : '';
    var fila =
        '<tr>' +
        '<td><input type="text" name="idi_fuente[]" class="form-control input-sm" value="' + (data.fuente || '') + '"></td>' +
        '<td><input type="text" name="idi_asignacion[]" class="form-control input-sm" value="' + (data.asignacion || '') + '"></td>' +
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

// Inicializar datos IDI desde BD
$(document).ready(function() {
    var idiData = <?= $idi_json ?>;
    if (idiData && idiData.length) {
        var grupos = {}, orden = [];
        $.each(idiData, function(i, row) {
            var ap = row.anio_pagado || <?= $idi_anio_p_def ?>;
            var as = row.anio_solic  || <?= $idi_anio_s_def ?>;
            var key = ap + '_' + as;
            if (!grupos[key]) { grupos[key] = {ap: ap, as: as, filas: []}; orden.push(key); }
            grupos[key].filas.push(row);
        });
        $.each(orden, function(i, key) {
            var g = grupos[key];
            agregarPeriodoIDI(g.ap, g.as, g.filas);
        });
    } else {
        agregarPeriodoIDI(<?= $idi_anio_p_def ?>, <?= $idi_anio_s_def ?>);
    }

    // Si vino con tab=2 o tab=3 en URL y está aprobado, activar ese tab
    var hash = window.location.hash;
    if (aprobado && (hash === '#tab2' || hash === '#tab3')) {
        $('#modulosTabs a[href="' + hash + '"]').tab('show');
    }
});
</script>
</body>
</html>
