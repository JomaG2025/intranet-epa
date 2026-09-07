			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Informes de prensa</h4>
			</div>

            <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'COM')){ ?>
            <div class="navbar-action hidden-xs">
			    <a href="#modal-nuevoinforme" data-toggle="modal" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Informe</a>
			</div>
            <?php } ?>

        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-file-o"></i> Informes de prensa<?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'COM')){ ?><a href="#modal-nuevoinforme" data-toggle="modal" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Informe</a><?php } ?></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th class="text-center">id</th>
											<th class="text-center">Archivo</th>
                                            
                                            <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'COM')){ ?>
											<th class="text-center">Creado</th>
                                            <?php } ?>
											
                                            <th class="text-center">Fecha</th>
											<th class="text-center"></th>
                                            
                                            <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'COM')){ ?>
											<th class="text-center"></th>
                                            <?php } ?>
										</tr>
											
									</thead>
									<tbody class="text-center">
									
									<?php foreach($informesprensa as $informeprensa){ ?>
									
										<tr>
											<td style="width: 5%;"><?= $informeprensa['id']; ?></td>
                                            <td><a href="<?= site_url('informesprensa/descargar/'. $informeprensa['id']); ?>"><?= $informeprensa['file_name']; ?></a></td>
                                            
                                            <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'COM')){ ?>
                                            <td><?= $informeprensa['creado']; ?></td>
                                            <?php } ?>
                                            
                                            <td><?= $informeprensa['fecha']; ?></td>
											<td><a href="<?= site_url('informesprensa/descargar/'. $informeprensa['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-download"></i> Descargar</a></td>
											
                                            <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'COM')){ ?>
                                            <td><a href="<?= site_url('informesprensa/eliminar/'. $informeprensa['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
                                            <?php } ?>
                                        </tr>
									<?php } ?>
									
									</tbody>
									
								</table>
								
								<script>
								    $(function(){
								        $('.table').dataTable({
									         bStateSave : true,      
										      fnStateSave :function(settings,data){
										        localStorage.setItem("dataTables_state", JSON.stringify(data));
										      },
										      fnStateLoad: function(settings) {
										        return JSON.parse(localStorage.getItem("dataTables_state"));
										      },
										     'aoColumnDefs': [
                                                <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'COM')){ ?>
											 	{ "bSortable": false, "aTargets": [ 4, 5 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 4, 5 ] }
                                                <?php }else{ ?>
                                                { "bSortable": false, "aTargets": [ 3 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 3 ] }
                                                <?php } ?>
											 ]
								        });
								    });
								</script>
									
							</div>

			        	</div>						
						
					</div>
			    </div>
			</div>
            
            <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'COM')){ ?>
            <div class="modal fade autofocus" id="modal-nuevoinforme" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Nuevo informe de prensa</h4>
                        </div>

                        <form action="<?= site_url('informesprensa/grabar') ?>" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="control-label">Archivo</label>
                                        <input type="file" class="form-control" name="archivo" required />
                                        <p class="text-muted">Sólo se pueden adjuntar archivos con extension pdf con un <strong>máximo</strong> de 10MB.</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Fecha del informe</label>
                                        <input type="text" class="form-control" name="fecha" required />
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default submit" data-dismiss="modal"><i class="fa fa-reply"></i> Cancelar</button>
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Subir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                $(function(){
                    $('input[name=fecha]').datepicker().keypress(function(e){return false;});
                    
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

                            if(files[i].size > 10240000)
                            {
                                $(e.currentTarget).replaceWith($(e.currentTarget).clone());
                                alert('El tamaño del archivo supera el permitido.');
                                return false;
                            }
                        }
                    });
                });
            </script>
            <? } ?>