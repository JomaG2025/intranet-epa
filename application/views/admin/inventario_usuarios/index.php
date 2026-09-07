<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('inventario/admin/usuarios/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Usuario</a>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs">
        <i class="fa fa-users"></i> Usuarios
        <a href="<?= site_url('inventario/admin/usuarios/nuevo'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo</a>
    </div>
    <div class="panel-body">

        <div class="alert alert-info">
            Solo se pueden autorizar usuarios que ya existan en la intranet. Esta pantalla no crea cuentas nuevas,
            solo autoriza o desautoriza el acceso al módulo Inventario y define su privilegio dentro de él.
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Privilegio Inventarios</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($usuarios as $u) { ?>
                    <tr>
                        <td><?= $u['usuario_login'] ? inv_e($u['usuario_login']) : '-'; ?></td>
                        <td class="text-left"><?= $u['usuario_nombre'] ? inv_e($u['usuario_nombre']) : '(usuario no encontrado en la intranet)'; ?></td>
                        <td><?= inv_e($u['privilegio']); ?></td>
                        <td>
                            <form method="post" action="<?= site_url('inventario/admin/usuarios/activo'); ?>" style="display:inline;">
                                <?= inv_csrf_campo(); ?>
                                <input type="hidden" name="id" value="<?= (int) $u['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-block btn-<?= ($u['activo'] == 1) ? 'success' : 'danger'; ?>"><?= ($u['activo'] == 1) ? '<i class="fa fa-check"></i> Activo' : '<i class="fa fa-times"></i> Inactivo'; ?></button>
                            </form>
                        </td>
                        <td><a href="<?= site_url('inventario/admin/usuarios/editar/' . $u['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
                    </tr>
                    <?php } ?>

                    <?php if (empty($usuarios)) { ?>
                    <tr>
                        <td colspan="5" class="text-muted">Todavía no hay usuarios autorizados en el módulo.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <script>
                $(function(){
                    $('.table').dataTable({
                        bStateSave : false,
                        pageLength : 100
                    });
                });
            </script>
        </div>

    </div>
</div>
