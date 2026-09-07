			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Relacionados</h4>
			</div>

        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-random"></i> Relacionados</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
				            <form method="post" action="<?= site_url('relacionados/grabar'); ?>" enctype="multipart/form-data">
                                <fieldset>
								    <legend>Nuevo Relacionado</legend>
				                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label>Rut</label>
                                                    <div class="row">
                                                        <div class="col-xs-9">
                                                            <input type="text" autocomplete="off" class="form-control text-right" name="rut" id="rut" maxlength="10" pattern="^[0-9.]{7,10}" required />
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <input type="text" class="col-xs-2 form-control" name="dv" id="dv" maxlength="1" autocomplete="off" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label>Nombre</label>
                                                    <input type="text" class="form-control" name="nombre" maxlength="255"placeholder="Nombre" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Relación</label>
                                                <select name="tipo" class="form-control" autocomplete="off">
                                                    <?php foreach($tipos as $tipo){ ?>
                                                    <option value="<?= $tipo['abrev']; ?>"><?= $tipo['descripcion']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>  
                                            <div class="form-group">
                                                <label>Observación</label>
                                                <input type="text" class="form-control" autocomplete="off" name="observacion" maxlength="255" placeholder="Descripción" />
                                            </div>
								            <div class="form-group">
                                                <label>Funcionario con quien se relaciona</label>
                                                <select class="form-control" name="relacionado" disabled autocomplete="off">
                                                    <option value="">Ninguno</option>
                                                    <?php foreach($funcionarios as $funcionario){ ?>
                                                    <option value="<?= $funcionario['rut']; ?>"><?= $funcionario['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <a href="<?= site_url('relacionados'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
                                                </div>
                                                <div class="form-group col-sm-6">
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
                    $('form [name=tipo]').change(function(e){
                        if(($(e.currentTarget).val() == 'FUN') || ($(e.currentTarget).val() == 'DIR')){
                            $('form [name=relacionado]').val('').prop('disabled', 'true');
                        }else{
                            $('form [name=relacionado]').removeAttr('disabled');
                        }
                    });
                    
                    $('#rut').Rut({
                        format_on: 'keyup',
                        digito_verificador: '#dv',
                        on_error: function(){
                            alert('Rut incorrecto');
                            $('#dv').val('');
                            $('#rut').select().focus();
                        }
                    });
                });
            </script>