            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
				            <form method="post" action="<?= site_url('admin/usuarios/actualizar'); ?>" enctype="multipart/form-data">
                                <fieldset>
									<legend>Editar Usuario</legend>
									<div class="row">    
								        <div class="col-md-6">
                                            <div class="form-group">
												<label>Usuario</label>
												<input type="text" value="<?= $usuario['usuario']; ?>"  class="form-control" disabled />										
											</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Privilegio</label>
												<select name="privilegio" class="form-control">
													<?php foreach($privilegios as $privilegio){ ?>
														<option value="<?= $privilegio['abrev']; ?>" <?= ($usuario['privilegio'] == $privilegio['abrev'])? 'selected="selected"' : '';?> ><?= $privilegio['nombre']; ?></option>
													<?php } ?>
												</select>
								            </div>
											<div class="row">
												<div class="col-sm-6">
													<a href="<?= site_url('admin/usuarios'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
												<p class="visible-xs"></p>
												<div class="col-sm-6">
													<input type="hidden" name="id" value="<?= $usuario['id']; ?>" />
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