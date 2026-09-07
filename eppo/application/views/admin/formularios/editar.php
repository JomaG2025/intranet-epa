			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Formulario</h4>
			</div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default no-border">
                        
                        <div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Formulario</div>
                        
                        <div class="panel-body">

                            <form id="form-formulario" action="<?= site_url('admin/formularios/actualizar')?>" method="post" enctype="multipart/form-data">

                                <fieldset>
                                    
                                    <legend>Editar Formulario</legend>

                                    <div class="row">

                                        <div class="col-md-12">
                                            
                                            <div class="well">
                                            
                                                <div class="form-group row form-horizontal">
                                                    <label class="control-label col-sm-2 col-md-1">Usuario</label>
                                                    <div class="col-sm-10 col-md-11 pt-5">
                                                        <?= $formulario['usuario']; ?>
                                                    </div>
                                                </div>

                                                <div class="form-group row form-horizontal">
                                                    <label class="control-label col-sm-2 col-md-1">Periodo</label>
                                                    <div class="col-sm-10 col-md-11 pt-5">
                                                        <?= $formulario['periodo']; ?>
                                                    </div>
                                                </div>

                                                <div class="form-group row form-horizontal">
                                                    <label class="control-label col-sm-2 col-md-1">Creado</label>
                                                    <div class="col-sm-10 col-md-11 pt-5">
                                                        <?= formato_fecha_hora($formulario['creado']); ?> <span class="text-muted"><?= formato_fecha_atras($formulario['creado']); ?></span>
                                                    </div>
                                                </div>

                                                <?php if($formulario['modificado']){ ?>

                                                    <div class="form-group row form-horizontal">
                                                        <label class="control-label col-sm-2 col-md-1">Modificado</label>
                                                        <div class="col-sm-10 col-md-11 pt-5">
                                                            <?= formato_fecha_hora($formulario['modificado']); ?> <span class="text-muted"><?= formato_fecha_atras($formulario['modificado']); ?></span>
                                                        </div>
                                                    </div>

                                                <?php } ?>
                                                
                                            </div>

                                        </div>
                                            
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                <label>Estado</label>
                                                <select name="estadoformulario" class="form-control">
                                                    <?php foreach($estadoformularios as $estadoformulario){ ?>
                                                    <option value="<?= $estadoformulario['abrev']?>" <?= ($estadoformulario['abrev'] == $formulario['estadoformulario']) ? 'selected' : ''; ?>><?= $estadoformulario['abrev']?> - <?= $estadoformulario['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" class="form-control" maxlength="255" name="nombre" value="<?= $formulario['nombre']; ?>" required/>
                                            </div>
                                            
                                            <div class="form-group">
                                                
                                                <?php if($evaluadores){ ?>
                                                    <label class="control-label col-sm-2 text-right"><?= (count($evaluadores) == 1) ? 'Evaluador' : 'Evaluadores'; ?></label>
                                                    <?php foreach($evaluadores as $evaluador){ ?>
                                                        <input type="text" class="form-control mb-15" maxlength="255" disabled value="<?= $evaluador['nombre']; ?>" tabindex="-1" />
                                                        <?php } ?>
                                                <?php } ?>

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">

                                                <label>Nivel</label>
                                                <select name="tiponivel" class="form-control">
                                                    <option value="">Sin selección</option>
                                                    <?php foreach($tiponiveles as $tiponivel){ ?>
                                                    <option value="<?= $tiponivel['abrev']; ?>" <?= ($formulario['tiponivel'] == $tiponivel['abrev']) ? 'selected' : ''; ?>><?= $tiponivel['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>

                                            <div class="form-group">

                                                <label>Contrato</label>
                                                <select name="tipocontrato" class="form-control">
                                                    <option value="">Sin selección</option>
                                                    <?php foreach($tipocontratos as $tipocontrato){ ?>
                                                    <option value="<?= $tipocontrato['abrev']; ?>" <?= ($formulario['tipocontrato'] == $tipocontrato['abrev']) ? 'selected' : ''; ?>><?= $tipocontrato['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                            
                                            <div class="row">
												<div class="col-sm-6">
													<a href="<?= site_url('admin/formularios'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
												<p class="visible-xs"></p>
												<div class="col-sm-6">
													<input type="hidden" name="id" value="<?= $formulario['id']; ?>" />
												    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
								                </div>
								            </div>

                                        </div>

                                    </div>

                                </fieldset>

                            </form>

                        </div>
                    </div>

                </div>
            </div>