            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Modelos <i class="fa fa-angle-right"></i> Grupo</h4>
			</div>
						
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('modelos/' . $grupo['modelo']) ?>" class="btn btn-info"><i class="fa fa-caret-left"></i> Volver al mapa</a>
			</div>
			
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-sitemap"></i> Grupo <a href="<?= site_url('modelos/' . $grupo['modelo']) ?>" class="pull-right"><i class="fa fa-caret-left"></i> Volver al mapa</a></div>
				
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-md-8">
			        		
			        		<h3 class="text-upper text-primary mt-0"><?= $grupo['nombre']; ?></h3>
			        		<ol class="breadcrumb">
							  <li><a href="<?= site_url('modelos/' . $grupo['modelo']) ?>"><?= $modelo['nombre'] ?></a></li>
							  <li class="active"><?= $grupo['nombre'] ?></li>
							</ol>
			        		<p><?= htmlspecialchars($grupo['descripcion']); ?></p>

							<?php if($apoyos){ ?>
							
								<div class="panel panel-primary">
									<div class="panel-heading lg">
										<i class="fa fa-folder"></i> Documentación de apoyo
									</div>
									
									<div class="panel-body">
										
										<ul class="list-group">
										
											<?php foreach($apoyos as $apoyo){ ?>
												
												<li class="list-group-item">
												    <div class="row">
												    	<div class="col-xs-11">
												    		<a href="<?= site_url('apoyo/descargar/' . $apoyo['id']) ?>">
												    		<span class="fa-stack fa fa-2x pull-left">
												    			<i class="fa fa-file fa-stack-2x"></i>
												    			<span class="fa fa-stack-1x fa-inverse"><?= strtoupper($apoyo['file_ext']); ?></span>
												    			</span>
												    		</a>
												    		<h4 style="margin: 0;"><?= $apoyo['raw_name']; ?></h4>
												    		<a href="<?= site_url('apoyo/descargar/' . $apoyo['id']) ?>" class="hidden-xs"><?= htmlspecialchars($apoyo['raw_name']); ?></a>
												    		<p><?= $apoyo['file_size']; ?></p>
												    	</div>
												    	<div class="col-xs-1 pull-right">
												    		<?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
                                                                <div class="btn-group pull-right">
                                                                    <i data-toggle="dropdown" class="dropdown-toggle fa fa-cog fa-lg"></i>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="<?= site_url('apoyo/descargar_original/' . $apoyo['id']) ?>">Descargar original</a></li>
                                                                        <li><a href="<?= site_url('apoyo/eliminar/'. $apoyo['id']); ?>" class="btn-eliminar">Eliminar</a></li>
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
								
							<?php }else{ ?>
							
								<div class="alert alert-warning">
									<strong>INFO!</strong> Aún no se han registrado Documentos de apoyo.
								</div>
								
							<?php } ?>
							
							<?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
							
								<div class="panel panel-default">
									<div class="panel-heading lg"><i class="fa fa-cloud-upload"></i> Subir Documento de apoyo</div>
									
									<div class="panel-body">
										
									<form action="<?= site_url('apoyo/grabar') ?>" method="post" enctype="multipart/form-data">
										<fieldset>
											<div class="row">
												<div class="form-group col-xs-8">
												    <input type="file" class="form-control input-sm" name="apoyo" required/>
												    <input type="hidden" name="grupo" value="<?= $grupo['id']; ?>" />
												</div>
												<div class="form-group col-xs-4">
													<button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fa fa-cloud-upload"></i> Subir</button>
												</div>
											</div>
										</fieldset>
									</form>
										
									</div>
									
								</div>
								
							
							<?php } ?>
							
							<?php if($procesos){ ?>
							
								<div class="panel panel-primary">
									<div class="panel-heading lg">
										<i class="fa fa-sitemap"></i> Procesos
									</div>
									
									<div class="panel-body">
										
										<ul class="list-group">
										
											<?php foreach($procesos as $proceso){ ?>
												
												<li class="list-group-item">
												    <div class="row">
												    	<div class="col-xs-12">
												    		<a href="<?= site_url('modelos/proceso/' . $proceso['id']) ?>">
													    		<span class="fa-stack fa fa-2x pull-left">
																	<i class="fa fa-square fa-stack-2x"></i>
																	<i class="fa fa-sitemap fa-stack-1x fa-inverse"></i>
																</span>
															</a>
															<a href="<?= site_url('modelos/proceso/' . $proceso['id']) ?>">
												    			<h4 style="margin: 0;"><?= $proceso['nombre']; ?></h4>
															</a>
												    		<p><?= $proceso['carpetas']; ?> <?= ($proceso['carpetas'] == 1) ? 'Carpeta' : 'Carpetas'; ?> &bull; <?= $proceso['documentos']; ?> <?= ($proceso['documentos'] == 1) ? 'Documento' : 'Documentos'; ?></p>
												    	</div>
												    </div>
												</li>
													
											<?php } ?>
											
										</ul>
	
									</div>
																
								</div>
								
							<?php } ?>
							
			        	</div>
			        	
			        	<div class="col-md-4">
			        		
                            <?php if(is_file(APPPATH . 'views/aside/id/' . $modelo['id'] .'.php')) $this->load->view('aside/id/' . $modelo['id']); ?>
			        		
			        	</div>
			        
			        </div>
			        
			    </div>
			</div>