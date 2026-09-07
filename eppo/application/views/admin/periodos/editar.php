			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Periodos</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Periodos</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
                            <form method="post" action="<?= site_url('admin/periodos/actualizar'); ?>" enctype="multipart/form-data">
				                <fieldset>
								    <legend>Editar Periodo</legend>
								    <div class="row">
								        <div class="col-md-2 col-md-offset-4">	
                                            <div class="form-group">
                                                <label>Periodo</label>
												<input type="number" name="periodo" class="form-control" min="2005" max="2030" value="<?= $periodo['periodo']; ?>" required readonly />
								            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label>Ponderación Objetivo Área</label>
                                                    <div class="input-group">
                                                      <input type="number" min="1" max="99" class="form-control" name="ponderacionobjetivoarea" value="<?= $periodo['ponderacionobjetivoarea']; ?>" />
                                                      <div class="input-group-addon">%</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label>Ponderación Objetivo Individual</label>
                                                    <div class="input-group">
                                                      <input type="number" min="1" max="99" class="form-control" name="ponderacionobjetivoindividual" value="<?= $periodo['ponderacionobjetivoindividual']; ?>" />
                                                      <div class="input-group-addon">%</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
												    <a href="<?= site_url('admin/periodos'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
                                                <p class="visible-xs"></p>
												<div class="col-sm-6">
                                                    <input type="hidden" name="id" value="<?= $periodo['id']; ?>" />
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
                    $('input[name=ponderacionobjetivoarea], input[name=ponderacionobjetivoindividual]').change(function(e){
                        
                        var objetivoarea = $('input[name=ponderacionobjetivoarea]');
                        var objetivoindividual = $('input[name=ponderacionobjetivoindividual]');
                        
                        if((objetivoarea.val() != '') && (objetivoindividual.val() != ''))
                        {
                            if((parseInt(objetivoarea.val()) + parseInt(objetivoindividual.val())) != 100)
                            {
                                alert('El porcentaje de ponderación de la evaluación de objetivos no esta ingresado correctamente.');
                                objetivoindividual.val('');
                                objetivoarea.val('').focus();
                            }
                        }
                    });
                });
            </script>