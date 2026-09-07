            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Comité de evaluación Periodo <?= $periodo_actual['periodo']; ?></h4>
			</div>		

            <?php foreach($evaluadores as $evaluador){ ?>

        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs text-uppercase"><?= $evaluador['cargo']; ?> <i class="fa fa-angle-right"></i> <?= $evaluador['nombre'] ?></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
                            
                            <?php if( ! $evaluador['usuario_eliminado']){ ?>
                            <h3 class="mt-0 color-alt text-uppercase"><span class="text-muted"><?= $evaluador['cargo']; ?> <i class="fa fa-angle-right"></i></span> <?= $evaluador['nombre'] ?></h3>
                            <a href="mailto:<?= $evaluador['email']; ?>?subject=Consulta%20sobre%20evaluación%20EPPO" class="btn btn-default"><i class="fa fa-envelope"></i> Enviar correo al Evaluador</a>
				            <?php } ?>
				            
					        <div class="table-responsive mt-20">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th class="text-center" style="width: 5%">#</th>
											<th class="text-center">NOMBRE</th>
                                            <th class="text-center">CARGO</th>
                                            <th class="text-center">CREADO</th>
                                            <th class="text-center">MODIFICADO</th>
                                            <th class="text-center"><abbr title="ESTADO FORMULARIO">EST</abbr></th>
                                            <th class="text-center"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                            <th class="text-center" style="width: 10%;"></th>
                                            <th class="text-center" style="width: 10%;"></th>
                                            <th class="text-center" style="width: 10%;"></th>
										</tr>
											
									</thead>
									<tbody>
									
									<?php foreach($evaluador['evaluados'] as $key => $evaluado){ ?>
									
										<tr <?= ($evaluado['estadoformulario'] == 'APE') ? 'class="warning"' : ''; ?>>
                                            <td class="text-center"><?= $key + 1; ?></td>
											<td class="text-center"><?= $evaluado['nombre']; ?></td>
                                            <td class="text-center"><?= $evaluado['cargo']; ?></td>
                                            <td class="text-center"><?= $evaluado['creado']; ?></td>
                                            <td class="text-center"><?= ($evaluado['modificado']) ? $evaluado['modificado'] : (($evaluado['creado']) ? 'No se ha modificado' : ''); ?></td>
                                            <td class="text-center text-uppercase"><strong><?= ($evaluado['id']) ? '<abbr title="'. $evaluado['estadoformulario_descripcion'] .'">'. $evaluado['estadoformulario_nombre'] .'</abbr>' : 'NO CREADO'; ?></strong></td>
                                            <td class="text-center"><?= ($evaluado['resultadoapelacion_puntaje']) ? $evaluado['resultadoapelacion_puntaje'] : $evaluado['resultado_puntaje']; ?></td>
                                            <td class="text-center">
                                                <?php if($evaluado['id']){ ?>
                                                    <a href="<?= site_url('comite/formulario/'. $evaluado['id']); ?>" class="btn btn-danger btn-block"><i class="fa fa-file-o"></i> Formulario</a></td>
                                                <?php } ?>
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

							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>

            <?php } ?>