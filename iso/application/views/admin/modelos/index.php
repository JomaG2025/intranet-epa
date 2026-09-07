            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Modelos</h4>
			</div>
						
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('admin/modelos/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Modelo</a>
			</div>			
			
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Modelos<a href="<?= site_url('admin/modelos/nuevo'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Modelo</a></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th>id</th>
											<th>Creado</th>
											<th>Modificado</th>
											<th>Nombre</th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									
									<?php foreach($modelos as $modelo){ ?>
									
										<tr>
											<td style="width: 5%;"><?= $modelo['id']; ?></td>
                                            <td><?= $modelo['creado']; ?></td>
                                            <td><?= $modelo['modificado']; ?></td>
											<td><a href="<?= site_url('admin/modelos/editar/'. $modelo['id']); ?>"><?= $modelo['nombre']; ?></a></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/modelos/activo/'. $modelo['id']); ?>" class="btn btn-sm btn-block btn-<?= ($modelo['activo'] == 1) ? 'success' : 'danger'; ?>"><?= ($modelo['activo'] == 1) ? '<i class="fa fa-check"></i> Activo' : '<i class="fa fa-times"></i> Inactivo'; ?></a></td>
                                            <td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/modelos/editar/'. $modelo['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/modelos/eliminar/'. $modelo['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
									
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
											 	{ "bSortable": false, "aTargets": [ 4, 5, 6] },
											 	{ "bSearchable": false, "aTargets": [ 0, 4, 5, 6+] }
											 ]
								        });
								    });
								</script>
									
							</div>
			        	</div>						
					</div>
			    </div>
			</div>