			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Contratos</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Contratos</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
							 <form method="post" action="<?= site_url('contratos/grabar'); ?>" enctype="multipart/form-data">
							     <fieldset>
								    <legend>Nuevo Contrato</legend>
                                    <div class="row">
                                        <div class="col-lg-7 col-lg-offset-1 col-md-12">
                                            <div class="form-horizontal">
                                                
                                                <div class="form-group">
                                                        
                                                    <label class="col-md-2 text-right control-label">Identificación</label>
                                                    <div class="col-md-10">
                                                        <input type="text" maxlength="255" name="identificacion" placeholder="Identificación y Nº del acto administrativo" class="form-control" />
                                                    </div>
                                                        
                                                </div>
                                                
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Fecha doc.</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="fecha" required />
                                                    </div>
                                                        
                                                </div>
							
                                                <div class="form-group">
                                                        
                                                    <label class="col-md-2 text-right control-label">RUT</label>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-9 col-xs-10 pr-5">
                                                                <input type="text" autocomplete="off" class="form-control" name="rut" id="rut" maxlength="10" pattern="^[0-9.]{7,10}" required />
                                                            </div>
                                                            <div class="col-md-3 col-xs-2 pl-5">
                                                                <input type="text" class="form-control" name="dv" maxlength="1" id="dv" pattern="^[0-9kK]" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">
                                                        
                                                    <label class="col-md-2 text-right control-label">Razón Social</label>
                                                    <div class="col-md-10">
                                                        <input type="text" maxlength="255" name="razon" class="form-control" required />
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Fecha de Inicio</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="inicio" required />
                                                    </div>
                                                        
                                                    <label class="col-md-2 text-right control-label">Término</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="termino" placeholder="opcional" />
                                                    </div>
                                                        
                                                </div>
                                                
                                                <div class="form-group">
                                                
                                                    <label class="col-md-2 col-md-offset-6 text-right control-label">Días alerta</label>
                                                    <div class="col-md-4">
                                                        <select name="diasalerta" class="form-control" autocomplete="off">
                                                            <option value="10" selected>10 días</option>
                                                            <option value="15">15 días</option>
                                                            <option value="20">20 días</option>
                                                            <option value="30">30 días</option>
                                                            <option value="45">45 días</option>
                                                            <option value="60">60 días</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Tipo de plazo</label>
                                                    <div class="col-md-4">
                                                        <select name="tipoplazo" class="form-control" autocomplete="off">
                                                            
                                                            <?php foreach($tipoplazos as $tipoplazo){ ?>
                                                                
                                                            <option value="<?= $tipoplazo['abrev']; ?>"><?= $tipoplazo['nombre']; ?></option>
                                                                
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                        
                                                    <label class="col-md-2 text-right control-label">Tipo de pago</label>
                                                    <div class="col-md-4">
                                                        <select name="tipopago" class="form-control" autocomplete="off">
                                                            
                                                            <?php foreach($tipopagos as $tipopago){ ?>
                                                                
                                                            <option value="<?= $tipopago['abrev']; ?>"><?= $tipopago['nombre']; ?></option>
                                                                
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Glosa</label>
                                                    <div class="col-md-10">
                                                        <textarea name="glosa" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Adjunto</label>
                                                    <div class="col-md-10">
                                                        <input type="file" class="form-control" name="archivo" />
                                                        <p class="text-muted">Sólo se pueden adjuntar archivos con extension pdf con un <strong>máximo</strong> de 4MB.</p>
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Monto</label>
                                                    <div class="col-md-10">
                                                            
                                                        <div class="well">
                                                        
                                                            <div class="row">
                                                                
                                                                <div class="col-sm-6">
                                                                    <select name="moneda" class="form-control" autocomplete="off">
                                                            
                                                                        <?php foreach($monedas as $moneda){ ?>

                                                                        <option value="<?= $moneda['cod']; ?>"><?= $moneda['nombre']; ?></option>

                                                                        <?php } ?>
                                                                            
                                                                    </select>
                                                                </div>
                                                                    
                                                                <p class="visible-xs"></p>
                                                                    
                                                                <div class="col-sm-6">
                                                                    <input type="number" name="monto" class="form-control" min="0" step="any" value="0" required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    
                                                    <label class="col-md-2 text-right control-label">Contraparte técnica</label>
                                                    <div class="col-md-10">
                                                            
                                                        <div class="well">
                                                            <?php foreach($usuarios as $item){ ?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="contrapartes[]" value="<?= $item['usuario']; ?>" />
                                                                    <?= (empty($item['nombre'])) ? $item['usuario'] : $item['nombre']; ?>
                                                                </label>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <?php } ?>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                </div>

                                            </div>
                                                
                                            <div class="row">
												<div class="col-md-5 col-sm-6 col-md-offset-2">
													<a href="<?= site_url(($this->session->userdata('referrer')) ? $this->session->userdata('referrer') : 'contratos'); ?>" class="btn btn-default btn-block btn-cancelar submit" tabindex="-1"><i class="fa fa-reply"></i> Cancelar y volver</a>
												</div>
												<p class="visible-xs"></p>
												<div class="col-md-5 col-sm-6">
													<button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
												</div>
											</div>
                                        </div>
                                    </div>
				                </fieldset>
                            </form>
			        	</div>						
					</div>
			    </div>
			</div>
            <script>
                $(function(){
                    
                    $('input[name=fecha], input[name=inicio], input[name=termino]').datepicker().keypress(function(){ return false; });
                    
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
                    
                    $('body').on('change', 'input[name=archivo]', function(e){
                        var files = e.currentTarget.files;
                        for(var i = 0; i < files.length; i++)
                        {	
                            var name = files[i].name.split('.');
                            
                            if(
                                (name[name.length - 1].toLowerCase() != 'pdf')
                            ){
                                $(e.currentTarget).replaceWith($(e.currentTarget).clone());
                                alert('Formato de archivo no permitido.');
                                return false;
                            }

                            if(files[i].size > 4096000)
                            {
                                $(e.currentTarget).replaceWith($(e.currentTarget).clone());
                                alert('El tamaño del archivo supera el permitido.');
                                return false;
                            }
                        }
                    });
                });
            </script>