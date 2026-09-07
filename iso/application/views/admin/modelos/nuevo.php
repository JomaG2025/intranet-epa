            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Modelos</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Modelos</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
							 <form method="post" action="<?= site_url('admin/modelos/grabar'); ?>" enctype="multipart/form-data">
							     <fieldset>
								    <legend>Nuevo Modelo</legend>
								    <div class="row">
								        <div class="col-md-6">	
											<div class="form-group">
                                                <label>Nombre</label>
												<input type="text" name="nombre" maxlength="64" class="form-control" required />
											</div>         					    
									   </div>     
									   <div class="col-md-6 form-group">
                                           <label class="hidden-xs">&nbsp;</label>
								           <div class="row">
                                               <div class="col-sm-6">
                                                   <a href="<?= site_url('modelos'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
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