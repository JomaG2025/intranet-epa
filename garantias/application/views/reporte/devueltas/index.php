			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Reportes <i class="fa fa-angle-right"></i> Garantías devueltas al Proveedor</h4>
			</div>
            <div class="navbar-action hidden-xs">
				<a href="<?= site_url('reporte/devueltas/imprimir'); ?>" target="_blank" class="btn btn-info"><i class="fa fa-download"></i> Generar Excel</a> 
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Garantías devueltas al Proveedor</div>
				<div class="panel-body">
			        <div class="table-responsive">
						<table class="table table-hover table-condensed table-striped">
							<thead>
								<tr>
									<th>id</th>
									<th>Ingresado</th>
									<th>Estado</th>
									<th>Rut</th>
									<th>Razón Social</th>
									<th>Inicio</th>
									<th>Término</th>
									<th>Monto</th>
								</tr>
							</thead>
							<tbody>
							
							<?php foreach($reportes as $reporte){ ?>
								
								<tr>
									<td style="width: 5%;"><?= $reporte['id']; ?></td>
									<td><?= $reporte['creado']; ?></td>
									<td><?= $reporte['estado_nombre']; ?></td>
									<td><?= formato_monto($reporte['proveedor']); ?>-<?= $reporte['proveedor_dv']; ?></td>
                                    <td><?= $reporte['proveedor_razon']; ?></td>
                                    <td><?= $reporte['inicio']; ?></td>
                                    <td><?= $reporte['termino']; ?></td>
                                    <td><?= $reporte['moneda']; ?> <?= $reporte['moneda_simbolo']?><?= ($reporte['moneda'] != 'CLP') ? formato_monto_decimal($reporte['monto']) : formato_monto($reporte['monto']); ?></td>
								</tr>
							
							<?php } ?>
							</tbody>
						</table>
					</div>
			    </div>
			</div>
            <script>
                $(function(){
                    $('.table').dataTable({
                        bStateSave : false, 
                        pageLength: 100
                    });
                });
            </script>