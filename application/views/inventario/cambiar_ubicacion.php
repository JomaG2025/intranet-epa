<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Cambiar ubicacion <i class="fa fa-angle-right"></i> <?= inv_e($activo['codigo_unico']); ?></h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-map-marker"></i> Cambiar ubicacion</div>
    <div class="panel-body">
        <form method="post" action="<?= site_url('inventario/actualizar_ubicacion'); ?>">
            <?= inv_csrf_campo(); ?>
            <input type="hidden" name="id" value="<?= (int) $activo['id']; ?>">

            <div class="form-group">
                <label>Ubicacion actual</label>
                <p><?= $activo['ubicacion_nombre'] ? inv_e($activo['ubicacion_nombre']) : 'Sin ubicacion registrada'; ?></p>
            </div>

            <div class="form-group">
                <label>Nueva ubicacion *</label>
                <select name="ubicacion_id" class="form-control" required autofocus>
                    <option value="">Seleccione</option>
                    <?php foreach ($ubicaciones as $ubicacion) { ?>
                    <option value="<?= (int) $ubicacion['id']; ?>" <?= ($activo['ubicacion_id'] == $ubicacion['id']) ? 'selected' : ''; ?>><?= inv_e($ubicacion['nombre']); ?></option>
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
