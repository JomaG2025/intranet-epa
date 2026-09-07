			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Evaluadores</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Evaluadores</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
                            <form method="post" action="<?= site_url('admin/evaluadores/actualizar'); ?>" enctype="multipart/form-data">
				                <fieldset>
								    <legend>Editar Evaluador</legend>
								    <div class="row">
								        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>Periodo</label>
                                                    <select name="periodo" class="form-control" disabled>
                                                        <?php foreach($periodos as $periodo){ ?>
                                                        <option value="<?= $periodo['periodo']; ?>" <?= ($periodo['periodo'] == $evaluador['periodo']) ? 'selected' : ''; ?>><?= $periodo['periodo']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-8 form-group">
                                                    <label>Evaluador</label>
                                                    <select name="usuario" class="form-control" disabled>
                                                        <?php foreach($usuarios as $item){ ?>
                                                        <option value="<?= $item['usuario']; ?>" <?= ($item['usuario'] == $evaluador['usuario']) ? 'selected' : ''; ?>><?= $item['usuario']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Evaluados</label>
												<div class="well">
                                                
                                                    <?php foreach($usuarios as $item){ ?>
                                                    <div class="checkbox">
                                                        <label <?= ($item['usuario'] == $evaluador['usuario']) ? 'class="disabled"' : ''; ?>>
                                                            <input type="checkbox" class="checkbox-evaluados" name="evaluados[]" value="<?= $item['usuario']; ?>" <?php foreach($evaluados as $evaluado){ if($evaluado['usuario'] == $item['usuario']){ echo 'checked'; }} ?> <?= ($item['usuario'] == $evaluador['usuario']) ? 'disabled' : ''; ?>/>
                                                            <?= (empty($item['nombre'])) ? $item['usuario'] : $item['nombre']; ?>
                                                      </label>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <?php } ?>
                                                    
                                                </div>
								            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
												    <a href="<?= site_url('admin/evaluadores'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
                                                <p class="visible-xs"></p>
												<div class="col-sm-6">
                                                    <input type="hidden" name="id" value="<?= $evaluador['id']; ?>" />
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