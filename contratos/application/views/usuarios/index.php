			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuariosss</h4>
			</div>
						
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('usuarios/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Usuario</a>
			</div>	
			
			<div id="alertas">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<?= $alertas; ?>	
					</div>
				</div>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios<a href="<?= site_url('usuarios/nuevo'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Usuario</a></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th>id</th>
											<th>Usuario</th>
											<th>Privilegio</th>
											<th></th>
											<th></th>
											<th></th>
												
										</tr>
											
									</thead>
									<tbody>
									
									<?php foreach($usuarios as $usuario){ ?>
									
										<tr>
											<td style="width: 5%;"><?= $usuario['id']; ?></td>
											<td><a href="<?= site_url('usuarios/editar/'. $usuario['id']); ?>" title=""><?= $usuario['usuario']; ?></a></td>
											<td><?= $usuario['privilegio_nombre']; ?></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('usuarios/activo/'. $usuario['id']); ?>" class="btn btn-sm btn-block btn-<?= ($usuario['activo'] == 1) ? 'success' : 'danger'; ?>" title=""><?= ($usuario['activo'] == 1) ? '<i class="fa fa-check"></i> Activo' : '<i class="fa fa-times"></i> Inactivo'; ?></a></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('usuarios/editar/'. $usuario['id']); ?>" class="btn btn-sm btn-block btn-default" title=""><i class="fa fa-edit"></i> Editar</a></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('usuarios/eliminar/'. $usuario['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar" title=""><i class="fa fa-trash-o"></i> Eliminar</a></td>
									
										</tr>
									<?php } ?>
									
									</tbody>
									
								</table>
								
								<script>
								    $(function(){
								        $('.table').dataTable({
									         bStateSave : true,      
										      fnStateSave :function(settings,data){
										        localStorage.setItem("dataTables_state", JSON.stringify(data));
										      },
										      fnStateLoad: function(settings) {
										        return JSON.parse(localStorage.getItem("dataTables_state"));
										      },
										     'aoColumnDefs': [
											 	{ "bSortable": false, "aTargets": [ 3, 4, 5 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 3, 4, 5] }
											 ]
								        });
								    });
								</script>
									
							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>