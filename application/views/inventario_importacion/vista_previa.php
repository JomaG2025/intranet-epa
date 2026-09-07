<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Importar Excel <i class="fa fa-angle-right"></i> Vista previa</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('inventario/importar'); ?>" class="btn btn-default"><i class="fa fa-reply"></i> Subir otro archivo</a>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-file-excel-o"></i> Vista previa</div>
    <div class="panel-body">

        <p><strong>Archivo:</strong> <?= inv_e($nombre_archivo); ?></p>

        <?php if ($resultado['error_general']) { ?>
        <div class="alert alert-danger">
            <strong>No se pudo procesar el archivo.</strong> <?= inv_e($resultado['error_general']); ?>
        </div>
        <p>
            <a href="<?= site_url('inventario/importar'); ?>" class="btn btn-default"><i class="fa fa-reply"></i> Volver</a>
        </p>
        <?php } else { ?>

        <div class="row text-center" style="margin-bottom: 15px;">
            <div class="col-sm-2 col-xs-4">
                <h3><?= (int) $resultado['resumen']['total']; ?></h3>
                <p class="text-muted">Total filas</p>
            </div>
            <div class="col-sm-2 col-xs-4">
                <h3 class="text-success"><?= (int) $resultado['resumen']['nuevos']; ?></h3>
                <p class="text-muted">Nuevos</p>
            </div>
            <div class="col-sm-2 col-xs-4">
                <h3 class="text-warning"><?= (int) $resultado['resumen']['con_advertencias']; ?></h3>
                <p class="text-muted">Con advertencias</p>
            </div>
            <div class="col-sm-2 col-xs-4">
                <h3 class="text-muted"><?= (int) $resultado['resumen']['ya_existentes']; ?></h3>
                <p class="text-muted">Ya existentes</p>
            </div>
            <div class="col-sm-2 col-xs-4">
                <h3 class="text-danger"><?= (int) $resultado['resumen']['con_errores']; ?></h3>
                <p class="text-muted">Con errores</p>
            </div>
        </div>

        <?php
            $hay_catalogos_nuevos = FALSE;
            foreach ($resultado['catalogos_nuevos'] as $lista) { if ( ! empty($lista)) { $hay_catalogos_nuevos = TRUE; break; } }
        ?>
        <?php if ($hay_catalogos_nuevos) { ?>
        <div class="alert alert-warning">
            <strong>Catalogos nuevos que se crearan al confirmar:</strong>
            <ul style="margin-bottom:0;">
                <?php $etiquetas = array('gerencias'=>'Gerencia','areas'=>'Area','categorias'=>'Categoria','subcategorias'=>'Subcategoria','tipos'=>'Tipo','ubicaciones'=>'Ubicacion','responsables'=>'Responsable','estados'=>'Estado'); ?>
                <?php foreach ($resultado['catalogos_nuevos'] as $clave => $lista) { ?>
                    <?php if (empty($lista)) continue; ?>
                    <li><?= inv_e($etiquetas[$clave]); ?>: <?= inv_e(implode(', ', $lista)); ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>

        <?php if ($resultado['resumen']['nuevos'] == 0 AND $resultado['resumen']['con_advertencias'] == 0) { ?>
        <div class="alert alert-info">No hay filas nuevas para importar en este archivo.</div>
        <?php } ?>

        <div class="table-responsive">
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Fila</th>
                        <th class="text-center">Codigo Unico</th>
                        <th class="text-center">Descripcion</th>
                        <th class="text-center">Resultado</th>
                        <th class="text-center">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado['filas'] as $fila) { ?>
                    <tr>
                        <td class="text-center"><?= (int) $fila['fila']; ?></td>
                        <td class="text-center"><?= inv_e($fila['codigo_unico']); ?></td>
                        <td><?= isset($fila['datos']['descripcion']) ? inv_e($fila['datos']['descripcion']) : '-'; ?></td>
                        <td class="text-center"><?= inv_badge_resultado_importacion($fila['resultado']); ?></td>
                        <td><?= empty($fila['mensajes']) ? '-' : inv_e(implode(' / ', $fila['mensajes'])); ?></td>
                    </tr>
                    <?php } ?>

                    <?php if (empty($resultado['filas'])) { ?>
                    <tr>
                        <td colspan="5" class="text-muted">La hoja "Cruce Activos" no tiene filas con datos.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <script>
                $(function(){
                    $('.table').dataTable({
                        bStateSave : false,
                        pageLength : 100
                    });
                });
            </script>
        </div>

        <?php if (($resultado['resumen']['nuevos'] + $resultado['resumen']['con_advertencias']) > 0) { ?>
        <form method="post" action="<?= site_url('inventario/importar/confirmar'); ?>" onsubmit="return confirm('Se importaran ' + <?= (int) ($resultado['resumen']['nuevos'] + $resultado['resumen']['con_advertencias']); ?> + ' activo(s) nuevo(s). Esta accion no se puede deshacer. Continuar?');">
            <?= inv_csrf_campo(); ?>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Confirmar Importacion</button>
                </div>
            </div>
        </form>
        <?php } ?>

        <form method="post" action="<?= site_url('inventario/importar/cancelar'); ?>" style="margin-top:10px;">
            <?= inv_csrf_campo(); ?>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <button type="submit" class="btn btn-default btn-block"><i class="fa fa-times"></i> Cancelar</button>
                </div>
            </div>
        </form>

        <?php } ?>

    </div>
</div>
