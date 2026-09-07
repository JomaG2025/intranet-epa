			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Inicio</h4>
			</div>

            <!-- KPI: contadores por estado -->
            <div class="row" style="margin-bottom:6px;">
                <div class="col-xs-6 col-sm-3">
                    <div class="panel panel-default text-center" style="border-top:3px solid #5bc0de;margin-bottom:8px;">
                        <div class="panel-body" style="padding:12px 8px;">
                            <div style="font-size:30px;font-weight:700;line-height:1;"><?= $conteo['total']; ?></div>
                            <small class="text-muted">Total contratos</small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="panel panel-default text-center" style="border-top:3px solid #5cb85c;margin-bottom:8px;">
                        <div class="panel-body" style="padding:12px 8px;">
                            <div style="font-size:30px;font-weight:700;line-height:1;"><?= $conteo['ING']; ?></div>
                            <small><a href="<?= site_url('contratos?estado=ING'); ?>" class="text-success">Vigentes</a></small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="panel panel-default text-center" style="border-top:3px solid #f0ad4e;margin-bottom:8px;">
                        <div class="panel-body" style="padding:12px 8px;">
                            <div style="font-size:30px;font-weight:700;line-height:1;"><?= $conteo['ALE']; ?></div>
                            <small><a href="<?= site_url('contratos?estado=ALE'); ?>" class="text-warning">Por vencer</a></small>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="panel panel-default text-center" style="border-top:3px solid #d9534f;margin-bottom:8px;">
                        <div class="panel-body" style="padding:12px 8px;">
                            <div style="font-size:30px;font-weight:700;line-height:1;"><?= $conteo['VEN']; ?></div>
                            <small><a href="<?= site_url('contratos?estado=VEN'); ?>" class="text-danger">Vencidos</a></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI: montos y acceso al reporte -->
            <div class="panel panel-default" style="margin-bottom:12px;">
                <div class="panel-body" style="padding:8px 15px;">
                    <?php
                    $meses_es = array('','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
                    foreach ($montos as $m):
                    ?>
                    <span style="margin-right:20px;">
                        <strong><?= $m['moneda']; ?> <?= number_format($m['total'], 0, ',', '.'); ?></strong>
                        <small class="text-muted">comprometido (<?= $m['contratos']; ?> contratos)</small>
                    </span>
                    <?php endforeach; ?>
                    <span style="margin-right:20px;">
                        <strong><?= $vencen_mes; ?></strong>
                        <small class="text-muted">vencen en <?= $meses_es[(int)date('n')]; ?></small>
                    </span>
                    <a href="<?= site_url('reporte'); ?>" class="btn btn-xs btn-default pull-right">
                        <i class="fa fa-bar-chart"></i> Reporte de gestión
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-4">
                    
                    <div class="panel no-border panel-info">
                        <div class="panel-heading lg">
                            Inicio rápido
                        </div>
                        <div class="panel-body">
                            <p>En este módulo podrá administrar y consultar los contratos con terceros ingresados. Seleccione una opción:</p>
                        </div>
                        <div class="list-group">
                            <a href="<?= site_url('contratos/nuevo'); ?>" class="list-group-item">
                                Ingresar nuevo Contrato <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <a href="<?= site_url('contratos'); ?>" class="list-group-item">
                                Consultar todos los Contratos <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <a href="<?= site_url('reporte'); ?>" class="list-group-item">
                                Reporte de gestión <i class="fa fa-bar-chart pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="panel no-border panel-info">
                        <div class="panel-heading lg">
                            Consultar por Proveedor
                        </div>
                        <div class="panel-body">
                            <form id="form-consulta-proveedor" action="<?= site_url('contratos/proveedor') ?>" method="get">
                            
                                <div class="form-group">
                                                        
                                    <label>RUT</label>
                                    <div class="row">
                                        <div class="col-md-9 col-xs-10 pr-5">
                                            <input type="text" autocomplete="off" class="form-control" name="rut" id="rut" maxlength="10" pattern="^[0-9.]{7,10}" required />
                                        </div>
                                        <div class="col-md-3 col-xs-2 pl-5">
                                            <input type="text" class="form-control" name="dv" maxlength="1" id="dv" pattern="^[0-9kK]" required />
                                        </div>
                                    </div>
                                                        
                                </div>
                                
                                <div class="form-group">
                                                        
                                    <label>Razón Social</label>
                                    <div class="form-group">
                                        <input type="text" maxlength="255" name="razon" class="form-control" required />
                                    </div>
                                        
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info btn-block"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                    
                </div>
                <div class="col-lg-9 col-md-8">
                    
                    <div class="panel no-border panel-danger">
                        <div class="panel-heading lg">
                            Últimos Contratos vencidos
                        </div>
                        <table class="table table-hover table-condensed table-striped">
										
				            <thead>
				            	<tr>
				            		<th class="text-center">id</th>
				            		<th class="text-center">Identificación</th>
				            		<th class="text-center">Rut</th>
                                    <th>Razón Social</th>
				            		<th class="text-center">Inicio</th>
                                    <th class="text-center">Término</th>
                                    <th>Responsable</th>
                                    <th class="text-center">Monto</th>
				            	</tr>
				            </thead>
				            <tbody>
                                <?php foreach($vencidos as $contrato){ ?>
									
								    <tr>
                                        <td class="text-center" style="width: 5%;"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['id']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['identificacion']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= formato_monto($contrato['proveedor']); ?>-<?= $contrato['proveedor_dv']; ?></a></td>
                                        <td><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['proveedor_razon']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['inicio']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['termino']; ?></a></td>
                                        <td><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= !empty($contrato['responsable']) ? $contrato['responsable'] : '-'; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['moneda']; ?> <?= $contrato['moneda_simbolo'] ?><?= ($contrato['moneda'] != 'CLP') ? formato_monto_decimal($contrato['monto']) : formato_monto($contrato['monto']); ?></a></td>
									</tr>
                                
								<?php } ?>
                            </tbody>
                        </table>
                        <div class="list-group">
                            <a href="<?= site_url('contratos/vencidos'); ?>" class="list-group-item">
                                Ver todos los Contratos vencidos <i class="fa fa-angle-right pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="panel no-border">
                        <div class="panel-heading lg">
                            Últimos Contratos ingresadas
                        </div>
                        <table class="table table-hover table-condensed table-striped">
										
				            <thead>
				            	<tr>
				            		<th class="text-center">id</th>
				            		<th class="text-center">Identificación</th>
				            		<th class="text-center">Rut</th>
				            		<th>Razón Social</th>
				            		<th class="text-center">Inicio</th>
				            		<th class="text-center">Término</th>
                                    <th>Responsable</th>
				            		<th class="text-center">Monto</th>
				            	</tr>
				            </thead>
				            <tbody>
                                <?php foreach($ultimos as $contrato){ ?>
									
								    <tr>
                                        <td class="text-center" style="width: 5%;"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['id']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['identificacion']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= formato_monto($contrato['proveedor']); ?>-<?= $contrato['proveedor_dv']; ?></a></td>
                                        <td><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['proveedor_razon']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['inicio']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['termino']; ?></a></td>
                                        <td><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= !empty($contrato['responsable']) ? $contrato['responsable'] : '-'; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('contratos/editar/' . $contrato['id']); ?>"><?= $contrato['moneda']; ?> <?= $contrato['moneda_simbolo'] ?><?= ($contrato['moneda'] != 'CLP') ? formato_monto_decimal($contrato['monto']) : formato_monto($contrato['monto']); ?></a></td>
									</tr>
                                
								<?php } ?>
                            </tbody>
                        </table>
                        <div class="list-group">
                            <a href="<?= site_url('contratos'); ?>" class="list-group-item">
                                Ver todos los Contratos<i class="fa fa-angle-right pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
            <script>
                $(function(){
                    
                    $('#rut').Rut({
                        digito_verificador: '#dv',
                        on_error: function(){
                            alert('Rut Incorrecto');
                            $('#dv').val('');
                            $('#rut').focus().select();
                        }
                    });
                    
                    $('#rut').blur(function(e){
                        proveedor_desde_rut($(e.currentTarget).val());                        
                    });
                    
                    $('input[name=razon]').autocomplete({
						source: '<?= site_url('api/proveedores'); ?>',
						minLength: 1,
						select: function(event, ui)
                        {                            
							proveedor_desde_razon(ui.item.value);
						}
					});
                    
                    function proveedor_desde_rut(rut){
                        var rut = rut.split('.').join('');
                        
                        if(rut != '')
                        {   
                            $.ajax({
                                url : '<?= site_url('api/proveedor'); ?>',
                                data : {
                                    rut : rut  
                                },
                                statusCode : {

                                    200 : function(data)
                                    {
                                        $('input[name=dv]').val(data.dv);
                                        $('input[name=razon]').val(data.razon);
                                        $('input[name=inicio]').focus();
                                    }
                                }
                            });
                        }
                    }
                    
                    function proveedor_desde_razon(razon)
                    {
                        $.ajax({
                            url : '<?= site_url('api/proveedor'); ?>',
                            data : {
                                razon : razon  
                            },
				            statusCode : {

                                200 : function(data)
								{
									$('input[name=dv]').val(data.dv);
									$('input[name=rut]').val(data.rut);
                                    $('input[name=inicio]').focus();
								}
                                
                            }
                        });
                    }
                    
                    $('#form-consulta-proveedor').submit(function(e){
                        $('input[name=rut]', e.currentTarget).val($('input[name=rut]', e.currentTarget).val().split('.').join(''));    
                        $('input[name=razon], input[name=dv]', e.currentTarget).prop('disabled', true);    
                    });
                });
            </script>