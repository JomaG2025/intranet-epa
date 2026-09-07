			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Comité evaluador</h4>
			</div>
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('admin/comites/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Integrante</a>
			</div>			
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Comité evaluador<a href="<?= site_url('admin/comites/nuevo'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Integrante</a></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th>id</th>
											<th class="text-center">Creado</th>
                                            <th class="text-center">Periodo</th>
                                            <th class="text-center">Usuario</th>
											<th></th>
										</tr>
											
									</thead>
									<tbody>
									
									<?php foreach($comites as $comite){ ?>
									
										<tr>
											<td style="width: 5%;"><?= $comite['id']; ?></td>
                                            <td class="text-center"><?= $comite['creado']; ?></td>
                                            <td class="text-center"><?= $comite['periodo']; ?></td>
                                            <td class="text-center"><?= $comite['usuario']; ?></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/comites/eliminar/'. $comite['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
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
											 	{ "bSortable": false, "aTargets": [ 4 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 4] }
											 ]
								        });
								    });
								</script>
									
							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>