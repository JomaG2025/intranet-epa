			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Garantías</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Garantías</div>
				<div class="panel-body">

			        <div class="row">

			        	<div class="col-lg-12">
			        		
							 <form method="post" action="<?= site_url('garantias/grabar'); ?>" enctype="multipart/form-data">
							    
							     <fieldset>
							    	
								    <legend>Nueva Garantía</legend>
                                        
                                    <div class="row">
								    
                                        <div class="col-lg-7 col-lg-offset-1 col-md-12">
                                                
                                            <div class="form-horizontal">
							
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
                                                        <input type="text" class="form-control" name="termino" required />
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Institución</label>
                                                    <div class="col-md-4">
                                                        <select name="institucion" class="form-control" autocomplete="off">
                                                            
                                                            <?php foreach($instituciones as $institucion){ ?>
                                                                
                                                            <option value="<?= $institucion['cod']; ?>"><?= str_pad($institucion['cod'], 3, '0', STR_PAD_LEFT); ?> <?= $institucion['nombre']; ?></option>
                                                                
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                        
                                                    <label class="col-md-2 text-right control-label">Serie</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="serie" required />
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

                                            </div>
                                                
                                            <div class="row">
												<div class="col-md-5 col-sm-6 col-md-offset-2">
													<a href="<?= site_url(($this->session->userdata('referrer')) ? $this->session->userdata('referrer') : 'garantias'); ?>" class="btn btn-default btn-block btn-cancelar submit" tabindex="-1"><i class="fa fa-reply"></i> Cancelar y volver</a>
												</div>
												<?php if(in_array($this->session->userdata('privilegio'), array('ADM', 'RES'))){ ?>
												<p class="visible-xs"></p>
												<div class="col-md-5 col-sm-6">
													<button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
												</div>
												<?php } ?>
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
                    
                    $('input[name=inicio], input[name=termino]').datepicker().keypress(function(){ return false; });
                    
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