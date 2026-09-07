<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Historial de Importaciones</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('inventario/importar'); ?>" class="btn btn-info"><i class="fa fa-upload"></i> Importar Excel</a>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-history"></i> Historial de importaciones</div>
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Archivo</th>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Insertados</th>
                        <th class="text-center">Omitidos</th>
                        <th class="text-center">Errores</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($importaciones as $imp) { ?>
                    <tr>
                        <td><?= inv_e(date('d-m-Y H:i', strtotime($imp['fecha']))); ?></td>
                        <td class="text-left"><?= inv_e($imp['nombre_archivo']); ?></td>
                        <td><?= $imp['usuario_nombre'] ? inv_e($imp['usuario_nombre']) : '-'; ?></td>
                        <td><?= (int) $imp['total_filas']; ?></td>
                        <td class="text-success"><?= (int) $imp['insertados']; ?></td>
                        <td><?= (int) $imp['omitidos']; ?></td>
                        <td class="<?= ($imp['errores'] > 0) ? 'text-danger' : ''; ?>"><?= (int) $imp['errores']; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if (empty($importaciones)) { ?>
                    <tr>
                        <td colspan="7" class="text-muted">Todavia no se ha confirmado ninguna importacion.</td>
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

    </div>
</div>
