<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Importar Excel <i class="fa fa-angle-right"></i> Resultado</h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-file-excel-o"></i> Resultado de la importacion</div>
    <div class="panel-body">

        <p><strong>Archivo:</strong> <?= inv_e($nombre_archivo); ?></p>

        <?php if ($resultado['ok']) { ?>
        <div class="alert alert-success">
            <strong>Importacion confirmada.</strong> Se procesaron <?= (int) $resultado['resumen']['total']; ?> fila(s).
        </div>

        <div class="row text-center" style="margin-bottom: 15px;">
            <div class="col-sm-4">
                <h3 class="text-success"><?= (int) $resultado['insertados']; ?></h3>
                <p class="text-muted">Insertados</p>
            </div>
            <div class="col-sm-4">
                <h3 class="text-muted"><?= (int) $resultado['omitidos']; ?></h3>
                <p class="text-muted">Omitidos (ya existian)</p>
            </div>
            <div class="col-sm-4">
                <h3 class="text-danger"><?= (int) $resultado['errores']; ?></h3>
                <p class="text-muted">Con errores</p>
            </div>
        </div>

        <?php if ( ! empty($resultado['filas'])) { ?>
        <p><strong>Detalle de filas con error:</strong></p>
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Fila</th>
                        <th>Codigo Unico</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado['filas'] as $fila) { ?>
                    <tr>
                        <td><?= (int) $fila['fila']; ?></td>
                        <td><?= inv_e($fila['codigo_unico']); ?></td>
                        <td><?= empty($fila['mensajes']) ? '-' : inv_e(implode(' / ', $fila['mensajes'])); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>

        <?php } else { ?>
        <div class="alert alert-danger">
            <strong>La importacion no se aplico.</strong> <?= inv_e($resultado['error_general']); ?>
        </div>
        <?php } ?>

        <a href="<?= site_url('inventario/listado'); ?>" class="btn btn-info"><i class="fa fa-list"></i> Ver listado de activos</a>
        <a href="<?= site_url('inventario/importaciones'); ?>" class="btn btn-default"><i class="fa fa-history"></i> Ver historial de importaciones</a>
        <a href="<?= site_url('inventario/importar'); ?>" class="btn btn-default"><i class="fa fa-upload"></i> Importar otro archivo</a>

    </div>
</div>
