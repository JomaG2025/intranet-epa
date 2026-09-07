<?php
// /intranet/proyectos/acciones/editar_proyecto.php
$path_header = dirname(__DIR__) . '/header_local.php';
include_once $path_header;
require_once dirname(__DIR__) . '/config/helpers.php';

if ($usuario_sesion === 'Invitado') {
    die("Acceso denegado: No tiene permisos para editar proyectos.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        $id_pry = intval($_POST['id']);

        // ── Leer datos actuales (para comparación y control de aprobación) ──
        $stmt_actual = $pdo->prepare("
            SELECT p.*, e.nombre AS estado_nombre
            FROM pry_proyectos p
            LEFT JOIN pry_estados e ON p.id_estado = e.id
            WHERE p.id = ?
        ");
        $stmt_actual->execute([$id_pry]);
        $actual = $stmt_actual->fetch();

        $aprobado = !empty($actual['aprobacion_gds']);

        // ── MÓDULO 1: Siempre se guarda ────────────────────────────────────
        $pdo->prepare("UPDATE pry_proyectos SET
                nombre               = ?,
                cod_bip              = ?,
                descripcion          = ?,
                tipologia            = ?,
                etapa_postulacion    = ?,
                encargado_formulacion = ?,
                rate                 = ?,
                fecha_inicio         = ?,
                fecha_entrega_estimada = ?,
                presupuesto_ip       = ?,
                idi_anio_pagado      = ?,
                idi_anio_solic       = ?,
                fecha_actualizacion  = NOW()
            WHERE id = ?")
            ->execute([
                $_POST['nombre'],
                isset($_POST['cod_bip'])              ? (trim($_POST['cod_bip'])    ?: null) : null,
                isset($_POST['descripcion'])           ? (trim($_POST['descripcion']) ?: null) : null,
                isset($_POST['tipologia'])             ? (trim($_POST['tipologia'])  ?: null) : null,
                isset($_POST['etapa_postulacion'])     ? (trim($_POST['etapa_postulacion']) ?: null) : null,
                isset($_POST['encargado_formulacion']) ? (trim($_POST['encargado_formulacion']) ?: null) : null,
                $_POST['rate'],
                (isset($_POST['fecha_inicio'])           && $_POST['fecha_inicio']           !== '') ? $_POST['fecha_inicio']           : null,
                (isset($_POST['fecha_entrega_estimada']) && $_POST['fecha_entrega_estimada'] !== '') ? $_POST['fecha_entrega_estimada'] : null,
                (isset($_POST['presupuesto_ip'])         && $_POST['presupuesto_ip']         !== '') ? intval($_POST['presupuesto_ip']) : null,
                isset($_POST['idi_anio_pagado']) ? intval($_POST['idi_anio_pagado']) : 2025,
                isset($_POST['idi_anio_solic'])  ? intval($_POST['idi_anio_solic'])  : 2026,
                $id_pry,
            ]);

        // ── FICHA IDI: siempre se reemplaza ───────────────────────────────
        $pdo->prepare("DELETE FROM pry_ficha_idi WHERE id_proyecto = ?")->execute([$id_pry]);
        if (!empty($_POST['idi_fuente']) && is_array($_POST['idi_fuente'])) {
            $stmt_idi = $pdo->prepare("INSERT INTO pry_ficha_idi
                (id_proyecto, fuente, asignacion, moneda, pagado_2025, solic_2026, solic_sig, costo_total, anio_pagado, anio_solic, orden)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($_POST['idi_fuente'] as $k => $fuente) {
                $fuente_v = trim($fuente);
                $asig_v   = isset($_POST['idi_asignacion'][$k]) ? trim($_POST['idi_asignacion'][$k]) : null;
                if ($fuente_v === '' && $asig_v === null) continue;
                $stmt_idi->execute([
                    $id_pry,
                    $fuente_v ?: null,
                    $asig_v,
                    isset($_POST['idi_moneda'][$k])      ? trim($_POST['idi_moneda'][$k])         : 'M$',
                    isset($_POST['idi_pagado'][$k])      ? (float)$_POST['idi_pagado'][$k]        : 0,
                    isset($_POST['idi_solic2026'][$k])   ? (float)$_POST['idi_solic2026'][$k]     : 0,
                    isset($_POST['idi_solicSig'][$k])    ? (float)$_POST['idi_solicSig'][$k]      : 0,
                    isset($_POST['idi_costo'][$k])       ? (float)$_POST['idi_costo'][$k]         : 0,
                    isset($_POST['idi_anio_pagado'][$k]) ? intval($_POST['idi_anio_pagado'][$k])   : 2025,
                    isset($_POST['idi_anio_solic'][$k])  ? intval($_POST['idi_anio_solic'][$k])    : 2026,
                    $k,
                ]);
            }
        }

        // ── MÓDULOS 2 Y 3: solo si aprobación GDS activa ──────────────────
        if ($aprobado) {

            // Módulo 2 — Gestión Interna
            $nuevo_estado = intval($_POST['id_estado']);
            $alineacion   = (!empty($_POST['alineacion_estrategica']) && is_array($_POST['alineacion_estrategica']))
                            ? implode(', ', $_POST['alineacion_estrategica']) : null;
            $fuente_fin   = (!empty($_POST['fuente_financiamiento']) && is_array($_POST['fuente_financiamiento']))
                            ? implode(', ', $_POST['fuente_financiamiento']) : null;

            $pdo->prepare("UPDATE pry_proyectos SET
                    codigo_gerencia        = ?,
                    clasificacion          = ?,
                    prioridad_estrategica  = ?,
                    alineacion_estrategica = ?,
                    fuente_financiamiento  = ?,
                    solicitud_ip           = ?
                WHERE id = ?")
                ->execute([
                    isset($_POST['codigo_gerencia'])       ? (trim($_POST['codigo_gerencia'])       ?: null) : null,
                    isset($_POST['clasificacion'])         ? (trim($_POST['clasificacion'])         ?: null) : null,
                    isset($_POST['prioridad_estrategica']) ? (trim($_POST['prioridad_estrategica']) ?: null) : null,
                    $alineacion,
                    $fuente_fin,
                    isset($_POST['solicitud_ip'])          ? (trim($_POST['solicitud_ip'])          ?: null) : null,
                    $id_pry,
                ]);

            // Encargados (Módulo 2 — solo ADM/GER)
            $stmt_enc_antes = $pdo->prepare("SELECT usuario FROM pry_proyectos_encargados WHERE id_proyecto = ? ORDER BY usuario");
            $stmt_enc_antes->execute([$id_pry]);
            $encargados_antes = $stmt_enc_antes->fetchAll(PDO::FETCH_COLUMN);

            if ($rango_mostrar === 'ADM' || $rango_mostrar === 'GER') {
                $pdo->prepare("DELETE FROM pry_proyectos_encargados WHERE id_proyecto = ?")->execute([$id_pry]);
                if (!empty($_POST['encargados'])) {
                    $stmt_enc = $pdo->prepare("INSERT INTO pry_proyectos_encargados (id_proyecto, usuario) VALUES (?, ?)");
                    foreach ((array)$_POST['encargados'] as $nombre) {
                        $nombre_limpio = trim($nombre);
                        if ($nombre_limpio !== '') {
                            $stmt_enc->execute([$id_pry, $nombre_limpio]);
                        }
                    }
                }
            }

            // Módulo 3 — Seguimiento y Avance
            $pdo->prepare("UPDATE pry_proyectos SET
                    id_estado              = ?,
                    porcentaje_ejecucion   = ?,
                    presupuesto_adjudicado = ?,
                    presupuesto_ejecutado  = ?,
                    lic_tipo               = ?,
                    lic_estado             = ?,
                    lic_monto              = ?,
                    lic_empresa            = ?,
                    lic_fecha_entrega      = ?,
                    lic_fecha_prorroga     = ?,
                    observaciones          = ?
                WHERE id = ?")
                ->execute([
                    $nuevo_estado,
                    intval(isset($_POST['avance']) ? $_POST['avance'] : 0),
                    (isset($_POST['presupuesto_adjudicado']) && $_POST['presupuesto_adjudicado'] !== '') ? intval($_POST['presupuesto_adjudicado']) : null,
                    (isset($_POST['presupuesto_ejecutado'])  && $_POST['presupuesto_ejecutado']  !== '') ? intval($_POST['presupuesto_ejecutado'])  : null,
                    isset($_POST['lic_tipo'])           ? (trim($_POST['lic_tipo'])    ?: null) : null,
                    isset($_POST['lic_estado'])         ? (trim($_POST['lic_estado'])  ?: null) : null,
                    (isset($_POST['lic_monto'])         && $_POST['lic_monto'] !== '') ? (float)$_POST['lic_monto'] : null,
                    isset($_POST['lic_empresa'])        ? (trim($_POST['lic_empresa']) ?: null) : null,
                    (isset($_POST['lic_fecha_entrega'])  && $_POST['lic_fecha_entrega']  !== '') ? $_POST['lic_fecha_entrega']  : null,
                    (isset($_POST['lic_fecha_prorroga']) && $_POST['lic_fecha_prorroga'] !== '') ? $_POST['lic_fecha_prorroga'] : null,
                    isset($_POST['observaciones'])      ? (trim($_POST['observaciones']) ?: null) : null,
                    $id_pry,
                ]);

            // Historial de estados (si cambió)
            if ($actual && $actual['id_estado'] != $nuevo_estado) {
                $stmt_nom = $pdo->prepare("SELECT nombre FROM pry_estados WHERE id = ?");
                $stmt_nom->execute([$nuevo_estado]);
                $nombre_nuevo_estado = $stmt_nom->fetchColumn();
                $comentario = isset($_POST['comentario_estado']) ? trim($_POST['comentario_estado']) : '';
                $pdo->prepare("INSERT INTO pry_historial_estados (id_proyecto, estado_anterior, estado_nuevo, usuario, comentario)
                               VALUES (?, ?, ?, ?, ?)")
                    ->execute([$id_pry, $actual['estado_nombre'], $nombre_nuevo_estado, $usuario_sesion, $comentario ?: null]);
            }
        }

        $pdo->commit();

        // ── BITÁCORA ──────────────────────────────────────────────────────
        $cambios = [];
        $campos_m1 = [
            ['Nombre',              $_POST['nombre'],                                       $actual['nombre']],
            ['BIP',                 isset($_POST['cod_bip']) ? trim($_POST['cod_bip']) : '', $actual['cod_bip'] ?: ''],
            ['Tipología',           isset($_POST['tipologia']) ? trim($_POST['tipologia']) : '', $actual['tipologia'] ?: ''],
            ['Etapa',               isset($_POST['etapa_postulacion']) ? trim($_POST['etapa_postulacion']) : '', $actual['etapa_postulacion'] ?: ''],
            ['Encargado Form.',     isset($_POST['encargado_formulacion']) ? trim($_POST['encargado_formulacion']) : '', $actual['encargado_formulacion'] ?: ''],
            ['RATE',                $_POST['rate'], $actual['rate']],
            ['Fecha inicio',        isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '', ($actual['fecha_inicio'] && $actual['fecha_inicio'] !== '0000-00-00' ? $actual['fecha_inicio'] : '')],
            ['Fecha entrega',       isset($_POST['fecha_entrega_estimada']) ? $_POST['fecha_entrega_estimada'] : '', ($actual['fecha_entrega_estimada'] && $actual['fecha_entrega_estimada'] !== '0000-00-00' ? $actual['fecha_entrega_estimada'] : '')],
            ['Ppto. IP (M$)',       isset($_POST['presupuesto_ip']) ? intval($_POST['presupuesto_ip']) : 0, intval($actual['presupuesto_ip'])],
        ];
        foreach ($campos_m1 as $c) {
            if ((string)$c[1] !== (string)$c[2]) {
                $cambios[] = $c[0] . ': "' . $c[2] . '" -> "' . $c[1] . '"';
            }
        }

        if ($aprobado) {
            $nuevo_estado_val = intval(isset($_POST['id_estado']) ? $_POST['id_estado'] : 0);
            if ($actual['id_estado'] != $nuevo_estado_val) {
                $cambios[] = 'Estado cambiado';
            }
            $campos_m23 = [
                ['Gerencia',        isset($_POST['codigo_gerencia'])       ? trim($_POST['codigo_gerencia'])       : '', $actual['codigo_gerencia'] ?: ''],
                ['Solicitud IP',    isset($_POST['solicitud_ip'])          ? trim($_POST['solicitud_ip'])          : '', $actual['solicitud_ip'] ?: ''],
                ['Lic. Tipo',       isset($_POST['lic_tipo'])              ? trim($_POST['lic_tipo'])              : '', $actual['lic_tipo'] ?: ''],
                ['Lic. Estado',     isset($_POST['lic_estado'])            ? trim($_POST['lic_estado'])            : '', $actual['lic_estado'] ?: ''],
                ['Lic. Empresa',    isset($_POST['lic_empresa'])           ? trim($_POST['lic_empresa'])           : '', $actual['lic_empresa'] ?: ''],
                ['Ppto. Adj. (M$)', isset($_POST['presupuesto_adjudicado']) ? intval($_POST['presupuesto_adjudicado']) : 0, intval($actual['presupuesto_adjudicado'])],
            ];
            foreach ($campos_m23 as $c) {
                if ((string)$c[1] !== (string)$c[2]) {
                    $cambios[] = $c[0] . ': "' . $c[2] . '" -> "' . $c[1] . '"';
                }
            }
        }

        $detalle = empty($cambios) ? 'Sin cambios detectados.' : implode(' | ', $cambios);
        registrar_auditoria($pdo, 'EDITAR', $id_pry, $_POST['nombre'], $detalle, $usuario_sesion);

        header("Location: ../ficha.php?id=$id_pry&edit=ok");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error crítico en la base de datos EPA: " . $e->getMessage());
    }
}
?>
