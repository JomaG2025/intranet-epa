<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Listado de Activos</h4>
</div>
<div class="navbar-action hidden-xs">
    <?php if ($puede_editar) { ?>
    <a href="<?= site_url('inventario/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Activo</a>
    <?php } ?>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs">
        <i class="fa fa-cubes"></i> Listado de Activos
        <?php if ($puede_editar) { ?>
        <a href="<?= site_url('inventario/nuevo'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo</a>
        <?php } ?>
    </div>

    <div class="panel-body">

        <form method="get" action="<?= site_url('inventario/listado'); ?>" class="form-horizontal">
            <div class="row">
                <div class="col-md-4 col-sm-6 form-group">
                    <input type="text" name="buscar" class="form-control" placeholder="Codigo, descripcion, marca, modelo o N. serie" value="<?= $filtros['buscar'] ? inv_e($filtros['buscar']) : ''; ?>">
                </div>
                <div class="col-md-2 col-sm-6 form-group">
                    <select name="area_id" class="form-control">
                        <option value="">Area (todas)</option>
                        <?php foreach ($areas as $area) { ?>
                        <option value="<?= (int) $area['id']; ?>" <?= ($filtros['area_id'] == $area['id']) ? 'selected' : ''; ?>><?= inv_e($area['nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6 form-group">
                    <select name="categoria_id" class="form-control">
                        <option value="">Categoria (todas)</option>
                        <?php foreach ($categorias as $categoria) { ?>
                        <option value="<?= (int) $categoria['id']; ?>" <?= ($filtros['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>><?= inv_e($categoria['nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6 form-group">
                    <select name="estado_id" class="form-control">
                        <option value="">Estado (todos)</option>
                        <?php foreach ($estados as $estado) { ?>
                        <option value="<?= (int) $estado['id']; ?>" <?= ($filtros['estado_id'] == $estado['id']) ? 'selected' : ''; ?>><?= inv_e($estado['nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6 form-group">
                    <select name="dado_baja" class="form-control">
                        <option value="0" <?= (empty($filtros['dado_baja'])) ? 'selected' : ''; ?>>Vigentes</option>
                        <option value="1" <?= ($filtros['dado_baja'] === '1') ? 'selected' : ''; ?>>Dados de baja</option>
                        <option value="todos" <?= ($filtros['dado_baja'] === 'todos') ? 'selected' : ''; ?>>Todos</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 form-group">
                    <select name="gerencia_id" class="form-control">
                        <option value="">Gerencia (todas)</option>
                        <?php foreach ($gerencias as $gerencia) { ?>
                        <option value="<?= (int) $gerencia['id']; ?>" <?= ($filtros['gerencia_id'] == $gerencia['id']) ? 'selected' : ''; ?>><?= inv_e($gerencia['nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 form-group">
                    <select name="subcategoria_id" class="form-control">
                        <option value="">Subcategoria (todas)</option>
                        <?php foreach ($subcategorias as $subcategoria) { ?>
                        <option value="<?= (int) $subcategoria['id']; ?>" <?= ($filtros['subcategoria_id'] == $subcategoria['id']) ? 'selected' : ''; ?>><?= inv_e($subcategoria['nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6 form-group">
                    <select name="tipo_id" class="form-control">
                        <option value="">Tipo (todos)</option>
                        <?php foreach ($tipos as $tipo) { ?>
                        <option value="<?= (int) $tipo['id']; ?>" <?= ($filtros['tipo_id'] == $tipo['id']) ? 'selected' : ''; ?>><?= inv_e($tipo['nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6 form-group">
                    <select name="ubicacion_id" class="form-control">
                        <option value="">Ubicacion (todas)</option>
                        <?php foreach ($ubicaciones as $ubicacion) { ?>
                        <option value="<?= (int) $ubicacion['id']; ?>" <?= ($filtros['ubicacion_id'] == $ubicacion['id']) ? 'selected' : ''; ?>><?= inv_e($ubicacion['nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Filtrar</button>
                </div>
            </div>
        </form>

        <?php if ($puede_administrar) { ?>
        <style>
            .dropdown-menu button.inv-item-menu { display: block; width: 100%; padding: 3px 20px; clear: both;
                font-weight: normal; text-align: left; white-space: nowrap; background: none; border: 0; }
            .dropdown-menu button.inv-item-menu:hover { background-color: #f5f5f5; }
        </style>
        <?php } ?>

        <form method="post" id="form-masivo" action="<?= site_url('inventario/baja_masiva'); ?>">
        <?php if ($puede_administrar) { ?>
            <?= inv_csrf_campo(); ?>
        <?php } ?>

        <div class="table-responsive">
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <?php if ($puede_administrar) { ?>
                        <th class="text-center"><input type="checkbox" id="inv-chk-todos" title="Seleccionar todos"></th>
                        <?php } ?>
                        <th class="text-center">Codigo</th>
                        <th class="text-center">Descripcion</th>
                        <th class="text-center">Categoria</th>
                        <th class="text-center">Ubicacion</th>
                        <th class="text-center">Responsable</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($activos as $activo) { ?>
                    <tr>
                        <?php if ($puede_administrar) { ?>
                        <td><input type="checkbox" name="ids[]" value="<?= (int) $activo['id']; ?>" class="inv-chk-fila"></td>
                        <?php } ?>
                        <td><a href="<?= site_url('inventario/detalle/' . $activo['id']); ?>"><?= inv_e($activo['codigo_unico']); ?></a></td>
                        <td class="text-left"><?= inv_e($activo['descripcion']); ?></td>
                        <td><?= inv_e($activo['categoria_nombre']); ?></td>
                        <td><?= $activo['ubicacion_nombre'] ? inv_e($activo['ubicacion_nombre']) : '-'; ?></td>
                        <td><?= $activo['responsable_nombre'] ? inv_e($activo['responsable_nombre']) : '-'; ?></td>
                        <td><?= inv_badge_estado($activo); ?></td>
                        <td><a href="<?= site_url('inventario/detalle/' . $activo['id']); ?>" class="btn btn-sm btn-default"><i class="fa fa-eye"></i> Ver</a></td>
                    </tr>
                    <?php } ?>

                    <?php if (empty($activos)) { ?>
                    <tr>
                        <td colspan="<?= $puede_administrar ? 8 : 7; ?>" class="text-muted">No se encontraron activos con los filtros seleccionados.</td>
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

                    // "Seleccionar todos" solo marca las filas visibles: al
                    // paginar, DataTables saca del DOM las filas de otras
                    // paginas, asi que $('.inv-chk-fila') aqui solo encuentra
                    // las de la pagina actual.
                    $('#inv-chk-todos').on('change', function(){
                        $('.inv-chk-fila').prop('checked', $(this).is(':checked'));
                    });

                    $('#form-masivo').on('submit', function(e){
                        if ($('.inv-chk-fila:checked').length === 0)
                        {
                            e.preventDefault();
                            alert('Selecciona al menos un activo.');
                        }
                    });
                });
            </script>
        </div>

        <?php if ($puede_administrar) { ?>
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="inv-menu-masivo" data-toggle="dropdown">
                <i class="fa fa-cogs"></i> Acciones masivas <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="inv-menu-masivo">
                <li><button type="submit" class="inv-item-menu" formaction="<?= site_url('inventario/baja_masiva'); ?>"><i class="fa fa-ban"></i> Dar de baja seleccionados</button></li>
                <li><button type="submit" class="inv-item-menu" formaction="<?= site_url('inventario/reactivar_masiva'); ?>"><i class="fa fa-refresh"></i> Reactivar seleccionados</button></li>
                <li role="separator" class="divider"></li>
                <li><button type="submit" class="inv-item-menu" formaction="<?= site_url('inventario/cambiar_estado_masiva'); ?>"><i class="fa fa-flag"></i> Cambiar estado</button></li>
                <li><button type="submit" class="inv-item-menu" formaction="<?= site_url('inventario/cambiar_ubicacion_masiva'); ?>"><i class="fa fa-map-marker"></i> Cambiar ubicacion</button></li>
                <li><button type="submit" class="inv-item-menu" formaction="<?= site_url('inventario/cambiar_responsable_masiva'); ?>"><i class="fa fa-user"></i> Cambiar responsable</button></li>
            </ul>
        </div>
        <?php } ?>

        </form>

    </div>
</div>
