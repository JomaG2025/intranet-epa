<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administracion <i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> <?= inv_e($titulo); ?></h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('admin/inventario_catalogos/nuevo/' . $tipo); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo</a>
</div>

<ul class="nav nav-tabs" style="margin-bottom: 15px;">
    <?php foreach ($tipos as $clave => $meta) { ?>
    <li class="<?= ($clave == $tipo) ? 'active' : ''; ?>">
        <a href="<?= site_url('admin/inventario_catalogos/index/' . $clave); ?>"><?= inv_e($meta['titulo']); ?></a>
    </li>
    <?php } ?>
</ul>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> <?= inv_e($titulo); ?><a href="<?= site_url('admin/inventario_catalogos/nuevo/' . $tipo); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo</a></div>

    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <th class="text-center">id</th>
                        <th class="text-center">Nombre</th>
                        <?php if ($tipo == 'subcategorias') { ?>
                        <th class="text-center">Categoria</th>
                        <?php } ?>
                        <?php if ($tipo == 'responsables') { ?>
                        <th class="text-center">Cargo</th>
                        <?php } ?>
                        <th class="text-center">Estado</th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($registros as $registro) { ?>
                    <tr>
                        <td style="width: 5%;"><?= (int) $registro['id']; ?></td>
                        <td class="text-left"><a href="<?= site_url('admin/inventario_catalogos/editar/' . $tipo . '/' . $registro['id']); ?>"><?= inv_e($registro['nombre']); ?></a></td>
                        <?php if ($tipo == 'subcategorias') { ?>
                        <td><?= inv_e($registro['categoria_nombre']); ?></td>
                        <?php } ?>
                        <?php if ($tipo == 'responsables') { ?>
                        <td><?= $registro['cargo'] ? inv_e($registro['cargo']) : '-'; ?></td>
                        <?php } ?>
                        <td>
                            <form method="post" action="<?= site_url('admin/inventario_catalogos/activo'); ?>">
                                <?= inv_csrf_campo(); ?>
                                <input type="hidden" name="tipo" value="<?= inv_e($tipo); ?>">
                                <input type="hidden" name="id" value="<?= (int) $registro['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-block btn-<?= ($registro['activo'] == 1) ? 'success' : 'danger'; ?>">
                                    <?= ($registro['activo'] == 1) ? '<i class="fa fa-check"></i> Activo' : '<i class="fa fa-times"></i> Inactivo'; ?>
                                </button>
                            </form>
                        </td>
                        <td><a href="<?= site_url('admin/inventario_catalogos/editar/' . $tipo . '/' . $registro['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
                        <td></td>
                    </tr>
                    <?php } ?>

                    <?php if (empty($registros)) { ?>
                    <tr>
                        <td colspan="6" class="text-muted">Sin registros todavia.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <script>
                $(function(){
                    $('.table').dataTable({
                        bStateSave : true,
                        fnStateSave :function(settings,data){
                            localStorage.setItem("dataTables_state", JSON.stringify(data));
                        },
                        fnStateLoad: function(settings) {
                            return JSON.parse(localStorage.getItem("dataTables_state"));
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>
