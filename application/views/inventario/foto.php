<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Fotografias <i class="fa fa-angle-right"></i> <?= inv_e($activo['codigo_unico']); ?></h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('inventario/detalle/' . $activo['id']); ?>" class="btn btn-default"><i class="fa fa-reply"></i> Volver al activo</a>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-camera"></i> Fotografias</div>
    <div class="panel-body">

        <form method="post" action="<?= site_url('inventario/subir_foto'); ?>" enctype="multipart/form-data">
            <?= inv_csrf_campo(); ?>
            <input type="hidden" name="id" value="<?= (int) $activo['id']; ?>">
            <div class="row">
                <div class="form-group col-sm-8">
                    <label>Nueva fotografia (JPG, JPEG o PNG, maximo 4 MB)</label>
                    <input type="file" name="imagen" accept="image/jpeg,image/png" required>
                </div>
                <div class="form-group col-sm-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-info btn-block"><i class="fa fa-upload"></i> Subir Fotografia</button>
                </div>
            </div>
        </form>

        <hr>

        <div class="row">
            <?php foreach ($fotos as $foto) { ?>
            <div class="col-sm-3 text-center" style="margin-bottom: 20px;">
                <img src="<?= base_url('uploads/inventario/fotos/' . rawurlencode($foto['archivo'])); ?>" class="img-responsive img-thumbnail">
                <?php if ($foto['principal'] == 1) { ?>
                <span class="label label-success">Principal</span>
                <?php } else { ?>
                <form method="post" action="<?= site_url('inventario/foto_principal'); ?>" style="display:inline;">
                    <?= inv_csrf_campo(); ?>
                    <input type="hidden" name="foto_id" value="<?= (int) $foto['id']; ?>">
                    <button type="submit" class="btn btn-xs btn-default">Marcar como principal</button>
                </form>
                <?php } ?>
                <form method="post" action="<?= site_url('inventario/eliminar_foto'); ?>" style="display:inline;" class="form-eliminar-foto">
                    <?= inv_csrf_campo(); ?>
                    <input type="hidden" name="foto_id" value="<?= (int) $foto['id']; ?>">
                    <button type="submit" class="btn btn-xs btn-danger btn-eliminar">Eliminar</button>
                </form>
            </div>
            <?php } ?>

            <?php if (empty($fotos)) { ?>
            <div class="col-sm-12">
                <p class="text-muted">Este activo aun no tiene fotografias.</p>
            </div>
            <?php } ?>
        </div>

    </div>
</div>
