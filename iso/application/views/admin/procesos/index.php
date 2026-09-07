            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Procesos</h4>
			</div>
						
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('admin/procesos/nuevo'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Proceso</a>
			</div>			

        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Procesos<a href="<?= site_url('admin/procesos/nuevo'); ?>" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Proceso</a></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th class="text-center">id</th>
											<th class="text-center">Creado</th>
											<th class="text-center">Modificado</th>
											<th class="text-center">Nombre</th>
											<th class="text-center">Código</th>
											<th class="text-center">Grupo</th>
											<th class="text-center">Modelo</th>
											<th class="text-center">Estado</th>
											<th class="text-center"></th>
											<th class="text-center"></th>
										</tr>
											
									</thead>
									<tbody class="text-center">
									
									<?php foreach($procesos as $proceso){ ?>
										<tr>
											<td style="width: 5%;"><?= $proceso['id']; ?></td>
                                            <td><?= $proceso['creado']; ?></td>
                                            <td><?= $proceso['modificado']; ?></td>
											<td><a href="<?= site_url('admin/procesos/editar/'. $proceso['id']); ?>" title="<?= $proceso['nombre']; ?>"><?= character_limiter($proceso['nombre'], 32); ?></a></td>
											<td><?= $proceso['codigo']; ?></td>
											<td><?= character_limiter($proceso['grupo_nombre'], 32); ?></td>
											<td><?= character_limiter($proceso['modelo_nombre'], 32); ?></td>
											<td><a href="<?= site_url('admin/procesos/activo/'. $proceso['id']); ?>" class="btn btn-sm btn-block btn-<?= ($proceso['activo'] == 1) ? 'success' : 'danger'; ?>"><?= ($proceso['activo'] == 1) ? '<i class="fa fa-check"></i> Activo' : '<i class="fa fa-times"></i> Inactivo'; ?></a></td>
											<td><a href="<?= site_url('admin/procesos/editar/'. $proceso['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
											<td><a href="<?= site_url('admin/procesos/eliminar/'. $proceso['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
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
											 	{ "bSortable": false, "aTargets": [ 8, 9 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 1, 2, 7, 8, 9] }
											 ]
								        });
								    });
								</script>
									
							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>