<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Cambiar estado <i class="fa fa-angle-right"></i> <?= inv_e($activo['codigo_unico']); ?></h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-flag"></i> Cambiar estado</div>
    <div class="panel-body">
        <form method="post" action="<?= site_url('inventario/actualizar_estado'); ?>">
            <?= inv_csrf_campo(); ?>
            <input type="hidden" name="id" value="<?= (int) $activo['id']; ?>">

            <div class="form-group">
                <label>Estado actual</label>
                <p><?= $activo['estado_nombre'] ? inv_e($activo['estado_nombre']) : 'Sin estado registrado'; ?></p>
            </div>

            <div class="form-group">
                <label>Nuevo estado *</label>
                <select name="estado_id" class="form-control" required autofocus>
                    <option value="">Seleccione</option>
                    <?php foreach ($estados as $estado) { ?>
                    <option value="<?= (int) $estado['id']; ?>" <?= ($activo['estado_id'] == $estado['id']) ? 'selected' : ''; ?>><?= inv_e($estado['nombre']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Motivo</label>
                <input type="text" name="motivo" class="form-control" maxlength="255">
            </div>

            <div class="form-group">
                <label>Observacion</label>
                <textarea name="observacion" class="form-control" rows="2"></textarea>
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('inventario/detalle/' . $activo['id']); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Confirmar Cambio</button>
                </div>
            </div>
        </form>
    </div>
</div>
