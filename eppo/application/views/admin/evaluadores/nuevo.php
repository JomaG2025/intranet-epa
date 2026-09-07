			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Evaluadores</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Evaluadores</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
                            <form method="post" action="<?= site_url('admin/evaluadores/grabar'); ?>" enctype="multipart/form-data">
				                <fieldset>
								    <legend>Nuevo Evaluador</legend>
								    <div class="row">
								        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>Periodo</label>
                                                    <select name="periodo" class="form-control" autofocus>
                                                        <?php foreach($periodos as $periodo){ ?>
                                                        <option value="<?= $periodo['periodo']; ?>"><?= $periodo['periodo']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-8 form-group">
                                                    <label>Evaluador</label>
                                                    <select name="usuario" class="form-control">
                                                        <option value="">Seleccionar...</option>
                                                        <?php foreach($usuarios as $item){ ?>
                                                        <option value="<?= $item['usuario']; ?>"><?= $item['usuario']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Evaluados</label>
												<div class="well">
                                                
                                                    <?php foreach($usuarios as $item){ ?>
                                                        <?php if($item['activo']){ ?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" class="checkbox-evaluados" name="evaluados[]" value="<?= $item['usuario']; ?>" />
                                                                    <?= (empty($item['nombre'])) ? $item['usuario'] : $item['nombre']; ?>
                                                              </label>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    
                                                </div>
								            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
												    <a href="<?= site_url('admin/evaluadores'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
                                                <p class="visible-xs"></p>
												<div class="col-sm-6">
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

                    $('select [name=periodo], select[name=usuario]').change(function(e){
                        if($('select[name=usuario]').val() != '')
                        {
                            var periodo = $('select[name=periodo]').val();
                            var usuario = $('select[name=usuario]').val();
                            
                            $.ajax({
                                url: '<?= site_url('api/evaluador/verificar'); ?>/' + periodo + '/' + usuario,
                                statusCode : {
                                    200 : function(data){
                                        alert('Ya se encuentra registrado el usuario para este periodo. Ingrese otra combinación.');
                                        $(e.currentTarget).blur();
                                        $('select[name=usuario]').prop('selectedIndex', 0);
                                    }
                                }
                            });
                        }
                    });
                    
                    $('select[name=usuario]').change(function(e){
                        $('.checkbox-evaluados').parent().removeClass('disabled');
                        $('.checkbox-evaluados').attr('disabled', false).each(function(i, item){
                            if($(e.currentTarget).val() == $(item).val())
                            {
                               $(item).prop('checked', false).prop('disabled', true).parent().addClass('disabled'); 
                            }
                        });
                    });
                });
            </script>