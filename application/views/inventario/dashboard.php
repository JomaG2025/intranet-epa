<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario de Activos</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('inventario/listado'); ?>" class="btn btn-default"><i class="fa fa-list"></i> Ver listado</a>
    <?php if ($puede_editar) { ?>
    <a href="<?= site_url('inventario/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Activo</a>
    <?php } ?>
    <?php if ($puede_administrar) { ?>
    <a href="<?= site_url('inventario/importar'); ?>" class="btn btn-default"><i class="fa fa-upload"></i> Importar Excel</a>
    <?php } ?>
</div>

<h4 class="visible-xs"><i class="fa fa-cubes"></i> Inventario de Activos</h4>

<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="panel panel-default no-border">
            <div class="panel-body text-center">
                <h2><?= (int) $total_operativos; ?></h2>
                <p class="text-muted">Activos vigentes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="panel panel-default no-border">
            <div class="panel-body text-center">
                <h2><?= (int) $total_baja; ?></h2>
                <p class="text-muted">Dados de baja</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="panel panel-default no-border">
            <div class="panel-body text-center">
                <?php if ($puede_editar) { ?>
                <a href="<?= site_url('inventario/nuevo'); ?>"><i class="fa fa-plus-circle fa-2x"></i></a>
                <?php } else { ?>
                <i class="fa fa-plus-circle fa-2x text-muted"></i>
                <?php } ?>
                <p class="text-muted">Registrar activo</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="panel panel-default no-border">
            <div class="panel-body text-center">
                <a href="<?= site_url('inventario/listado'); ?>"><i class="fa fa-search fa-2x"></i></a>
                <p class="text-muted">Buscar activos</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default no-border">
            <div class="panel-heading">Activos vigentes por estado</div>
            <div class="panel-body">
                <?php if (empty($por_estado)) { ?>
                    <p class="text-muted">Sin datos todavia.</p>
                <?php } else { ?>
                    <table class="table table-condensed">
                        <?php foreach ($por_estado as $fila) { ?>
                        <tr>
                            <td><?= $fila['estado'] ? inv_e($fila['estado']) : 'Sin estado'; ?></td>
                            <td class="text-right"><strong><?= (int) $fila['total']; ?></strong></td>
                        </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default no-border">
            <div class="panel-heading">Activos vigentes por area</div>
            <div class="panel-body">
                <?php if (empty($por_area)) { ?>
                    <p class="text-muted">Sin datos todavia.</p>
                <?php } else { ?>
                    <table class="table table-condensed">
                        <?php foreach ($por_area as $fila) { ?>
                        <tr>
                            <td><?= $fila['area'] ? inv_e($fila['area']) : 'Sin area'; ?></td>
                            <td class="text-right"><strong><?= (int) $fila['total']; ?></strong></td>
                        </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if ($puede_administrar) { ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default no-border">
            <div class="panel-heading">Administracion de catalogos</div>
            <div class="panel-body">
                <a href="<?= site_url('admin/inventario_catalogos/index/gerencias'); ?>" class="btn btn-default btn-sm">Gerencias</a>
                <a href="<?= site_url('admin/inventario_catalogos/index/areas'); ?>" class="btn btn-default btn-sm">Areas</a>
                <a href="<?= site_url('admin/inventario_catalogos/index/ubicaciones'); ?>" class="btn btn-default btn-sm">Ubicaciones</a>
                <a href="<?= site_url('admin/inventario_catalogos/index/categorias'); ?>" class="btn btn-default btn-sm">Categorias</a>
                <a href="<?= site_url('admin/inventario_catalogos/index/subcategorias'); ?>" class="btn btn-default btn-sm">Subcategorias</a>
                <a href="<?= site_url('admin/inventario_catalogos/index/tipos'); ?>" class="btn btn-default btn-sm">Tipos</a>
                <a href="<?= site_url('admin/inventario_catalogos/index/responsables'); ?>" class="btn btn-default btn-sm">Responsables</a>
                <a href="<?= site_url('admin/inventario_catalogos/index/estados'); ?>" class="btn btn-default btn-sm">Estados</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default no-border">
            <div class="panel-heading">Importacion de datos</div>
            <div class="panel-body">
                <a href="<?= site_url('inventario/importar'); ?>" class="btn btn-info btn-sm"><i class="fa fa-upload"></i> Importar Excel</a>
                <a href="<?= site_url('inventario/importaciones'); ?>" class="btn btn-default btn-sm"><i class="fa fa-history"></i> Historial de importaciones</a>
            </div>
        </div>
    </div>
</div>
<?php } ?>
