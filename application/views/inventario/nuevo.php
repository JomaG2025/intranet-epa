<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Nuevo Activo</h4>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-cubes"></i> Nuevo Activo</div>
    <div class="panel-body">
        <form method="post" action="<?= site_url('inventario/grabar'); ?>">
            <?= inv_csrf_campo(); ?>
            <fieldset>
                <legend>Identificacion</legend>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label>Codigo unico *</label>
                        <input type="text" name="codigo_unico" class="form-control" maxlength="30" required autocomplete="off" autofocus>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Codigo</label>
                        <input type="text" name="codigo" class="form-control" maxlength="20" autocomplete="off">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Grupo</label>
                        <input type="text" name="grupo" class="form-control" maxlength="30" autocomplete="off">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Unidad</label>
                        <input type="number" name="unidad" class="form-control" value="1" min="1">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Descripcion *</label>
                        <textarea name="descripcion" class="form-control" rows="2" required></textarea>
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
                            <option value="<?= (int) $gerencia['id']; ?>"><?= inv_e($gerencia['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Area</label>
                        <select name="area_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($areas as $area) { ?>
                            <option value="<?= (int) $area['id']; ?>"><?= inv_e($area['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Categoria *</label>
                        <select name="categoria_id" class="form-control" required>
                            <option value="">Seleccione</option>
                            <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?= (int) $categoria['id']; ?>"><?= inv_e($categoria['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Subcategoria</label>
                        <select name="subcategoria_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($subcategorias as $subcategoria) { ?>
                            <option value="<?= (int) $subcategoria['id']; ?>"><?= inv_e($subcategoria['categoria_nombre']); ?> - <?= inv_e($subcategoria['nombre']); ?></option>
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
                            <option value="<?= (int) $tipo['id']; ?>"><?= inv_e($tipo['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" maxlength="80" autocomplete="off">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control" maxlength="80" autocomplete="off">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control" maxlength="40" autocomplete="off">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label>N. de Serie</label>
                        <input type="text" name="numero_serie" class="form-control" maxlength="80" autocomplete="off">
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Ubicacion y responsable iniciales</legend>
                <p class="help-block">Estos 3 datos solo se pueden fijar aqui, al crear el activo. Una vez guardado, para cambiarlos usa las pantallas "Cambiar ubicacion / responsable / estado" desde el detalle del activo (asi queda registrado el movimiento).</p>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label>Ubicacion fisica</label>
                        <select name="ubicacion_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($ubicaciones as $ubicacion) { ?>
                            <option value="<?= (int) $ubicacion['id']; ?>"><?= inv_e($ubicacion['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Responsable</label>
                        <select name="responsable_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($responsables as $responsable) { ?>
                            <option value="<?= (int) $responsable['id']; ?>"><?= inv_e($responsable['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Cargo</label>
                        <input type="text" name="cargo" class="form-control" maxlength="150" autocomplete="off">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Estado</label>
                        <select name="estado_id" class="form-control">
                            <option value="">Seleccione</option>
                            <?php foreach ($estados as $estado) { ?>
                            <option value="<?= (int) $estado['id']; ?>"><?= inv_e($estado['nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Adquisicion<?php if($puede_ver_valores){ ?> y valores contables<?php } ?></legend>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label>Fecha de adquisicion</label>
                        <input type="date" name="fecha_adquisicion" class="form-control">
                    </div>
                    <?php if($puede_ver_valores){ ?>
                    <div class="form-group col-sm-3">
                        <label>Valor libro</label>
                        <input type="number" step="1" min="0" name="valor_libro" class="form-control">
                    </div>
                    <div class="form-group col-sm-3">
                        <label>Depreciacion acumulada</label>
                        <input type="number" step="1" min="0" name="depreciacion_acumulada" class="form-control">
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </fieldset>

            <div class="row">
                <div class="form-group col-sm-6">
                    <a href="<?= site_url('inventario/listado'); ?>" class="btn btn-default btn-block"><i class="fa fa-reply"></i> Cancelar</a>
                </div>
                <div class="form-group col-sm-6">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Guardar Activo</button>
                </div>
            </div>
        </form>
    </div>
</div>
