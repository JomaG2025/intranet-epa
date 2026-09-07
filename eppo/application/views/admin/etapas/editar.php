			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Etapas</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Etapas</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
                            <form method="post" action="<?= site_url('admin/etapas/actualizar'); ?>" enctype="multipart/form-data">
				                <fieldset>
								    <legend>Editar Etapa</legend>
								    <div class="row">
								        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label>Periodo</label>
                                                    <select name="periodo" class="form-control" autofocus>
                                                        <?php foreach($periodos as $periodo){ ?>
                                                        <option value="<?= $periodo['periodo']; ?>" <?= ($periodo['periodo'] == $etapa['periodo']) ? 'selected' : ''; ?>><?= $periodo['periodo']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-8 form-group">
                                                    <label>Nombre</label>
                                                    <input type="text" name="nombre" class="form-control" maxlength="255" value="<?= $etapa['nombre']; ?>" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Permisos Formulario</label>
                                                <div class="clearfix"></div>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="puedeevaluar" value="1" <?= ($etapa['puedeevaluar']) ? 'checked' : ''; ?> /> Evaluarse
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="puedeapelar" value="1" <?= ($etapa['puedeapelar']) ? 'checked' : ''; ?> /> Apelarse
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Descripción</label>
												<textarea class="form-control" name="descripcion" rows="5"><?= $etapa['descripcion']; ?></textarea>
								            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Fecha límite</label>
                                                        <input type="text" name="limite" class="form-control" value="<?= $etapa['limite']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
												    <a href="<?= site_url('admin/etapas'); ?>" class="btn btn-default btn-block btn-cancelar"><i class="fa fa-reply"></i> Cancelar</a>
												</div>
                                                <p class="visible-xs"></p>
												<div class="col-sm-6">
                                                    <input type="hidden" name="id" value="<?= $etapa['id']; ?>" />
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
                    $('input[name=limite]').datepicker().keypress(function(e){ return false });
                    $('input[name=limite]').change(function(e){
                        
                        if($(e.currentTarget).val() != '')
                        {
                            var periodo = $('select[name=periodo]').val();
                            var limite = $(e.currentTarget).val();
                            var id =  $('input[name=id]').val();
                            
                            $.ajax({
                                url: '<?= site_url('api/etapa/verificar'); ?>/' + periodo + '/' + limite,
                                statusCode : {
                                    200 : function(data)
                                    {
                                        if(data.id != id)
                                        {
                                            alert('Ya se encuentra registrada la fecha límite ingresada para este periodo. Ingrese otro valor.');
                                            $(e.currentTarget).val('').focus();
                                        }
                                    }
                                }
                            });
                        }
                    });
                });
            </script>
