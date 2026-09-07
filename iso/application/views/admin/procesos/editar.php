            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Procesos</h4>
			</div>
			
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Procesos</div>
				<div class="panel-body">

			        <div class="row">

			        	<div class="col-lg-12">
			        		
							  <form method="post" action="<?= site_url('admin/procesos/actualizar'); ?>" enctype="multipart/form-data">
							    
							    	<fieldset>
							    	
								    	<legend>Editar Proceso</legend>
								    
								    	<div class="row">
								    
										    <div class="col-md-6">	
												
												<div class="form-group">
													<label>Nombre</label>
													<input type="text" name="nombre" maxlength="255" class="form-control" value="<?= $proceso['nombre']; ?>" autocomplete="off" required/>							
												</div>
												
												<div class="row">
												
													<div class="form-group col-md-6">
														<label>Código</label>
														<input type="text" name="codigo" maxlength="8" class="form-control" value="<?= $proceso['codigo']; ?>" autocomplete="off"/>
													</div>
													<div class="form-group col-md-6">
														<label>Grupo</label>
														<select name="grupo" class="form-control">
														<?php foreach($grupos as $grupo){ ?>
															<option value="<?= $grupo['id']; ?>" <?= ($grupo['id'] == $proceso['grupo']) ? 'selected="selected"' : ''; ?>><?= $grupo['modelo_nombre'] ?> - <?= $grupo['nombre']; ?></option>
														<?php } ?>
													</select>
													</div>
													   
												</div>   					    
									        </div>
									        
									        <div class="col-md-6">	
										        <div class="form-group">
													<label>Descripción</label>
													<textarea class="form-control" rows="4" name="descripcion"><?= $proceso['descripcion']; ?></textarea>
												</div>
												<div class="row">
													<div class="col-sm-6">
														<a href="<?= site_url('admin/procesos'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
													</div>
													<p class="visible-xs"></p>
													<div class="col-sm-6">
														<input type="hidden" name="id" value="<?= $proceso['id']; ?>" />
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