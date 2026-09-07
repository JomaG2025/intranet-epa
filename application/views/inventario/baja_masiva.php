<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Baja masiva</h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-ban"></i> Baja masiva</div>
    <div class="panel-body">
        <div class="alert alert-warning">
            <strong>Atencion!</strong> Se van a dar de baja <strong><?= count($activos); ?> activo(s)</strong>.
            Esta accion marca cada activo como dado de baja (no se elimina ningun registro) y queda registrada en el
            historial de cada uno.
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

        <form method="post" action="<?= site_url('inventario/procesar_baja_masiva'); ?>">
            <?= inv_csrf_campo(); ?>

            <div class="form-group">
                <label>Motivo de la baja masiva *</label>
                <textarea name="motivo" class="form-control" rows="3" required autofocus placeholder="Ej: Resultado de toma de inventario 2026, equipos dados de baja segun acta N°..."></textarea>
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('inventario/cancelar_masivo'); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Confirma dar de baja <?= count($activos); ?> activo(s)? Esta accion no se puede deshacer.');"><i class="fa fa-ban"></i> Confirmar Baja Masiva</button>
                </div>
            </div>
        </form>
    </div>
</div>
