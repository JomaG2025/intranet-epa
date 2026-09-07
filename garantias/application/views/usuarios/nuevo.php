			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
			</div>
			
			<div id="alertas">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<?= $alertas; ?>	
					</div>
				</div>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios</div>
				<div class="panel-body">

			        <div class="row">

			        	<div class="col-lg-12">
			        		
							    <form method="post" action="<?= site_url('usuarios/grabar'); ?>" enctype="multipart/form-data">
							    
							    	<fieldset>
							    	
								    	<legend>Nuevo Usuario</legend>
								    
								    	<div class="row">
								    
										    <div class="col-md-6">	
												<div class="form-group">
													<label>Usuario</label>
													<select name="usuario" class="form-control" autofocus>
														<?php foreach($usuarios as $usuario){ ?>
															<option value="<?= $usuario['usuario']; ?>"><?= $usuario['nombre']; ?></option>
														<?php } ?>
													</select>											
												</div>         					    
									        </div>
									        
									        <div class="col-md-6">	
										        <div class="form-group">
													<label>Privilegio</label>
													<select name="privilegio" class="form-control">
														<?php foreach($privilegios as $privilegio){ ?>
															<option value="<?= $privilegio['abrev']; ?>"><?= $privilegio['nombre']; ?></option>
														<?php } ?>
													</select>
												</div>
												<div class="row">
													<div class="col-sm-6">
														<a href="<?= site_url('usuarios'); ?>" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
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