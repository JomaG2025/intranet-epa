			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Comité evaluador</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Comité evaluador</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
                            <form method="post" action="<?= site_url('admin/comites/grabar'); ?>" enctype="multipart/form-data">
				                <fieldset>
								    <legend>Nuevo Integrante</legend>
								    <div class="row">
								        <div class="col-md-2 col-md-offset-4">	
                                            <div class="form-group">
                                                <label>Periodo</label>
												<select name="periodo" class="form-control" required>
                                                    <?php foreach($periodos as $periodo){ ?>
                                                    <option value="<?= $periodo['periodo']; ?>"><?= $periodo['periodo']; ?></option>
                                                    <?php } ?>
                                                </select>
								            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Usuario</label>
                                                <select name="usuario" class="form-control" required>
                                                    <?php foreach($usuarios as $usuario){ ?>
                                                        <?php if($usuario['activo']){ ?>
                                                            <option value="<?= $usuario['usuario']; ?>"><?= $usuario['nombre']; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
												    <a href="<?= site_url('admin/comites'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
                                                <p class="visible-xs"></p>
												<div class="col-sm-6">
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