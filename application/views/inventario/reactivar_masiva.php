<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Reactivacion masiva</h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-refresh"></i> Reactivacion masiva</div>
    <div class="panel-body">
        <div class="alert alert-info">
            Se van a reactivar <strong><?= count($activos); ?> activo(s)</strong> actualmente dados de baja. Como el
            estado anterior puede ya no ser valido, se debe indicar un estado nuevo para todos.
        </div>

        <div class="table-responsive" style="max-height: 300px; overflow-y: auto; margin-bottom: 15px;">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr><th>Codigo Unico</th><th>Descripcion</th><th>Estado actual</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($activos as $activo) { ?>
                    <tr>
                        <td><?= inv_e($activo['codigo_unico']); ?></td>
                        <td><?= inv_e($activo['descripcion']); ?></td>
                        <td><?= inv_badge_estado($activo); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <form method="post" action="<?= site_url('inventario/procesar_reactivar_masiva'); ?>">
            <?= inv_csrf_campo(); ?>

            <div class="form-group">
                <label>Nuevo estado para los activos reactivados *</label>
                <select name="estado_id" class="form-control" required autofocus>
                    <option value="">Seleccione</option>
                    <?php foreach ($estados as $estado) { ?>
                    <option value="<?= (int) $estado['id']; ?>"><?= inv_e($estado['nombre']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Motivo *</label>
                <textarea name="motivo" class="form-control" rows="3" required></textarea>
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('inventario/cancelar_masivo'); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Confirmar Reactivacion</button>
                </div>
            </div>
        </form>
    </div>
</div>
