            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Mi historial</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Mi historial</div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th class="text-center" style="width: 5%">#</th>
											<th class="text-center">CREADO</th>
                                            <th class="text-center">MODIFICADO</th>
                                            <th class="text-center">PERIODO</th>
                                            <th class="text-center">ESTADO</th>
                                            <th class="text-center">EVALUACIÓN</th>
                                            <th class="text-center" style="width: 10%;"></th>
										</tr>
											
									</thead>
									<tbody>
									
									<?php foreach($formularios as $key => $formulario){ ?>
									
										<tr>
                                            <td class="text-center"><?= $key + 1; ?></td>
                                            <td class="text-center"><?= $formulario['creado']; ?></td>
                                            <td class="text-center"><?= ($formulario['modificado']) ? $formulario['modificado'] : 'No se ha modificado'; ?></td>
                                            <td class="text-center"><strong><?= $formulario['periodo']; ?></strong></td>
                                            <td class="text-center text-uppercase"><strong><abbr title="<?= $formulario['estadoformulario_descripcion']; ?>"><?= $formulario['estadoformulario_nombre']; ?></abbr></strong></td>
                                            <td class="text-center"><?= ($formulario['resultadoapelacion_puntaje']) ? $formulario['resultadoapelacion_puntaje'] : $formulario['resultado_puntaje']; ?></td>
                                            <td class="text-center">
                                                <?php if($formulario['periodo'] == $periodo_actual['periodo']){ ?>
                                                    <a href="<?= site_url('formulario'); ?>" class="btn btn-danger btn-block"><i class="fa fa-file-o"></i> Formulario</a>
                                                <?php }else{ ?>
                                                    <a href="<?= site_url('historial/formulario/'. $formulario['id']); ?>" class="btn btn-danger btn-block"><i class="fa fa-file-o"></i> Formulario</a>
                                                <?php } ?>
                                            </td>
									
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
											 	{ "bSortable": false, "aTargets": [ 6 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 6 ] }
											]
								        });
								    });
								</script>
									
							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>