<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Editar Activo <?= inv_e($activo['codigo_unico']); ?></h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-cubes"></i> Editar Activo</div>
    <div class="panel-body">
        <form method="post" action="<?= site_url('inventario/actualizar'); ?>">
            <?= inv_csrf_campo(); ?>
            <input type="hidden" name="id" value="<?= (int) $activo['id']; ?>">

            <fieldset>
                <legend>Identificacion</legend>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label>Codigo unico *</label>
                        <input type="text" name="codigo_unico" class="form-control" maxlength="30" required autocomplete="off" value="<?= inv_e($activo['codigo_unico']); ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Codigo</label>
                        <input type="text" name="codigo" class="form-control" maxlength="20" autocomplete="off" value="<?= inv_e($activo['codigo']); ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Grupo</label>
                        <input type="text" name="grupo" class="form-control" maxlength="30" autocomplete="off" value="<?= inv_e($activo['grupo']); ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Unidad</label>
                        <input type="number" name="unidad" class="form-control" min="1" value="<?= (int) $activo['unidad']; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Descripcion *</label>
                        <textarea name="descripcion" class="form-control" rows="2" required><?= inv_e($activo['descripcion']); ?></textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Clasificacion</legend>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label>Gerencia</label>
                        <select name="gerencia_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($gerencias as $gerencia) { ?>
                            <option value="<?= (int) $gerencia['id']; ?>" <?= ($activo['gerencia_id'] == $gerencia['id']) ? 'selected' : ''; ?>><?= inv_e($gerencia['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Area</label>
                        <select name="area_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($areas as $area) { ?>
                            <option value="<?= (int) $area['id']; ?>" <?= ($activo['area_id'] == $area['id']) ? 'selected' : ''; ?>><?= inv_e($area['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Categoria *</label>
                        <select name="categoria_id" class="form-control" required>
                            <option value="">Seleccione</option>
                            <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?= (int) $categoria['id']; ?>" <?= ($activo['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>><?= inv_e($categoria['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Subcategoria</label>
                        <select name="subcategoria_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($subcategorias as $subcategoria) { ?>
                            <option value="<?= (int) $subcategoria['id']; ?>" <?= ($activo['subcategoria_id'] == $subcategoria['id']) ? 'selected' : ''; ?>><?= inv_e($subcategoria['categoria_nombre']); ?> - <?= inv_e($subcategoria['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label>Tipo</label>
                        <select name="tipo_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($tipos as $tipo) { ?>
                            <option value="<?= (int) $tipo['id']; ?>" <?= ($activo['tipo_id'] == $tipo['id']) ? 'selected' : ''; ?>><?= inv_e($tipo['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" maxlength="80" autocomplete="off" value="<?= inv_e($activo['marca']); ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control" maxlength="80" autocomplete="off" value="<?= inv_e($activo['modelo']); ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control" maxlength="40" autocomplete="off" value="<?= inv_e($activo['color']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label>N. de Serie</label>
                        <input type="text" name="numero_serie" class="form-control" maxlength="80" autocomplete="off" value="<?= inv_e($activo['numero_serie']); ?>">
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Ubicacion y responsable</legend>
                <div class="alert alert-info">
                    Estos 3 datos son de solo lectura aqui. Para modificarlos y que quede registro en el historial, usa las pantallas dedicadas.
                </div>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label>Ubicacion fisica</label>
                        <p class="form-control-static"><?= $activo['ubicacion_nombre'] ? inv_e($activo['ubicacion_nombre']) : '-'; ?></p>
                        <a href="<?= site_url('inventario/cambiar_ubicacion/' . $activo['id']); ?>" class="btn btn-xs btn-default"><i class="fa fa-exchange"></i> Cambiar ubicacion</a>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Responsable</label>
                        <p class="form-control-static"><?= $activo['responsable_nombre'] ? inv_e($activo['responsable_nombre']) : '-'; ?></p>
                        <a href="<?= site_url('inventario/cambiar_responsable/' . $activo['id']); ?>" class="btn btn-xs btn-default"><i class="fa fa-exchange"></i> Cambiar responsable</a>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Estado</label>
                        <p class="form-control-static"><?= $activo['estado_nombre'] ? inv_e($activo['estado_nombre']) : '-'; ?></p>
                        <a href="<?= site_url('inventario/cambiar_estado/' . $activo['id']); ?>" class="btn btn-xs btn-default"><i class="fa fa-exchange"></i> Cambiar estado</a>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label>Cargo del responsable</label>
                        <input type="text" name="cargo" class="form-control" maxlength="150" autocomplete="off" value="<?= inv_e($activo['cargo']); ?>">
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Adquisicion<?php if($puede_ver_valores){ ?> y valores contables<?php } ?></legend>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label>Fecha de adquisicion</label>
                        <input type="date" name="fecha_adquisicion" class="form-control" value="<?= inv_e($activo['fecha_adquisicion']); ?>">
                    </div>
                    <?php if($puede_ver_valores){ ?>
                    <div class="form-group col-sm-3">
                        <label>Valor libro</label>
                        <input type="number" step="1" min="0" name="valor_libro" class="form-control" value="<?= inv_e($activo['valor_libro']); ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Depreciacion acumulada</label>
                        <input type="number" step="1" min="0" name="depreciacion_acumulada" class="form-control" value="<?= inv_e($activo['depreciacion_acumulada']); ?>">
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"><?= inv_e($activo['observaciones']); ?></textarea>
                    </div>
                </div>
            </fieldset>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('inventario/detalle/' . $activo['id']); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>
