<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Historial <i class="fa fa-angle-right"></i> <?= inv_e($activo['codigo_unico']); ?></h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('inventario/detalle/' . $activo['id']); ?>" class="btn btn-default"><i class="fa fa-reply"></i> Volver al activo</a>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-history"></i> Historial</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Movimiento</th>
                        <th class="text-center">Valor anterior</th>
                        <th class="text-center">Valor nuevo</th>
                        <th class="text-center">Motivo</th>
                        <th class="text-center">Observacion</th>
                        <th class="text-center">Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $mov) { ?>
                    <tr>
                        <td class="text-center"><?= inv_formato_fecha_corta($mov['fecha']); ?></td>
                        <td class="text-center"><?= inv_tipo_movimiento_texto($mov['tipo_movimiento']); ?></td>
                        <td><?= $mov['valor_anterior'] ? inv_e($mov['valor_anterior']) : '-'; ?></td>
                        <td><?= $mov['valor_nuevo'] ? inv_e($mov['valor_nuevo']) : '-'; ?></td>
                        <td><?= $mov['motivo'] ? inv_e($mov['motivo']) : '-'; ?></td>
                        <td><?= $mov['observacion'] ? inv_e($mov['observacion']) : '-'; ?></td>
                        <td class="text-center"><?= $mov['usuario_nombre'] ? inv_e($mov['usuario_nombre']) : '-'; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if (empty($movimientos)) { ?>
                    <tr>
                        <td colspan="7" class="text-muted text-center">Este activo aun no tiene movimientos registrados.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
