			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Inicio</h4>
			</div>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    
                    <div class="panel no-border panel-info">
                        <div class="panel-heading lg">
                            Inicio rápido
                        </div>
                        <div class="panel-body">
                            <p>En este módulo podrá administrar y consultar las garantías ingresadas. Seleccione una opción:</p>
                        </div>
                        <div class="list-group">
                            <?php if(in_array($this->session->userdata('privilegio'), array('ADM', 'RES'))){ ?>
                            <a href="<?= site_url('garantias/nueva'); ?>" class="list-group-item">
                                Ingresar nueva Garantía <i class="fa fa-angle-right pull-right"></i>
                            </a>
                            <?php } ?>
                            <a href="<?= site_url('garantias'); ?>" class="list-group-item">
                                Consultar todas las Garantías <i class="fa fa-angle-right pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="panel no-border panel-info">
                        <div class="panel-heading lg">
                            Consultar por Proveedor
                        </div>
                        <div class="panel-body">
                            <form id="form-consulta-proveedor" action="<?= site_url('garantias/proveedor') ?>" method="get">
                            
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
                            Últimas Garantías vencidas
                        </div>
                        <table class="table table-hover table-condensed table-striped">
										
				            <thead>
				            	<tr>
				            		<th class="text-center">id</th>
				            		<th class="text-center">Rut</th>
                                    <th>Razón Social</th>
				            		<th class="text-center">Inicio</th>
				            		<th class="text-center">Término</th>
				            		<th class="text-center">Monto</th>
				            	</tr>
				            </thead>
				            <tbody>
                                <?php foreach($vencidas as $garantia){ ?>
									
								    <tr>
                                        <td class="text-center" style="width: 5%;"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['id']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= formato_monto($garantia['proveedor']); ?>-<?= $garantia['proveedor_dv']; ?></a></td>
                                        <td><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['proveedor_razon']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['inicio']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['termino']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['moneda']; ?> <?= $garantia['moneda_simbolo']?><?= ($garantia['moneda'] != 'CLP') ? formato_monto_decimal($garantia['monto']) : formato_monto($garantia['monto']); ?></a></td>
									</tr>
                                
								<?php } ?>
                            </tbody>
                        </table>
                        <div class="list-group">
                            <a href="<?= site_url('garantias/vencidas'); ?>" class="list-group-item">
                                Ver todas las Garantías vencidas <i class="fa fa-angle-right pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="panel no-border panel-success">
                        <div class="panel-heading lg">
                            Últimas Garantías devueltas al Proveedor
                        </div>
                        <table class="table table-hover table-condensed table-striped">
										
				            <thead>
				            	<tr>
				            		<th class="text-center">id</th>
				            		<th class="text-center">Rut</th>
				            		<th>Razón Social</th>
				            		<th class="text-center">Inicio</th>
				            		<th class="text-center">Término</th>
				            		<th class="text-center">Monto</th>
				            	</tr>
				            </thead>
				            <tbody>
                                <?php foreach($devueltas as $garantia){ ?>
									
								    <tr>
                                        <td class="text-center" style="width: 5%;"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['id']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= formato_monto($garantia['proveedor']); ?>-<?= $garantia['proveedor_dv']; ?></a></td>
                                        <td><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['proveedor_razon']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['inicio']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['termino']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['moneda']; ?> <?= $garantia['moneda_simbolo']?><?= ($garantia['moneda'] != 'CLP') ? formato_monto_decimal($garantia['monto']) : formato_monto($garantia['monto']); ?></a></td>
									</tr>
                                
								<?php } ?>
                            </tbody>
                        </table>
                        <div class="list-group">
                            <a href="<?= site_url('garantias/devueltas'); ?>" class="list-group-item">
                                Ver todas las Garantías devueltas al Proveedor <i class="fa fa-angle-right pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="panel no-border panel-success">
                        <div class="panel-heading lg">
                            Últimas Garantías cobradas
                        </div>
                        <table class="table table-hover table-condensed table-striped">
										
				            <thead>
				            	<tr>
				            		<th class="text-center">id</th>
				            		<th class="text-center">Rut</th>
				            		<th>Razón Social</th>
				            		<th class="text-center">Inicio</th>
				            		<th class="text-center">Término</th>
				            		<th class="text-center">Monto</th>
				            	</tr>
				            </thead>
				            <tbody>
                                <?php foreach($cobradas as $garantia){ ?>
									
								    <tr>
                                        <td class="text-center" style="width: 5%;"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['id']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= formato_monto($garantia['proveedor']); ?>-<?= $garantia['proveedor_dv']; ?></a></td>
                                        <td><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['proveedor_razon']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['inicio']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['termino']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['moneda']; ?> <?= $garantia['moneda_simbolo']?><?= ($garantia['moneda'] != 'CLP') ? formato_monto_decimal($garantia['monto']) : formato_monto($garantia['monto']); ?></a></td>
									</tr>
                                
								<?php } ?>
                            </tbody>
                        </table>
                        <div class="list-group">
                            <a href="<?= site_url('garantias/cobradas'); ?>" class="list-group-item">
                                Ver todas las Garantías cobradas <i class="fa fa-angle-right pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="panel no-border">
                        <div class="panel-heading lg">
                            Últimas Garantías ingresadas
                        </div>
                        <table class="table table-hover table-condensed table-striped">
										
				            <thead>
				            	<tr>
				            		<th class="text-center">id</th>
				            		<th class="text-center">Rut</th>
				            		<th>Razón Social</th>
				            		<th class="text-center">Inicio</th>
				            		<th class="text-center">Término</th>
				            		<th class="text-center">Monto</th>
				            	</tr>
				            </thead>
				            <tbody>
                                <?php foreach($ultimas as $garantia){ ?>
									
								    <tr>
                                        <td class="text-center" style="width: 5%;"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['id']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= formato_monto($garantia['proveedor']); ?>-<?= $garantia['proveedor_dv']; ?></a></td>
                                        <td><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['proveedor_razon']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['inicio']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['termino']; ?></a></td>
                                        <td class="text-center"><a href="<?= site_url('garantias/editar/' . $garantia['id']); ?>"><?= $garantia['moneda']; ?> <?= $garantia['moneda_simbolo']?><?= ($garantia['moneda'] != 'CLP') ? formato_monto_decimal($garantia['monto']) : formato_monto($garantia['monto']); ?></a></td>
									</tr>
                                
								<?php } ?>
                            </tbody>
                        </table>
                        <div class="list-group">
                            <a href="<?= site_url('garantias'); ?>" class="list-group-item">
                                Ver todas las Garantías<i class="fa fa-angle-right pull-right"></i>
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