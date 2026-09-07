<?php
// /intranet/proyectos/acciones/guardar_proyecto.php
$path_header = dirname(__DIR__) . '/header_local.php';
include_once $path_header;
require_once dirname(__DIR__) . '/config/helpers.php';

if ($usuario_sesion === 'Invitado') {
    die("Acceso denegado: No tiene permisos para crear proyectos.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        // ── MÓDULO 1: Campos BIP ───────────────────────────────────────────
        $sql = "INSERT INTO pry_proyectos (
                    nombre, cod_bip, descripcion,
                    tipologia, etapa_postulacion,
                    encargado_formulacion,
                    id_estado, rate, porcentaje_ejecucion,
                    fecha_inicio, fecha_entrega_estimada,
                    presupuesto_ip,
                    idi_anio_pagado, idi_anio_solic,
                    observaciones
                ) VALUES (
                    ?, ?, ?,
                    ?, ?,
                    ?,
                    ?, ?, ?,
                    ?, ?,
                    ?,
                    ?, ?,
                    ?
                )";

        $pdo->prepare($sql)->execute([
            $_POST['nombre'],
            isset($_POST['cod_bip'])               ? (trim($_POST['cod_bip'])    ?: null) : null,
            isset($_POST['descripcion'])            ? (trim($_POST['descripcion']) ?: null) : null,
            isset($_POST['tipologia'])              ? (trim($_POST['tipologia'])  ?: null) : null,
            isset($_POST['etapa_postulacion'])      ? (trim($_POST['etapa_postulacion']) ?: null) : null,
            isset($_POST['encargado_formulacion'])  ? (trim($_POST['encargado_formulacion']) ?: null) : null,
            intval($_POST['id_estado']),
            $_POST['rate'],
            intval(isset($_POST['avance']) ? $_POST['avance'] : 0),
            (isset($_POST['fecha_inicio'])            && $_POST['fecha_inicio']            !== '') ? $_POST['fecha_inicio']            : null,
            (isset($_POST['fecha_entrega_estimada'])  && $_POST['fecha_entrega_estimada']  !== '') ? $_POST['fecha_entrega_estimada']  : null,
            (isset($_POST['presupuesto_ip'])          && $_POST['presupuesto_ip']          !== '') ? intval($_POST['presupuesto_ip']) : null,
            isset($_POST['idi_anio_pagado']) ? intval($_POST['idi_anio_pagado']) : 2025,
            isset($_POST['idi_anio_solic'])  ? intval($_POST['idi_anio_solic'])  : 2026,
            isset($_POST['observaciones']) ? (trim($_POST['observaciones']) ?: null) : null,
        ]);

        $nuevo_id = $pdo->lastInsertId();

        // ── FICHA IDI: guardar filas ──────────────────────────────────────
        if (!empty($_POST['idi_fuente']) && is_array($_POST['idi_fuente'])) {
            $stmt_idi = $pdo->prepare("INSERT INTO pry_ficha_idi
                (id_proyecto, fuente, asignacion, moneda, pagado_2025, solic_2026, solic_sig, costo_total, anio_pagado, anio_solic, orden)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($_POST['idi_fuente'] as $k => $fuente) {
                $fuente_v  = trim($fuente);
                $asig_v    = isset($_POST['idi_asignacion'][$k]) ? trim($_POST['idi_asignacion'][$k]) : null;
                if ($fuente_v === '' && $asig_v === null) continue;
                $stmt_idi->execute([
                    $nuevo_id,
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

        $pdo->commit();

        registrar_auditoria($pdo, 'CREAR', $nuevo_id, $_POST['nombre'],
            'Iniciativa BIP creada. RATE: ' . $_POST['rate'] . ' | Etapa: ' . (isset($_POST['etapa_postulacion']) ? $_POST['etapa_postulacion'] : ''),
            $usuario_sesion);

        header("Location: ../index.php?msg=ok");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error al guardar en el sistema: " . $e->getMessage());
    }
}
?>
