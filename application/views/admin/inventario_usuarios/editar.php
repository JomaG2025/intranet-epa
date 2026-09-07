<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios <i class="fa fa-angle-right"></i> Editar</h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-users"></i> Editar Usuario</div>
    <div class="panel-body">

        <form method="post" action="<?= site_url('inventario/admin/usuarios/actualizar'); ?>">
            <?= inv_csrf_campo(); ?>
            <input type="hidden" name="id" value="<?= (int) $usuario['id']; ?>">

            <div class="form-group">
                <label>Usuario</label>
                <p>
                    <?= $usuario['usuario_nombre'] ? inv_e($usuario['usuario_nombre']) : '-'; ?>
                    (<?= $usuario['usuario_login'] ? inv_e($usuario['usuario_login']) : '-'; ?>)
                </p>
            </div>

            <div class="form-group">
                <label>Privilegio en Inventarios *</label>
                <select name="privilegio" class="form-control" required autofocus>
                    <option value="ADM" <?= ($usuario['privilegio'] == 'ADM') ? 'selected' : ''; ?>>Administrador</option>
                    <option value="EDI" <?= ($usuario['privilegio'] == 'EDI') ? 'selected' : ''; ?>>Editor</option>
                    <option value="CON" <?= ($usuario['privilegio'] == 'CON') ? 'selected' : ''; ?>>Consulta</option>
                </select>
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('inventario/admin/usuarios'); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
