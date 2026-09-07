			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Proveedores</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Proveedores</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
                            <form method="post" action="<?= site_url('admin/proveedores/actualizar'); ?>" enctype="multipart/form-data">
				                <fieldset>
								    <legend>Editar Usuario</legend>
								    <div class="row">
									    <div class="col-md-6">	
											<div class="form-group">  
                                                   <label>RUT</label>
                                                   <div class="row">
                                                       <div class="col-md-9 col-xs-11 pr-5">
                                                           <input type="text" autocomplete="off" class="form-control" name="rut" id="rut" maxlength="10" pattern="^[0-9.]{7,10}" value="<?= $proveedor['rut']; ?>" disabled />
                                                       </div>
                                                       <div class="col-md-3 col-xs-1 pl-5">
                                                           <input type="text" class="form-control" name="dv" maxlength="1" id="dv" pattern="^[0-9kK]" value="<?= $proveedor['dv']; ?>" required />
                                                       </div>
                                                   </div>  
                                               </div>        					    
									       </div>
									       <div class="col-md-6">	
									        <div class="form-group">
												<label>Razón Social</label>
												<input type="text" maxlength="255" name="razon" class="form-control" value="<?= $proveedor['razon']; ?>" required />
											</div>
											<div class="row">
												<div class="col-sm-6">
													<a href="<?= site_url('admin/proveedores'); ?>" class="btn btn-default btn-block btn-cancelar submit"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
												<p class="visible-xs"></p>
												<div class="col-sm-6">
													<input type="hidden" name="id" value="<?= $proveedor['id']; ?>" />
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