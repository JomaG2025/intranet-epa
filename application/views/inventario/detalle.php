<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Activo <i class="fa fa-angle-right"></i> <?= inv_e($activo['codigo_unico']); ?></h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('inventario/ficha/' . $activo['id']); ?>" class="btn btn-default" target="_blank"><i class="fa fa-file-text-o"></i> Ficha</a>
    <a href="<?= site_url('inventario/etiqueta/' . $activo['id']); ?>" class="btn btn-default" target="_blank"><i class="fa fa-tag"></i> Etiqueta</a>
    <?php if ($puede_editar) { ?>
    <a href="<?= site_url('inventario/editar/' . $activo['id']); ?>" class="btn btn-info"><i class="fa fa-edit"></i> Editar</a>
    <?php } ?>
</div>

<?php if ($activo['dado_baja'] == 1) { ?>
<div class="alert alert-danger">
    <strong>Activo dado de baja</strong> el <?= inv_formato_fecha_corta($activo['fecha_baja']); ?>.
    Motivo: <?= inv_e($activo['motivo_baja']); ?>
    <?php if ($puede_administrar) { ?>
    <form method="post" action="<?= site_url('inventario/reactivar'); ?>" style="display:inline;">
        <input type="hidden" name="id" value="<?= (int) $activo['id']; ?>">
        <?= inv_csrf_campo(); ?>
        <button type="submit" class="btn btn-xs btn-warning">Reactivar activo</button>
    </form>
    <?php } ?>
</div>
<?php } ?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default no-border">
            <div class="panel-body text-center">
                <?php if ($foto) { ?>
                <img src="<?= base_url('uploads/inventario/fotos/' . rawurlencode($foto['archivo'])); ?>" class="img-responsive" style="margin: 0 auto 15px;">
                <?php } else { ?>
                <p class="text-muted" style="padding: 40px 0;"><i class="fa fa-camera fa-3x"></i><br>Sin fotografia</p>
                <?php } ?>
                <?php if ($puede_editar) { ?>
                <a href="<?= site_url('inventario/foto/' . $activo['id']); ?>" class="btn btn-default btn-sm btn-block"><i class="fa fa-camera"></i> Administrar fotografias</a>
                <?php } ?>
            </div>
        </div>
        <div class="panel panel-default no-border">
            <div class="panel-body text-center">
                <img src="<?= site_url('inventario/qr/' . $activo['id']); ?>" alt="QR" style="max-width: 180px;">
                <p class="text-muted small">Al escanear este QR se abre esta misma ficha (requiere sesion iniciada).</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel panel-default no-border">
            <div class="panel-heading">Datos del activo <?= inv_badge_estado($activo); ?></div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <tr><th style="width:180px;">Codigo unico</th><td><?= inv_e($activo['codigo_unico']); ?></td></tr>
                    <tr><th>Codigo</th><td><?= $activo['codigo'] ? inv_e($activo['codigo']) : '-'; ?></td></tr>
                    <tr><th>Descripcion</th><td><?= inv_e($activo['descripcion']); ?></td></tr>
                    <tr><th>Gerencia</th><td><?= $activo['gerencia_nombre'] ? inv_e($activo['gerencia_nombre']) : '-'; ?></td></tr>
                    <tr><th>Area</th><td><?= $activo['area_nombre'] ? inv_e($activo['area_nombre']) : '-'; ?></td></tr>
                    <tr><th>Categoria</th><td><?= inv_e($activo['categoria_nombre']); ?></td></tr>
                    <tr><th>Subcategoria</th><td><?= $activo['subcategoria_nombre'] ? inv_e($activo['subcategoria_nombre']) : '-'; ?></td></tr>
                    <tr><th>Tipo</th><td><?= $activo['tipo_nombre'] ? inv_e($activo['tipo_nombre']) : '-'; ?></td></tr>
                    <tr><th>Marca / Modelo</th><td><?= inv_e($activo['marca']); ?> <?= inv_e($activo['modelo']); ?></td></tr>
                    <tr><th>Color</th><td><?= $activo['color'] ? inv_e($activo['color']) : '-'; ?></td></tr>
                    <tr><th>N. de Serie</th><td><?= $activo['numero_serie'] ? inv_e($activo['numero_serie']) : '-'; ?></td></tr>
                    <tr>
                        <th>Ubicacion</th>
                        <td><?= $activo['ubicacion_nombre'] ? inv_e($activo['ubicacion_nombre']) : '-'; ?>
                            <?php if ($puede_editar) { ?>
                            &nbsp; <a href="<?= site_url('inventario/cambiar_ubicacion/' . $activo['id']); ?>" class="btn btn-xs btn-default"><i class="fa fa-exchange"></i> Cambiar</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Responsable</th>
                        <td><?= $activo['responsable_nombre'] ? inv_e($activo['responsable_nombre']) : '-'; ?> <?= $activo['cargo'] ? '(' . inv_e($activo['cargo']) . ')' : ''; ?>
                            <?php if ($puede_editar) { ?>
                            &nbsp; <a href="<?= site_url('inventario/cambiar_responsable/' . $activo['id']); ?>" class="btn btn-xs btn-default"><i class="fa fa-exchange"></i> Cambiar</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td><?= $activo['estado_nombre'] ? inv_e($activo['estado_nombre']) : '-'; ?>
                            <?php if ($puede_editar) { ?>
                            &nbsp; <a href="<?= site_url('inventario/cambiar_estado/' . $activo['id']); ?>" class="btn btn-xs btn-default"><i class="fa fa-exchange"></i> Cambiar</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr><th>Fecha de adquisicion</th><td><?= inv_formato_fecha_corta($activo['fecha_adquisicion']); ?></td></tr>
                    <?php if ($mostrar_valor) { ?>
                    <tr><th>Valor libro</th><td><?= inv_formato_moneda($activo['valor_libro']); ?></td></tr>
                    <tr><th>Depreciacion acumulada</th><td><?= inv_formato_moneda($activo['depreciacion_acumulada']); ?></td></tr>
                    <?php } ?>
                    <tr><th>Observaciones</th><td><?= $activo['observaciones'] ? nl2br(inv_e($activo['observaciones'])) : '-'; ?></td></tr>
                </table>

                <?php if ($puede_administrar AND ($activo['dado_baja'] == 0)) { ?>
                <a href="<?= site_url('inventario/baja/' . $activo['id']); ?>" class="btn btn-danger"><i class="fa fa-ban"></i> Dar de baja</a>
                <?php } ?>
            </div>
        </div>

        <div class="panel panel-default no-border">
            <div class="panel-heading">Ultimos movimientos <a href="<?= site_url('inventario/historial/' . $activo['id']); ?>" class="pull-right">Ver historial completo</a></div>
            <div class="panel-body">
                <?php if (empty($movimientos)) { ?>
                <p class="text-muted">Sin movimientos registrados.</p>
                <?php } else { ?>
                <table class="table table-condensed">
                    <?php $i = 0; foreach ($movimientos as $mov) { if ($i >= 5) break; $i++; ?>
                    <tr>
                        <td><?= inv_formato_fecha_corta($mov['fecha']); ?></td>
                        <td><?= inv_tipo_movimiento_texto($mov['tipo_movimiento']); ?></td>
                        <td><?= inv_e($mov['valor_anterior']); ?> &rarr; <?= inv_e($mov['valor_nuevo']); ?></td>
                        <td><?= $mov['usuario_nombre'] ? inv_e($mov['usuario_nombre']) : '-'; ?></td>
                    </tr>
                    <?php } ?>
                </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
