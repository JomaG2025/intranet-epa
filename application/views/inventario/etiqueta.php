<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Etiqueta - <?= inv_e($activo['codigo_unico']); ?></title>
<style>
    /*
        Colores institucionales: ver nota en inventario/ficha.php.
        Logo real: imagenes/logoepa.png (1920x1080, apaisado). Se limita
        con max-width/max-height (no width fijo) para que quede completo
        y proporcional dentro de la columna izquierda sin deformarse,
        sea cual sea su relacion de aspecto real.

        Prioridad visual pedida (de mas a menos espacio/protagonismo):
        1) Codigo unico grande  2) QR (columna derecha, 30mm cuadrados,
        ya definitivo -- no tocar)  3) Logo EPA (~19mm de ancho)
        4) Ubicacion/Area (pie). El tamano fisico de la etiqueta sigue
        siendo 90mm x 50mm.
    */
    @page { size: 90mm 50mm; margin: 0; }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        color: #25336d;
        background: #eee;
    }
    .etiqueta {
        width: 90mm;
        height: 50mm;
        margin: 10mm auto;
        background: #fff;
        border: 1.5px solid #0078bd;
        border-radius: 4mm;
        padding: 3mm 4mm;
        display: table;
    }
    .fila-superior { display: table-row; }
    .col-logo, .col-info, .col-qr {
        display: table-cell;
        vertical-align: middle;
    }
    .col-logo {
        width: 23mm;
        border-right: 1px solid #d6dde6;
        padding-right: 2mm;
        text-align: center;
    }
    .col-logo img {
        max-width: 19mm;
        max-height: 15mm;
        width: auto;
        height: auto;
    }
    .col-logo .logo-fallback {
        display: inline-block;
        font-size: 3mm;
        font-weight: 700;
        color: #25336d;
        line-height: 1.15;
    }
    .col-info { padding: 0 2mm; }
    .badge {
        display: inline-block;
        background: #0078bd;
        color: #fff;
        font-size: 2.4mm;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.6mm 2mm;
        border-radius: 1mm;
        letter-spacing: .1mm;
    }
    .codigo-grande {
        font-size: 7mm;
        font-weight: 800;
        color: #25336d;
        margin: 0.6mm 0;
        line-height: 1.1;
    }
    .categoria {
        font-size: 2.6mm;
        font-weight: 700;
        color: #6f8093;
        text-transform: uppercase;
        letter-spacing: .1mm;
    }
    /*
        Columna del QR: bien ancha y cuadrada a proposito (pedido
        explicito: "el espacio reservado para el QR es demasiado
        pequeno"). El QR (o su placeholder mientras no exista) siempre
        mide 30mm x 30mm -- dentro de los 28-32mm pedidos -- y la columna
        mide un poco mas (32mm) solo para dejarle un margen de aire
        parejo alrededor, nunca para estirarlo ni recortarlo.
    */
    .col-qr {
        width: 32mm;
        text-align: center;
        border-left: 1px solid #d6dde6;
        padding-left: 2mm;
    }
    .col-qr img { width: 30mm; height: 30mm; }
    .qr-pendiente {
        width: 30mm; height: 30mm; margin: 0 auto;
        border: 1px dashed #b9c3d0; color: #8a95a3;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.9mm; text-align: center; line-height: 1.25;
        padding: 3mm;
    }
    /*
        Ubicacion y Area quedan claramente separadas a proposito (antes
        se veian pegadas como "UBICACIONAREA"): cada columna tiene su
        propio padding y hay ademas una linea divisoria vertical entre
        ambas, en vez de depender solo del 50%/50% del table-cell.
    */
    .pie {
        display: table;
        width: 100%;
        min-height: 11.5mm;
        margin-top: 1.5mm;
        padding-top: 2mm;
        border-top: 1px solid #d6dde6;
    }
    .pie .col { display: table-cell; width: 50%; vertical-align: middle; padding: 0 2.5mm; }
    .pie .col:first-child { padding-left: 0; border-right: 1px solid #d6dde6; }
    .pie .col:last-child { padding-right: 0; }
    .pie .etq { display: block; font-size: 2.2mm; font-weight: 700; color: #0078bd; text-transform: uppercase; margin-bottom: 0.6mm; }
    .pie .val { display: block; font-size: 3.4mm; font-weight: 600; color: #25336d; }
    .btn-imprimir { text-align: center; margin-bottom: 4mm; }
    .btn-imprimir button {
        background: #0078bd; color: #fff; border: 0; padding: 2.5mm 5mm;
        border-radius: 1mm; cursor: pointer; font-size: 3.6mm;
    }
    @media print {
        body { background: #fff; }
        .btn-imprimir { display: none; }
        .etiqueta { margin: 0; border-radius: 0; }
    }
</style>
</head>
<body>

<div class="btn-imprimir"><button onclick="window.print()">Imprimir / Guardar PDF</button></div>

<div class="etiqueta">
    <div class="fila-superior">
        <div class="col-logo">
            <?php if (inv_logo_disponible()) { ?>
            <img src="<?= base_url('imagenes/logoepa.png'); ?>" alt="Puerto Arica">
            <?php } else { ?>
            <span class="logo-fallback">Puerto Arica</span>
            <?php } ?>
        </div>
        <div class="col-info">
            <span class="badge">Codigo del bien</span>
            <div class="codigo-grande"><?= inv_e($activo['codigo_unico']); ?></div>
            <div class="categoria"><?= $activo['tipo_nombre'] ? inv_e($activo['tipo_nombre']) : inv_e($activo['categoria_nombre']); ?></div>
        </div>
        <div class="col-qr">
            <?php if ($qr_disponible) { ?>
            <img src="<?= site_url('inventario/qr/' . $activo['id']); ?>" alt="QR">
            <?php } else { ?>
            <div class="qr-pendiente">QR pendiente de generacion</div>
            <?php } ?>
        </div>
    </div>

    <div class="pie">
        <div class="col">
            <span class="etq">Ubicacion</span>
            <span class="val"><?= $activo['ubicacion_nombre'] ? inv_e($activo['ubicacion_nombre']) : '-'; ?></span>
        </div>
        <div class="col">
            <span class="etq">Area</span>
            <span class="val"><?= $activo['area_nombre'] ? inv_e($activo['area_nombre']) : '-'; ?></span>
        </div>
    </div>
</div>

</body>
</html>
