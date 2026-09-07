			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Etapas</h4>
			</div>	
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('admin/etapas/nueva'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nueva Etapa</a>
			</div>			
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Etapas<a href="<?= site_url('admin/etapas/nueva'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nueva Etapa</a></div>
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
											<th class="text-center">Periodo</th>
											<th>Descripción</th>
											<th class="text-center">Límite</th>
											<th></th>
											<th></th>
												
										</tr>
											
									</thead>
									<tbody>
									
									<?php foreach($etapas as $etapa){ ?>
										<tr>
											<td style="width: 5%;"><?= $etapa['id']; ?></td>
                                            <td class="text-center"><?= $etapa['creado']; ?></td>
											<td class="text-center"><?= $etapa['modificado']; ?></td>
											<td class="text-center"><?= $etapa['periodo']; ?></td>
                                            <td class="text-justify">
                                                <strong>Etapa <?= $etapa['correlativo']; ?> <i class="fa fa-angle-right"></i> <a href="<?= site_url('admin/etapa/editar/'. $etapa['id']); ?>"><?= $etapa['nombre']; ?></a></strong>
                                                <?php if($etapa['puedeapelar']){ ?><span class="label label-warning">Apelación</span><?php } ?>
                                                <?php if($etapa['puedeevaluar']){ ?><span class="label label-danger">Evaluación</span><?php } ?>
                                                <br/><?= character_limiter($etapa['descripcion'], 80); ?></td>
											<td class="text-center"><?= $etapa['limite']; ?></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/etapas/editar/'. $etapa['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('admin/etapas/eliminar/'. $etapa['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
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
											 	{ "bSortable": false, "aTargets": [ 6, 7 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 6, 7] }
											 ]
								        });
								    });
								</script>
									
							</div>
			        	</div>							
					</div>
			    </div>
			</div>