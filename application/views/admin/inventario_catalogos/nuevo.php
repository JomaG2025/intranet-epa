<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administracion <i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Nuevo <?= inv_e($titulo); ?></h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Nuevo <?= inv_e($titulo); ?></div>
    <div class="panel-body">
        <form method="post" action="<?= site_url('admin/inventario_catalogos/grabar/' . $tipo); ?>" class="form-horizontal">
            <?= inv_csrf_campo(); ?>

            <?php if ($tipo == 'subcategorias') { ?>
            <div class="form-group col-sm-6">
                <label>Categoria *</label>
                <select name="categoria_id" class="form-control" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($categorias as $categoria) { ?>
                    <option value="<?= (int) $categoria['id']; ?>"><?= inv_e($categoria['nombre']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>

            <div class="form-group col-sm-6">
                <label>Nombre *</label>
                <input type="text" name="nombre" class="form-control" maxlength="150" required autofocus autocomplete="off">
            </div>

            <?php if ($tipo == 'responsables') { ?>
            <div class="form-group col-sm-6">
                <label>Cargo</label>
                <input type="text" name="cargo" class="form-control" maxlength="150" autocomplete="off">
            </div>
            <?php } ?>

            <div class="clearfix"></div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('admin/inventario_catalogos/index/' . $tipo); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
