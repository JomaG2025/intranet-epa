<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Cambiar ubicacion (masivo)</h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-map-marker"></i> Cambiar ubicacion (masivo)</div>
    <div class="panel-body">
        <div class="alert alert-info">
            Se va a cambiar la ubicacion de <strong><?= count($activos); ?> activo(s)</strong>. El cambio queda
            registrado individualmente en el historial de cada activo.
        </div>

        <div class="table-responsive" style="max-height: 300px; overflow-y: auto; margin-bottom: 15px;">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr><th>Codigo Unico</th><th>Descripcion</th><th>Ubicacion actual</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($activos as $activo) { ?>
                    <tr>
                        <td><?= inv_e($activo['codigo_unico']); ?></td>
                        <td><?= inv_e($activo['descripcion']); ?></td>
                        <td><?= $activo['ubicacion_nombre'] ? inv_e($activo['ubicacion_nombre']) : '-'; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <form method="post" action="<?= site_url('inventario/procesar_cambiar_ubicacion_masiva'); ?>">
            <?= inv_csrf_campo(); ?>

            <div class="form-group">
                <label>Nueva ubicacion *</label>
                <select name="ubicacion_id" class="form-control" required autofocus>
                    <option value="">Seleccione</option>
                    <?php foreach ($ubicaciones as $ubicacion) { ?>
                    <option value="<?= (int) $ubicacion['id']; ?>"><?= inv_e($ubicacion['nombre']); ?></option>
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
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aplicar Cambio</button>
                </div>
            </div>
        </form>
    </div>
</div>
