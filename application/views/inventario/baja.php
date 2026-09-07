<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Dar de baja <?= inv_e($activo['codigo_unico']); ?></h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-ban"></i> Dar de baja</div>
    <div class="panel-body">
        <div class="alert alert-warning">
            <strong>Atencion!</strong> Esta accion marca el activo como dado de baja. El registro <strong>no se elimina</strong>
            y puede reactivarse mas adelante desde la ficha del activo.
        </div>

        <form method="post" action="<?= site_url('inventario/procesar_baja'); ?>">
            <?= inv_csrf_campo(); ?>
            <input type="hidden" name="id" value="<?= (int) $activo['id']; ?>">

            <div class="form-group">
                <label>Activo</label>
                <p><?= inv_e($activo['codigo_unico']); ?> - <?= inv_e($activo['descripcion']); ?></p>
            </div>

            <div class="form-group">
                <label>Motivo de la baja *</label>
                <input type="text" name="motivo" class="form-control" maxlength="255" required autofocus placeholder="Ej: Equipo obsoleto, dano irreparable, robo, etc.">
            </div>

            <div class="form-group">
                <label>Observacion</label>
                <textarea name="observacion" class="form-control" rows="3"></textarea>
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('inventario/detalle/' . $activo['id']); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-danger btn-block"><i class="fa fa-ban"></i> Confirmar Baja</button>
                </div>
            </div>
        </form>
    </div>
</div>
