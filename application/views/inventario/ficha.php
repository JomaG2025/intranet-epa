<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ficha del Activo - <?= inv_e($activo['codigo_unico']); ?></title>
<style>
    /*
        Colores institucionales tomados de archivos reales de la intranet:
        - #0078bd  -> css/estilo.css, overlay de fondo del login (rgba(0,120,189,.65))
        - #25336d  -> css/estilo.css (.circulo-fecha, .acceso.geovictoria)

        Logo real: imagenes/logoepa.png (1920x1080). Solo se fija la
        altura en CSS (.ficha-logo img { height: ... }); el ancho queda
        en "auto" a proposito para que el navegador escale la imagen
        proporcionalmente sin deformarla, sea cual sea su relacion de
        aspecto real.
    */
    @page { size: A5 portrait; margin: 0; }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        color: #25336d;
        background: #eee;
    }
    .ficha {
        width: 148mm;
        margin: 10mm auto;
        background: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }
    .ficha-logo {
        padding: 5mm 6mm 3mm;
        text-align: left;
    }
    .ficha-logo img {
        height: 12mm;
        width: auto;
        max-width: 100%;
    }
    .ficha-logo .logo-fallback {
        font-size: 5mm;
        font-weight: 700;
        color: #25336d;
        letter-spacing: .3mm;
    }
    .ficha-header {
        background: #0078bd;
        color: #fff;
        padding: 4mm 6mm;
        display: table;
        width: 100%;
    }
    .ficha-header .titulo {
        display: table-cell;
        vertical-align: middle;
        font-size: 6mm;
        font-weight: 700;
    }
    .ficha-header .codigo {
        display: table-cell;
        vertical-align: middle;
        text-align: right;
    }
    .ficha-header .codigo span {
        background: #fff;
        color: #0078bd;
        font-weight: 700;
        padding: 1.5mm 3mm;
        border-radius: 1mm;
        font-size: 4mm;
    }
    .ficha-body { padding: 4mm 6mm; }
    .campo {
        border: 1px solid #d6dde6;
        border-radius: 1mm;
        padding: 1.5mm 3mm;
        margin-bottom: 2.5mm;
    }
    .campo .etq {
        display: block;
        font-size: 2.8mm;
        font-weight: 700;
        color: #6f8093;
        text-transform: uppercase;
        letter-spacing: .2mm;
    }
    .campo .val { font-size: 3.6mm; }
    .fila { display: table; width: 100%; border-spacing: 2mm 0; }
    .fila .mitad { display: table-cell; width: 50%; }
    .fila .mitad .campo { margin-right: 1mm; }
    .media { display: table; width: 100%; margin-top: 2mm; }
    .media .col { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
    .media .etq {
        display: block;
        font-size: 2.8mm;
        font-weight: 700;
        color: #6f8093;
        text-transform: uppercase;
        margin-bottom: 1mm;
    }
    .media img.foto { max-width: 36mm; max-height: 36mm; border: 1px solid #d6dde6; }
    .media img.qr { width: 30mm; height: 30mm; }
    .placeholder-foto {
        width: 36mm; height: 36mm; margin: 0 auto;
        border: 1px dashed #b9c3d0; color: #b9c3d0;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.8mm;
    }
    .ficha-footer { background: #0078bd; height: 4mm; }
    .qr-pendiente {
        font-size: 2.6mm; color: #8a95a3; margin-top: 1.5mm;
    }
    .btn-imprimir {
        width: 148mm; margin: 0 auto 4mm; text-align: center;
    }
    .btn-imprimir button {
        background: #0078bd; color: #fff; border: 0; padding: 2.5mm 5mm;
        border-radius: 1mm; cursor: pointer; font-size: 3.6mm;
    }
    @media print {
        body { background: #fff; }
        .btn-imprimir { display: none; }
        .ficha { margin: 0 auto; border: none; box-shadow: none; }
    }
</style>
</head>
<body>

<div class="btn-imprimir"><button onclick="window.print()">Imprimir / Guardar PDF</button></div>

<div class="ficha">
    <div class="ficha-logo">
        <?php if (inv_logo_disponible()) { ?>
        <img src="<?= base_url('imagenes/logoepa.png'); ?>" alt="Puerto Arica">
        <?php } else { ?>
        <span class="logo-fallback">Puerto Arica</span>
        <?php } ?>
    </div>

    <div class="ficha-header">
        <div class="titulo">Ficha del Activo</div>
        <div class="codigo"><span><?= inv_e($activo['codigo_unico']); ?></span></div>
    </div>

    <div class="ficha-body">

        <div class="campo">
            <span class="etq">Area</span>
            <span class="val"><?= $activo['area_nombre'] ? inv_e($activo['area_nombre']) : '-'; ?></span>
        </div>

        <div class="fila">
            <div class="mitad">
                <div class="campo">
                    <span class="etq">Categoria</span>
                    <span class="val"><?= inv_e($activo['categoria_nombre']); ?></span>
                </div>
            </div>
            <div class="mitad">
                <div class="campo">
                    <span class="etq">Subcategoria</span>
                    <span class="val"><?= $activo['subcategoria_nombre'] ? inv_e($activo['subcategoria_nombre']) : '-'; ?></span>
                </div>
            </div>
        </div>

        <div class="campo">
            <span class="etq">Descripcion</span>
            <span class="val"><?= inv_e($activo['descripcion']); ?></span>
        </div>

        <div class="fila">
            <div class="mitad">
                <div class="campo">
                    <span class="etq">Marca</span>
                    <span class="val"><?= $activo['marca'] ? inv_e($activo['marca']) : '-'; ?></span>
                </div>
            </div>
            <div class="mitad">
                <div class="campo">
                    <span class="etq">Color</span>
                    <span class="val"><?= $activo['color'] ? inv_e($activo['color']) : '-'; ?></span>
                </div>
            </div>
        </div>

        <div class="fila">
            <div class="mitad">
                <div class="campo">
                    <span class="etq">Modelo</span>
                    <span class="val"><?= $activo['modelo'] ? inv_e($activo['modelo']) : '-'; ?></span>
                </div>
            </div>
            <div class="mitad">
                <div class="campo">
                    <span class="etq">Nro de Serie</span>
                    <span class="val"><?= $activo['numero_serie'] ? inv_e($activo['numero_serie']) : '-'; ?></span>
                </div>
            </div>
        </div>

        <div class="campo">
            <span class="etq">Ubicacion</span>
            <span class="val"><?= $activo['ubicacion_nombre'] ? inv_e($activo['ubicacion_nombre']) : '-'; ?></span>
        </div>

        <div class="campo">
            <span class="etq">Responsable</span>
            <span class="val"><?= $activo['responsable_nombre'] ? inv_e($activo['responsable_nombre']) : '-'; ?></span>
        </div>

        <div class="fila">
            <div class="mitad">
                <div class="campo">
                    <span class="etq">Fecha</span>
                    <span class="val"><?= inv_formato_fecha_corta($activo['fecha_adquisicion']); ?></span>
                </div>
            </div>
            <div class="mitad">
                <div class="campo">
                    <span class="etq">Valor</span>
                    <span class="val"><?= $mostrar_valor ? inv_formato_moneda($activo['valor_libro']) : 'No autorizado'; ?></span>
                </div>
            </div>
        </div>

        <div class="media">
            <div class="col">
                <span class="etq">Imagen</span>
                <?php if ($foto) { ?>
                <img class="foto" src="<?= base_url('uploads/inventario/fotos/' . rawurlencode($foto['archivo'])); ?>">
                <?php } else { ?>
                <div class="placeholder-foto">Sin fotografia</div>
                <?php } ?>
            </div>
            <div class="col">
                <span class="etq">Codigo QR</span>
                <?php if ($qr_disponible) { ?>
                <img class="qr" src="<?= site_url('inventario/qr/' . $activo['id']); ?>">
                <?php } else { ?>
                <div class="placeholder-foto">QR</div>
                <p class="qr-pendiente">Codigo QR pendiente de generacion</p>
                <?php } ?>
            </div>
        </div>

    </div>

    <div class="ficha-footer"></div>
</div>

</body>
</html>
