            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
			</div>		
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('admin/usuarios/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Usuario</a>
			</div>			
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios<a href="<?= site_url('admin/usuarios/nuevo'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Usuario</a></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th>id</th>
											<th class="text-center">Creado</th>
											<th class="text-center">Modificado</th>
											<th class="text-center">Usuario</th>
											<th class="text-center">Privilegio</th>
											<th></th>
											<th></th>
											<th></th>
												
										</tr>
											
									</thead>
									<tbody>
									
									<?php foreach($usuarios as $usuario){ ?>
									
										<tr>
											<td style="width: 5%;"><?= $usuario['id']; ?></td>
                                            <td class="text-center"><?= $usuario['creado']; ?></td>
                                            <td class="text-center"><?= $usuario['modificado']; ?></td>
											<td class="text-center"><a href="<?= site_url('admin/usuarios/editar/'. $usuario['id']); ?>"><?= $usuario['usuario']; ?></a></td>
											<td class="text-center"><?= $usuario['privilegio_nombre']; ?></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/usuarios/activo/'. $usuario['id']); ?>" class="btn btn-sm btn-block btn-<?= ($usuario['activo'] == 1) ? 'success' : 'danger'; ?>" title=""><?= ($usuario['activo'] == 1) ? '<i class="fa fa-check"></i> Activo' : '<i class="fa fa-times"></i> Inactivo'; ?></a></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/usuarios/editar/'. $usuario['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/usuarios/eliminar/'. $usuario['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
									
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
											 	{ "bSortable": false, "aTargets": [ 5, 6, 7 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 5, 6, 7] }
											 ]
								        });
								    });
								</script>
									
							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>