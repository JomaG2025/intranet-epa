<?php
// /intranet/proyectos/config/helpers.php

/**
 * Retorna el estilo CSS y el texto descriptivo según el RATE
 * Basado en las Fórmulas de Planificación Estratégica EPA
 */
function obtener_semaforo($rate) {
    $semaforo = [
        'clase' => 'label-default',
        'color' => '#777',
        'texto' => 'Sin Información',
        'icono' => 'fa-question-circle'
    ];

    switch (trim($rate)) {
        case 'RS':
            $semaforo = [
                'clase' => 'label-success',
                'color' => '#5cb85c',
                'texto' => 'Recomendado Satisfactoriamente',
                'icono' => 'fa-check-circle'
            ];
            break;
        case 'RSA':
            $semaforo = [
                'clase' => 'label-success',
                'color' => '#27ae60',
                'texto' => 'RS de Arrastre',
                'icono' => 'fa-check-circle'
            ];
            break;
        case 'FI':
            $semaforo = [
                'clase' => 'label-warning', // Amarillo
                'color' => '#f0ad4e',
                'texto' => 'Falta Información / En Proceso',
                'icono' => 'fa-exclamation-triangle'
            ];
            break;
        case 'OT':
            $semaforo = [
                'clase' => 'label-danger', // Rojo
                'color' => '#d9534f',
                'texto' => 'Objetado Técnicamente',
                'icono' => 'fa-times-circle'
            ];
            break;
        case 'SR':
            $semaforo = [
                'clase' => 'label-default', // Blanco/Gris
                'color' => '#999',
                'texto' => 'Sin RATE',
                'icono' => 'fa-minus-circle'
            ];
            break;
        case 'ER':
            $semaforo = [
                'clase' => 'label-info', // Azul/Blanco
                'color' => '#5bc0de',
                'texto' => 'En Revisión',
                'icono' => 'fa-clock-o'
            ];
            break;
    }

    return $semaforo;
}

function calcular_semaforo_avance($porcentaje_real, $fecha_inicio, $fecha_entrega, $estado) {
    if (trim($estado) === 'Finalizada') {
        return ['color' => '#5bc0de', 'clase' => 'info',    'texto' => 'Finalizado',     'icono' => 'fa-flag-checkered'];
    }
    if (empty($fecha_inicio)  || $fecha_inicio  === '0000-00-00' ||
        empty($fecha_entrega) || $fecha_entrega === '0000-00-00') {
        return ['color' => '#aaa',    'clase' => 'default', 'texto' => 'Sin fechas',      'icono' => 'fa-circle-o'];
    }
    $hoy    = time();
    $inicio = strtotime($fecha_inicio);
    $fin    = strtotime($fecha_entrega);
    if ($fin <= $inicio) {
        return ['color' => '#aaa',    'clase' => 'default', 'texto' => 'Sin fechas',      'icono' => 'fa-circle-o'];
    }
    if ($hoy <= $inicio) {
        return ['color' => '#aaa',    'clase' => 'default', 'texto' => 'No iniciado',     'icono' => 'fa-circle-o'];
    }
    $duracion     = $fin - $inicio;
    $transcurrido = min($hoy - $inicio, $duracion);
    $pct_esperado = ($transcurrido / $duracion) * 100;
    $diferencia   = $pct_esperado - (int)$porcentaje_real;
    if ($diferencia <= 0) {
        return ['color' => '#5cb85c', 'clase' => 'success', 'texto' => 'Al día',          'icono' => 'fa-check-circle'];
    } elseif ($diferencia <= 20) {
        return ['color' => '#f0ad4e', 'clase' => 'warning', 'texto' => 'Leve retraso',    'icono' => 'fa-exclamation-circle'];
    } else {
        return ['color' => '#d9534f', 'clase' => 'danger',  'texto' => 'Retraso crítico', 'icono' => 'fa-times-circle'];
    }
}

function registrar_auditoria($pdo, $accion, $id_proyecto, $nombre_pry, $detalle, $usuario) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    try {
        $pdo->prepare("INSERT INTO pry_auditoria (accion, id_proyecto, nombre_pry, detalle, usuario, ip)
                       VALUES (?, ?, ?, ?, ?, ?)")
            ->execute([$accion, $id_proyecto, $nombre_pry, $detalle, $usuario, $ip]);
    } catch (Exception $e) {
        // Silencioso — la auditoría no interrumpe el flujo principal
    }
}