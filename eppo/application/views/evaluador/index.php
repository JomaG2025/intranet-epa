            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Mis evaluados Periodo <?= $periodo; ?></h4>
			</div>		
            <div class="navbar-action hidden-xs">
			    <a href="#modal-periodo" class="btn btn-info" data-toggle="modal"><i class="fa fa-history"></i> Consultar otro periodo</a>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Mis evaluados Periodo <?= $periodo; ?></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th class="text-center" style="width: 5%">#</th>
											<th class="text-center">NOMBRE</th>
                                            <th class="text-center">CARGO</th>
                                            <th class="text-center">CREADO</th>
                                            <th class="text-center">MODIFICADO</th>
                                            <th class="text-center"><abbr title="ESTADO FORMULARIO">EST</abbr></th>
                                            <th class="text-center"><abbr title="PUNTAJE">PUN</abbr></th>
                                            <th class="text-center" style="width: 10%;"></th>
                                            <th class="text-center" style="width: 10%;"></th>
                                            <th class="text-center" style="width: 10%;"></th>
										</tr>
											
									</thead>
									<tbody>
									
									<?php foreach($evaluados as $key => $evaluado){ ?>
									
										<tr>
                                            <td class="text-center"><?= $key + 1; ?></td>
											<td class="text-center"><?= $evaluado['nombre']; ?></td>
                                            <td class="text-center"><?= $evaluado['cargo']; ?></td>
                                            <td class="text-center"><?= $evaluado['creado']; ?></td>
                                            <td class="text-center"><?= ($evaluado['modificado']) ? $evaluado['modificado'] : (($evaluado['creado']) ? 'No se ha modificado' : ''); ?></td>
                                            <td class="text-center text-uppercase"><strong><?= ($evaluado['id']) ? '<abbr title="'. $evaluado['estadoformulario_descripcion'] .'">'. $evaluado['estadoformulario_nombre'] .'</abbr>' : 'NO CREADO'; ?></strong></td>
                                            <td class="text-center"><?= ($evaluado['resultadoapelacion_puntaje']) ? $evaluado['resultadoapelacion_puntaje'] : $evaluado['resultado_puntaje']; ?></td>
                                            <td class="text-center">
                                                <?php if($evaluado['id']){ ?>
                                                <a href="<?= site_url('evaluador/formulario/'. $evaluado['id']); ?>" class="btn btn-danger btn-block <?= ( ! $evaluado['id']) ? 'disabled' : ''; ?>"><i class="fa fa-file-o"></i> Formulario</a>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($evaluado['id']){ ?>
                                                    <a href="<?= site_url('formulario/excel/'. $evaluado['id']); ?>" class="btn btn-info btn-block"><i class="fa fa-file-excel-o"></i> Descargar Excel</a>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center"><a href="mailto:<?= $evaluado['email']; ?>?subject=Consulta%20sobre%20evaluación%20EPPO" class="btn btn-default btn-block"><i class="fa fa-envelope"></i> Enviar correo</a></td>
									
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
											 	{ "bSortable": false, "aTargets": [ 7, 8 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 7, 8] }
											 ]
								        });
								    });
								</script>
									
							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>

            <div class="modal fade" id="modal-periodo" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Seleccionar Periodo</h4>
                        </div>
                        <div class="modal-body">
                            <form id="form-periodo" method="post" action="#">
                                <fieldset>
                                    <div class="form-group">
                                        <label>Seleccione</label>
                                        <select class="form-control">
                                            <?php foreach($periodos as $value){ ?>
                                            <option value="<?= $value['periodo']; ?>" <?= ($value['periodo'] == $periodo) ? 'selected' : ''; ?>><?= $value['periodo']; ?><?= ($value['periodo'] == $periodo_actual['periodo']) ? ' (Actual)' : ''; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(function(){
                    $('#form-periodo select').change(function(e){
                         window.location = '<?= site_url('evaluador/index') ?>/' + $(e.currentTarget).val(); 
                    });
                });
            </script>