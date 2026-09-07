			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Reportes <i class="fa fa-angle-right"></i> Garantías ingresadas</h4>
			</div>
            <div class="navbar-action hidden-xs">
				<a href="<?= site_url('reporte/ingresadas/imprimir?desde='. $desde .'&amp;hasta='. $hasta); ?>" target="_blank" class="btn btn-info <?= (empty($reportes)) ? 'disabled' : ''; ?>"><i class="fa fa-download"></i> Generar Excel</a> 
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Garantías ingresadas</div>
				<div class="panel-body">
					<form id="form-filtros" action="<?= site_url('reporte/ingresadas') ?>" method="get">
				    	<div class="row">
				    		<div class="form-group col-md-3 col-xs-12">
				    			<div class="radio">
				    				<label>
				    					<input type="radio" name="filtro" value="rango" id="radio-rango" checked>
				    					Entre las fechas
				    				</label>
				    			</div>
				    			<div class="row">	
				    				<div class="col-xs-6">			        				
				    					<input name="desde" type="text" value="<?= $desde; ?>" class="form-control" id="filtro-desde" required="required" placeholder="desde" autocomplete="off"/>
				    				</div>
				    				<div class="col-xs-6">
				    					<input name="hasta" type="text" value="<?= $hasta; ?>" class="form-control" id="filtro-hasta" required="required" placeholder="hasta" autocomplete="off"/>
				    				</div>
				    			</div>
				    		</div>
				    		<div class="form-group col-md-2 col-xs-12 col-md-offset-7">
				        		<p style="margin-top: 10px;" class="hidden-xs hidden-sm">&nbsp;</p>
				        		<button type="submit" class="btn btn-success btn-block"><i class="fa fa-search"></i> Filtrar</button> 				        	
				        	</div>
				    	</div>
			        </form>
			        <p>&nbsp;</p>
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

                    $('#filtro-desde').datepicker().keypress(function(e){return false;});
                    $('#filtro-hasta').datepicker().keypress(function(e){return false;});
                });
        
            </script>