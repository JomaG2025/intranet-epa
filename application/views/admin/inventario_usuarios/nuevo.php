<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios <i class="fa fa-angle-right"></i> Nuevo</h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-users"></i> Nuevo Usuario</div>
    <div class="panel-body">

        <?php if (empty($usuarios_intranet)) { ?>
        <div class="alert alert-info">
            No hay usuarios de la intranet disponibles para autorizar: o no hay usuarios activos, o todos los
            usuarios activos ya están registrados en el módulo.
        </div>
        <p><a href="<?= site_url('inventario/admin/usuarios'); ?>" class="btn btn-default"><i class="fa fa-reply"></i> Volver</a></p>
        <?php } else { ?>

        <form method="post" action="<?= site_url('inventario/admin/usuarios/grabar'); ?>">
            <?= inv_csrf_campo(); ?>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Usuario (de la intranet) *</label>
                    <select name="usuario_id" class="form-control" required autofocus>
                        <option value="">Seleccione</option>
                        <?php foreach ($usuarios_intranet as $u) { ?>
                        <option value="<?= (int) $u['id']; ?>"><?= inv_e($u['nombre']); ?> (<?= inv_e($u['usuario']); ?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Privilegio en Inventarios *</label>
                    <select name="privilegio" class="form-control" required>
                        <option value="ADM">Administrador</option>
                        <option value="EDI">Editor</option>
                        <option value="CON">Consulta</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('inventario/admin/usuarios'); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
                </div>
            </div>
        </form>
        <?php } ?>

    </div>
</div>
