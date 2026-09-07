            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Modelos <i class="fa fa-angle-right"></i> Proceso</h4>
			</div>
						
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('modelos/' . $modelo['id']) ?>" class="btn btn-info"><i class="fa fa-caret-left"></i> Volver al mapa</a>
			</div>

        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-sitemap"></i> Proceso <a href="<?= site_url('modelos/' . $modelo['id']) ?>" class="pull-right"><i class="fa fa-caret-left"></i> Volver al mapa</a></div>
				
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-md-8">
			        		
			        		<h3 class="text-upper text-primary mt-0"><?= $proceso['nombre']; ?> <?= ($proceso['codigo']) ? '('.$proceso['codigo'].')' : ''; ?></h3>
			        		<ol class="breadcrumb">
							  <li><a href="<?= site_url('modelos/' . $modelo['id']) ?>"><?= $modelo['nombre']; ?></a></li>
							  <li><a href="<?= site_url('modelos/grupo/' . $proceso['grupo']) ?>"><?= $grupo['nombre']; ?></a></li>
							  <li class="active"><?= $proceso['nombre']; ?> <?= ($proceso['codigo']) ? '('.$proceso['codigo'].')' : ''; ?></li>
							</ol>
			        		<p><?= htmlspecialchars($proceso['descripcion']); ?></p>
                            
                            <p>&nbsp;</p>
							
							<?php if(isset($documentacion)){ ?>
							
								<?php foreach($documentacion as $carpeta){ ?>
                            
                                    <?php if(isset($carpeta['nombre'])){ ?>
                            
                                        <?php if(( ! $carpeta['privada']) OR (($carpeta['privada']) AND (($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')))){ ?>
								
                                            <div class="panel <?= ($carpeta['privada']) ? 'panel-warning' : 'panel-primary'; ?>">
                                                <div class="panel-heading lg">
                                                    <i class="fa <?= ($carpeta['privada']) ? 'fa-lock' : 'fa-folder'; ?>"></i> <?= isset($carpeta['nombre']) ? htmlspecialchars($carpeta['nombre']) : ''; ?><?php if($carpeta['privada']){ ?> <span class="text-muted"> (Carpeta privada)</span><?php } ?>
                                                    <?php if((($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')) AND (isset($carpeta['nombre']))){ ?>
                                                        <div class="btn-group pull-right">
                                                            <i data-toggle="dropdown" class="dropdown-toggle fa fa-bars fa-lg"></i>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="<?= site_url('carpeta/convertir/' . $carpeta['id']) ?>" class="<?= ($carpeta['privada']) ? 'btn-convertir-publico' : 'btn-convertir-privado'; ?>"><?= ($carpeta['privada']) ? 'Convertir carpeta a pública' : 'Convertir carpeta a privada'; ?></a></li>
                                                                <li><a href="<?= site_url('carpeta/renombrar/' . $carpeta['id']) ?>" class="btn-renombrar" data-nombre="<?= htmlspecialchars($carpeta['nombre']); ?>" data-carpeta="<?= $carpeta['id']; ?>">Renombrar</a></li>
                                                                <li><a href="<?= site_url('carpeta/eliminar/' . $carpeta['id']) ?>" class="btn-eliminar">Eliminar</a></li>
                                                            </ul>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <div class="panel-body">

                                                    <ul class="list-group">

                                                        <?php foreach($carpeta['documentos'] as $documento){ ?>

                                                            <li class="list-group-item" id="documento-<?= $documento['id']; ?>">
                                                                <div class="row">
                                                                    <div class="col-xs-11">
                                                                        <a href="<?= site_url('documento/descargar/' . $documento['id']) ?>" class="<?= ($carpeta['privada']) ? 'text-warning' : 'text-primary'; ?>">
                                                                        <span class="fa-stack fa fa-2x pull-left">
                                                                            <i class="fa fa-file fa-stack-2x"></i>
                                                                            <span class="fa fa-stack-1x fa-inverse"><?= strtoupper($documento['file_ext']); ?></span>
                                                                            </span>
                                                                        </a>
                                                                        <h4 style="margin: 0;" class=""><a href="<?= site_url('documento/descargar/' . $documento['id']) ?>" class="<?= ($carpeta['privada']) ? 'text-warning' : 'text-primary'; ?>"><?= $documento['nombre']; ?></a></h4>
                                                                        <a href="<?= site_url('documento/descargar/' . $documento['id']) ?>" class="<?= ($carpeta['privada']) ? 'text-warning' : 'text-primary'; ?>"><?= htmlspecialchars($documento['raw_name']); ?></a> <?php if($documento['version']){ ?><span class="text-muted">Versión <?= htmlspecialchars($documento['version']); ?></span><?php } ?>
                                                                        <p><?php if($documento['codigo']){ ?><?= htmlspecialchars($documento['codigo']); ?> &bull; <?php } ?><?= $documento['file_size']; ?></p>
                                                                    </div>
                                                                    <div class="col-xs-1 pull-right">
                                                                        <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
                                                                            <div class="btn-group pull-right">
                                                                                <i data-toggle="dropdown" class="dropdown-toggle fa fa-cog fa-lg"></i>
                                                                                <ul class="dropdown-menu" role="menu">
                                                                                    <li><a href="<?= site_url('documento/descargar_original/' . $documento['id']) ?>">Descargar original</a></li>
                                                                                    <li><a href="#" class="btn-editar-documento" data-documento="<?= $documento['id']; ?>" data-nombre="<?= $documento['nombre']; ?>" data-codigo="<?= $documento['codigo']; ?>" data-version="<?= $documento['version'] ?>">Editar</a></li>
                                                                                    <li><a href="#" class="btn-mover" data-documento="<?= $documento['id']; ?>" data-carpeta="<?= isset($carpeta['id']) ? $carpeta['id'] : ''; ?>">Mover</a></li>
                                                                                    <li><a href="<?= site_url('documento/eliminar/' . $documento['id']) ?>" class="btn-eliminar">Eliminar</a></li>
                                                                                </ul>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                        <?php } ?>

                                                    </ul>

                                                </div>

                                            </div>
                            
                                        <?php } ?>

                                    <?php }else{ ?>
                            
                                        <ul class="list-group">
                            
                                            <?php foreach($carpeta['documentos'] as $documento){ ?>

                                                <li class="list-group-item" id="documento-<?= $documento['id']; ?>">
                                                    <div class="row">
                                                        <div class="col-xs-11">
                                                            <a href="<?= site_url('documento/descargar/' . $documento['id']) ?>">
                                                            <span class="fa-stack fa fa-2x pull-left">
                                                                <i class="fa fa-file fa-stack-2x"></i>
                                                                <span class="fa fa-stack-1x fa-inverse"><?= strtoupper($documento['file_ext']); ?></span>
                                                                </span>
                                                            </a>
                                                            <h4 style="margin: 0;"><a href="<?= site_url('documento/descargar/' . $documento['id']) ?>"><?= $documento['nombre']; ?></a></h4>
                                                            <a href="<?= site_url('documento/descargar/' . $documento['id']) ?>"><?= htmlspecialchars($documento['raw_name']); ?></a> <?php if($documento['version']){ ?><span class="text-muted">Versión <?= htmlspecialchars($documento['version']); ?></span><?php } ?>
                                                            <p><?php if($documento['codigo']){ ?><?= htmlspecialchars($documento['codigo']); ?> &bull; <?php } ?><?= $documento['file_size']; ?></p>
                                                        </div>
                                                        <div class="col-xs-1 pull-right">
                                                            <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
                                                                <div class="btn-group pull-right">
                                                                    <i data-toggle="dropdown" class="dropdown-toggle fa fa-cog fa-lg"></i>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="<?= site_url('documento/descargar_original/' . $documento['id']) ?>">Descargar original</a></li>
                                                                        <li><a href="#" class="btn-editar-documento" data-documento="<?= $documento['id']; ?>" data-nombre="<?= $documento['nombre']; ?>" data-codigo="<?= $documento['codigo']; ?>" data-version="<?= $documento['version'] ?>">Editar</a></li>
                                                                        <li><a href="#" class="btn-mover" data-documento="<?= $documento['id']; ?>" data-carpeta="<?= isset($carpeta['id']) ? $carpeta['id'] : ''; ?>">Mover</a></li>
                                                                        <li><a href="<?= site_url('documento/eliminar/' . $documento['id']) ?>" class="btn-eliminar">Eliminar</a></li>
                                                                    </ul>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </li>

                                            <?php } ?>
                                            
                                        </ul>

                                    <?php } ?>
                            
                                <?php } ?>
								
							<?php }else{ ?>
							
								<div class="alert alert-warning">
									<strong>INFO!</strong> Aún no se ha registrado Documentación.
								</div>
								
							<?php } ?>
							
							<?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
							
								<div class="panel panel-default">
									<div class="panel-heading lg"><i class="fa fa-cloud-upload"></i> Subir Documento</div>
									
									<div class="panel-body">
										
										<form action="<?= site_url('documento/grabar') ?>" method="post" enctype="multipart/form-data">
											
											<fieldset>
												
												<div class="form-group">
													<label>Nombre</label>
													<input type="text" maxlength="255" name="nombre" class="form-control" autocomplete="off" required/>
												</div>
												
												<div class="row">
													
													<div class="form-group col-md-4">
														<label>Código</label>
														<input type="text" maxlength="32" name="codigo" class="form-control" autocomplete="off" />
													</div>
													
													<div class="form-group col-md-4">
														<label>Versión</label>
														<input type="text" maxlength="32" name="version" class="form-control" autocomplete="off" />
													</div>
													
													<div class="form-group col-md-4">
														<label>Archivo</label>
														<input type="file" name="documento" class="form-control" required/>
													</div>
												
												</div>
												
												<div class="row">
													
													<div class="form-group col-md-4">
														<label class="radio-inline">
														  <input type="radio" name="radio-carpeta" value="0" checked> <strong>Carpeta</strong>
														</label>
														<select name="carpeta" class="form-control">
															<option value="">Sin carpeta</option>
															<?php foreach($carpetas as $carpeta){ ?>
																<option value="<?= $carpeta['id']; ?>"><?= htmlspecialchars($carpeta['nombre']); ?><?= ($carpeta['privada']) ? ' (Carpeta privada)' : ''; ?></option>
															<?php } ?>
														</select>
													</div>
													
													<div class="form-group col-md-4">
														<label class="radio-inline">
														  <input type="radio" name="radio-carpeta" value="1"> <strong>Nueva Carpeta</strong>
														</label>
														<input type="text" maxlength="64" name="nuevacarpeta" class="form-control" placeholder="Nombre de la nueva carpeta" autocomplete="off" required disabled />
													</div>
													
													<div class="form-group col-md-4">
														<label>&nbsp;</label>
														<input type="hidden" name="proceso" value="<?= $proceso['id']; ?>" />
														<button type="submit" class="btn btn-block btn-success"><i class="fa fa-cloud-upload"></i> Subir</button>
													</div>
												
												</div>
											
											</fieldset>
										
										</form>
										
									</div>
									
								</div>
								<div class="panel panel-default">
									<div class="panel-heading lg"><i class="fa fa-folder"></i> Crear Carpeta</div>
									
									<div class="panel-body">
										
										<form action="<?= site_url('carpeta/grabar') ?>" method="post" enctype="multipart/form-data">
											
											<fieldset>
												
												<div class="row">
													
													<div class="form-group col-md-8">
														<label>Nombre</label>
														<input type="text" maxlength="64" name="nombre" class="form-control" autocomplete="off" required/>
													</div>
													
													<div class="form-group col-md-4">
														<label>&nbsp;</label>
														<input type="hidden" name="proceso" value="<?= $proceso['id']; ?>" />
														<button type="submit" class="btn btn-block btn-success"><i class="fa fa-folder"></i> Crear</button>
													</div>
												
												</div>
											
											</fieldset>
										
										</form>
										
									</div>
									
								</div>
							
							<?php } ?>
							
			        	</div>
			        	
			        	<div class="col-md-4">
			        		<div class="panel panel-primary">
								<div class="panel-heading lg"><i class="fa fa-file"></i> Documentación vigente</div>
								
								<div class="panel-body">
									<ul class="list-group">
									
										<?php if($apoyos){ ?>
									
											<?php foreach($apoyos as $apoyo){ ?>
										
											<li class="list-group-item">
												<a href="<?= site_url('apoyo/descargar/'. $apoyo['id']); ?>">
                                                    <small><?= htmlspecialchars($apoyo['raw_name']); ?> &bull; <?= $apoyo['file_size']; ?></small>
                                                </a>
                                                
                                                <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
                                                    <div class="btn-group pull-right">
                                                        <i data-toggle="dropdown" class="dropdown-toggle fa fa-cog fa-lg"></i>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li><a href="<?= site_url('apoyo/descargar_original/' . $apoyo['id']) ?>">Descargar original</a></li>
                                                            <li><a href="<?= site_url('apoyo/eliminar/'. $apoyo['id']); ?>" class="btn-eliminar">Eliminar</a></li>
                                                        </ul>
                                                    </div>
                                                <?php } ?>
                                                
											</li>
											
											<?php } ?>
											
										<?php }else{ ?>
										
											<div class="alert alert-warning">
												<strong>INFO!</strong> Aún no se han registrado Documentos de apoyo.
											</div>
										
										<?php } ?>
										
									</ul>
									<?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
									<form action="<?= site_url('apoyo/grabar') ?>" method="post" enctype="multipart/form-data">
										<fieldset>
											<div class="row">
												<div class="form-group col-xs-8">
												    <input type="file" class="form-control input-sm" name="apoyo" required/>
												    <input type="hidden" name="grupo" value="<?= $proceso['grupo']; ?>" />
												</div>
												<div class="form-group col-xs-4">
													<button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fa fa-cloud-upload"></i> Subir</button>
												</div>
											</div>
										</fieldset>
									</form>
									<?php } ?>
								</div>
			        		</div>
			        		
			        		<?php if($grupo['descripcion']){ ?>
			        		
			        		<div class="panel panel-primary">
								<div class="panel-heading lg"><i class="fa fa-info"></i> <?= $grupo['nombre']; ?></div>
								
								<div class="panel-body">
									<?= htmlspecialchars($grupo['descripcion']); ?>
								</div>
			        		</div>
			        		
			        		<?php } ?>
			        		
			        		<?php if(is_file(APPPATH . 'views/aside/id/' . $modelo['id'] .'.php')) $this->load->view('aside/id/' . $modelo['id']); ?>	
			        		
			        	</div>
			        
			        </div>
			        
			    </div>
			</div>
			<div class="modal fade" id="modal-editar" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
				   	<div class="modal-content">
				    	<div class="modal-header">
					    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					    	<h4 class="modal-title">Editar Documento</h4>
					    </div>
					    <form id="form-editar" action="<?= site_url('documento/actualizar') ?>" method="post">
						    <div class="modal-body">
						    	
						    	<div class="row">
						    	
						    		<div class="col-xs-12">
	
						    			<fieldset>
										    	
										    <div class="form-group">
										    	<label>Nombre</label>
										    	<input type="text" maxlength="255" name="nombre" class="form-control" autocomplete="off" required/>
										    </div>
										    	
										    <div class="row">
										    		
										    	<div class="form-group col-md-6">
										    		<label>Código</label>
										    		<input type="text" maxlength="32" name="codigo" class="form-control" autocomplete="off" />
										    	</div>
										    		
										    	<div class="form-group col-md-6">
										    		<label>Versión</label>
										    		<input type="text" maxlength="32" name="version" class="form-control" autocomplete="off" />
										    	</div>
										    	
										    	<input type="hidden" name="id" />
										    	
										    </div>
										    
						    			</fieldset>
		 				    		
						    		</div>
						    	
						    	</div>
							   							    
							</div>
							<div class="modal-footer">
								<div class="row">
									<div class="col-xs-6 col-md-3 col-md-push-6">
										<button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>	
									</div>
									<div class="col-xs-6 col-md-3 col-md-push-6">
										<button type="button" class="btn btn-primary btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-mover" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
				   	<div class="modal-content">
				    	<div class="modal-header">
					    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					    	<h4 class="modal-title">Mover Documento</h4>
					    </div>
					    <form id="form-mover" action="<?= site_url('documento/mover') ?>" method="post">
						    <div class="modal-body">
						    	
						    	<div class="row">
						    	
						    		<div class="col-xs-12">
						    		
						    			<div class="form-group">
						    			    
						    			    <select name="carpeta" class="form-control">
						    			    	<option value="">Sin carpeta</option>
						    			    	<?php foreach($carpetas as $carpeta){ ?>
						    			    		<option value="<?= $carpeta['id']; ?>"><?= htmlspecialchars($carpeta['nombre']); ?></option>
						    			    	<?php }?>
						    			    </select>
						    			    <input type="hidden" name="documento" value="" />
						    			    
						    			</div>
						    					 				    		
						    		</div>
						    	
						    	</div>
							   							    
							</div>
							<div class="modal-footer">
								<div class="row">
									<div class="col-xs-6 col-md-3 col-md-push-6">
										<button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>	
									</div>
									<div class="col-xs-6 col-md-3 col-md-push-6">
										<button type="button" class="btn btn-primary btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modal-renombrar-carpeta" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
				   	<div class="modal-content">
				    	<div class="modal-header">
					    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					    	<h4 class="modal-title">Renombrar Carpeta</h4>
					    </div>
					    <form id="form-renombrar-carpeta" action="<?= site_url('carpeta/renombrar') ?>" method="post">
						    <div class="modal-body">
						    	
						    	<div class="row">
						    	
						    		<div class="col-xs-12">

						    			<div class="form-group">
						    			    
						    			    <label>Nombre</label>
						    			    <input type="text" maxlength="64" name="nombre" class="form-control" autocomplete="off" required />
						    			    <input type="hidden" name="carpeta" value="" />
						    			    
						    			</div>
		 				    		
						    		</div>
						    	
						    	</div>
							   							    
							</div>
							<div class="modal-footer">
								<div class="row">
									<div class="col-xs-6 col-md-3 col-md-push-6">
										<button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>	
									</div>
									<div class="col-xs-6 col-md-3 col-md-push-6">
										<button type="button" class="btn btn-primary btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<script>
				$(function(){
                    
                    if(window.location.hash){
                        var offset = -100;
                        $('html, body').animate({
                            scrollTop: ($(window.location.hash).offset().top + offset) + 'px'
                        }, 1000, 'swing');
                        $(window.location.hash).addClass('animated flash');
                    }
					
					$('.btn-editar-documento').click(function(e){
						e.preventDefault();
						$('#form-editar [name=id]').val($(e.currentTarget).data('documento'));
						$('#form-editar [name=nombre]').val($(e.currentTarget).data('nombre'));
						$('#form-editar [name=codigo]').val($(e.currentTarget).data('codigo'));
						$('#form-editar [name=version]').val($(e.currentTarget).data('version'));
						$('#modal-editar').modal('show');
					});
					
					$('.btn-mover').click(function(e){
						e.preventDefault();
						$('#form-mover [name=carpeta]').val($(e.currentTarget).data('carpeta'));
						$('#form-mover [name=documento]').val($(e.currentTarget).data('documento'));
						$('#modal-mover').modal('show');
					});
					
					$('.btn-renombrar').click(function(e){
						e.preventDefault();
						$('#form-renombrar-carpeta [name=carpeta]').val($(e.currentTarget).data('carpeta'));
						$('#form-renombrar-carpeta [name=nombre]').val($(e.currentTarget).data('nombre'));
						$('#modal-renombrar-carpeta').modal('show');
					});
												
					$('input[name=radio-carpeta]').change(function(e)
					{
						if($(this).val() == 1)
						{
							$('input[name=nuevacarpeta]').prop('disabled', false);
							$('select[name=carpeta]').prop('disabled', true);
						}
						else
						{
							$('input[name=nuevacarpeta]').prop('disabled', true);
							$('select[name=carpeta]').prop('disabled', false);
						}
					});
				});
			</script>