            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Grupos</h4>
			</div>
						
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('admin/grupos/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Grupo</a>
			</div>

        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Grupos<a href="<?= site_url('admin/grupos/nuevo'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Grupo</a></div>
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
											<th class="text-center">Módelo</th>
											<th class="text-center"></th>
											<th class="text-center"></th>
										</tr>
									</thead>
									<tbody>
									
									<?php foreach($grupos as $grupo){ ?>
										<tr>
											<td style="width: 5%;"><?= $grupo['id']; ?></td>
                                            <td><?= $grupo['creado']; ?></td>
                                            <td><?= $grupo['modificado']; ?></td>
											<td><a href="<?= site_url('admin/grupos/editar/'. $grupo['id']); ?>"><?= character_limiter($grupo['nombre'], 32); ?></a></td>
											<td><?= character_limiter($grupo['modelo_nombre'], 32); ?></td>
											<td><a href="<?= site_url('admin/grupos/editar/'. $grupo['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
											<td><a href="<?= site_url('admin/grupos/eliminar/'. $grupo['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
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
											 	{ "bSortable": false, "aTargets": [ 5, 6 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 1, 2, 5, 6] }
											 ]
								        });
								    });
								</script>
									
							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>