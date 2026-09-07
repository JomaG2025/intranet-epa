            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Grupos</h4>
			</div>

        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Grupos</div>
				<div class="panel-body">

			        <div class="row">

			        	<div class="col-lg-12">
			        		
							  <form method="post" action="<?= site_url('admin/grupos/actualizar'); ?>" enctype="multipart/form-data">
							    
							    	<fieldset>
							    	
								    	<legend>Editar Grupo</legend>
								    
								    	<div class="row">
								    
										    <div class="col-md-6">	
												
												<div class="form-group">
													<label>Nombre</label>
													<input type="text" name="nombre" maxlength="255" class="form-control" value="<?= $grupo['nombre']; ?>" autocomplete="off" required/>							
												</div>
												
												<div class="form-group">
												    <label>Modelo</label>
												    <select name="modelo" class="form-control">
												    	<?php foreach($modelos as $modelo){ ?>
												    		<option value="<?= $modelo['id']; ?>" <?= ($modelo['id'] == $grupo['modelo']) ? 'selected="selected"' : ''; ?>><?= $modelo['nombre']; ?></option>
												    	<?php } ?>
												    </select>
												</div>   					    
									        </div>
									        
									        <div class="col-md-6">	
										        <div class="form-group">
													<label>Descripción</label>
													<textarea class="form-control" rows="4" name="descripcion"><?= $grupo['descripcion']; ?></textarea>
												</div>
												<div class="row">
													<div class="col-sm-6">
														<a href="<?= site_url('admin/grupos'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
													</div>
													<p class="visible-xs"></p>
													<div class="col-sm-6">
														<input type="hidden" name="id" value="<?= $grupo['id']; ?>" />
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