<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Cambiar responsable <i class="fa fa-angle-right"></i> <?= inv_e($activo['codigo_unico']); ?></h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-user"></i> Cambiar responsable</div>
    <div class="panel-body">
        <form method="post" action="<?= site_url('inventario/actualizar_responsable'); ?>">
            <?= inv_csrf_campo(); ?>
            <input type="hidden" name="id" value="<?= (int) $activo['id']; ?>">

            <div class="form-group">
                <label>Responsable actual</label>
                <p><?= $activo['responsable_nombre'] ? inv_e($activo['responsable_nombre']) : 'Sin responsable asignado'; ?></p>
            </div>

            <div class="form-group">
                <label>Nuevo responsable *</label>
                <select name="responsable_id" class="form-control" required autofocus>
                    <option value="">Seleccione</option>
                    <?php foreach ($responsables as $responsable) { ?>
                    <option value="<?= (int) $responsable['id']; ?>" <?= ($activo['responsable_id'] == $responsable['id']) ? 'selected' : ''; ?>><?= inv_e($responsable['nombre']); ?><?= $responsable['cargo'] ? ' (' . inv_e($responsable['cargo']) . ')' : ''; ?></option>
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
