			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Auditoría</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Auditoría</div>
				<div class="panel-body">
					
					<form id="form-filtros" action="<?= site_url('admin/auditoria') ?>" method="get">
			        	
				    	<div class="row">
				    	
				    		<div class="form-group col-md-3 col-xs-12">
				    			<div class="radio">
				    				<label>
				    					<input type="radio" name="filtro" value="rango" id="radio-rango" checked>
				    					Rango
				    				</label>
				    			</div>
				    			<div class="row">	
				    				<div class="col-xs-6">			        				
				    					<input name="desde" type="text" class="form-control" id="filtro-desde" required="required" placeholder="desde" autocomplete="off"/>
				    				</div>
				    				<div class="col-xs-6">
				    					<input name="hasta" type="text" class="form-control" id="filtro-hasta" required="required" placeholder="hasta" autocomplete="off"/>
				    				</div>
				    			</div>
				    		</div>
				    		
				    		<div class="form-group col-md-2 col-xs-6">
				    			<div class="radio">
				    				<label>
				    					<input type="radio" name="filtro" value="dia" id="radio-dia">
				    					Día
				    				</label>
				    			</div>				        				
				    			<input name="dia" type="text" class="form-control" id="filtro-dia" required="required" />			        			
				    		</div>
				    		
				    		<div class="form-group col-md-2 col-xs-6">
				    		
				    			<div class="radio">
				    				<label>
				    					<input type="radio" name="filtro" value="mes" id="radio-mes">
				    					Mes
				    				</label>
				    			</div>				        				
				    			<select name="mes" class="form-control" id="filtro-mes">
				    				<?php foreach($anos as $ano){ ?>
				    					<option value="12-<?= $ano['ano']; ?>">Diciembre <?= $ano['ano']; ?></option>
										<option value="11-<?= $ano['ano']; ?>">Noviembre <?= $ano['ano']; ?></option>
										<option value="10-<?= $ano['ano']; ?>">Octubre <?= $ano['ano']; ?></option>
										<option value="9-<?= $ano['ano']; ?>">Septiembre <?= $ano['ano']; ?></option>
										<option value="8-<?= $ano['ano']; ?>">Agosto <?= $ano['ano']; ?></option>
										<option value="7-<?= $ano['ano']; ?>">Julio <?= $ano['ano']; ?></option>
										<option value="6-<?= $ano['ano']; ?>">Junio <?= $ano['ano']; ?></option>
										<option value="5-<?= $ano['ano']; ?>">Mayo <?= $ano['ano']; ?></option>
										<option value="4-<?= $ano['ano']; ?>">Abril <?= $ano['ano']; ?></option>
										<option value="3-<?= $ano['ano']; ?>">Marzo <?= $ano['ano']; ?></option>
										<option value="2-<?= $ano['ano']; ?>">Febrero <?= $ano['ano']; ?></option>
										<option value="1-<?= $ano['ano']; ?>">Enero <?= $ano['ano']; ?></option>
				    				<?php } ?>
				    			</select>
				    		
				    		</div>
				    	
				    		<div class="form-group col-md-2 col-xs-12 col-md-offset-3">
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
									<th>Fecha</th>
									<th>Usuario</th>
									<th>Evento</th>
									<th>Recurso</th>
									<th>Instancia</th>
									<th>IP</th>
									<th></th>
								</tr>
									
							</thead>
							<tbody>
							
							<?php foreach($auditorias as $auditoria){ ?>
								
								<tr>
									<td style="width: 5%;"><?= $auditoria['id']; ?></td>
									<td><?= $auditoria['fecha']; ?></td>
									<td><?= $auditoria['usuario']; ?></td>
									<td><?= $auditoria['evento_nombre']; ?></td>
									<td><?= $auditoria['recurso']; ?></td>
									<td><?= $auditoria['instancia']; ?></td>
									<td><?= $auditoria['ip']; ?></td>
									<td><?= $auditoria['adicional']; ?></td>
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

                    $('#filtro-desde').datetimepicker({defaultValue: '<?= date('Y-m-d'); ?> 00:00'}).keypress(function(e){return false;});
                    $('#filtro-hasta').datetimepicker({defaultValue: '<?= date('Y-m-d'); ?> 23:59'}).keypress(function(e){return false;});
                    $('#filtro-dia').datepicker().keypress(function(e){return false;});

                    <?php if( ! $filtro){ ?>
                    $('#radio-rango').prop('checked', true);
                    $('#filtro-dia').attr('disabled', 'disabled');
                    $('#filtro-mes').attr('disabled', 'disabled');
                    $('#btn-imprimir').addClass('disabled');

                    <?php } ?>

                    <?php 

                        switch($filtro){

                            case 'rango':
                    ?>
                            $('#radio-rango').prop('checked', true);
                            $('#filtro-desde').removeAttr('disabled').val('<?= $desde; ?>');
                            $('#filtro-hasta').removeAttr('disabled').val('<?= $hasta; ?>');
                            $('#filtro-dia').attr('disabled', 'disabled')
                            $('#filtro-mes').attr('disabled', 'disabled');
                    <?php
                            break;

                            case 'dia':
                    ?>
                            $('#radio-dia').prop('checked', true);
                            $('#filtro-desde').attr('disabled', 'disabled');
                            $('#filtro-hasta').attr('disabled', 'disabled');
                            $('#filtro-dia').removeAttr('disabled').val('<?= $valor; ?>');
                            $('#filtro-mes').attr('disabled', 'disabled');
                    <?php
                            break;

                            case 'mes':
                    ?>
                            $('#radio-mes').prop('checked', true);
                            $('#filtro-desde').attr('disabled', 'disabled');
                            $('#filtro-hasta').attr('disabled', 'disabled');
                            $('#filtro-dia').attr('disabled', 'disabled');
                            $('#filtro-mes').removeAttr('disabled').val('<?= $valor; ?>');
                    <?php
                            break;

                    } ?>

                    $('#radio-rango').change(function(e)
                    {
                        if($(e.currentTarget).prop('checked'))
                        {
                            $('#filtro-desde').removeAttr('disabled');
                            $('#filtro-hasta').removeAttr('disabled');
                            $('#filtro-dia').attr('disabled', 'disabled').val('');
                            $('#filtro-mes').attr('disabled', 'disabled');
                        }
                    });

                    $('#radio-dia').change(function(e)
                    {
                        if($(e.currentTarget).prop('checked'))
                        {
                            $('#filtro-desde').attr('disabled', 'disabled').val('');
                            $('#filtro-hasta').attr('disabled', 'disabled').val('');
                            $('#filtro-dia').removeAttr('disabled');
                            $('#filtro-mes').attr('disabled', 'disabled');
                        }
                    });

                    $('#radio-mes').change(function(e)
                    {
                        if($(e.currentTarget).prop('checked'))
                        {
                            $('#filtro-desde').attr('disabled', 'disabled').val('');
                            $('#filtro-hasta').attr('disabled', 'disabled').val('');
                            $('#filtro-dia').attr('disabled', 'disabled').val('');
                            $('#filtro-mes').removeAttr('disabled');
                        }
                    });

                });
        
            </script>