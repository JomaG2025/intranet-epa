			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Relacionados</h4>
			</div>
			
			<?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
			<div class="navbar-action hidden-xs">
			    <a href="<?= site_url('relacionados/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Relacionado</a>
			</div>
			<?php } ?>       		
        		
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-random"></i> Relacionados<?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?><a href="<?= site_url('relacionados/nuevo'); ?>" class="btn btn-info btn-xs pull-right" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Relacionado</a><?php } ?></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th class="text-center">#</th>
						    				<th class="text-center">rut</th>
                                            <th class="text-center">nombre</th>
						    				<th class="text-center">tipo</th>
						    				<th class="text-center">observación</th>
						    				<th class="text-center">relación</th>
						    				<th class="text-center"></th>
						    				<th class="text-center"></th>
						    				<th class="text-center"></th>
										</tr>
											
									</thead>
									<tbody class="text-center">
									
										<?php foreach($relacionados as $key => $relacionado){ ?>
						    			
						    				<tr>
						    					<td><?= $key+1; ?></td>
						    					<td class="text-upper"><?= formato_monto($relacionado['rut']); ?><?= ($relacionado['dv'] != '') ? '-'. $relacionado['dv'] : ''; ?></td>
						    					<td><?= $relacionado['nombre']; ?></td>
						    					<td><?= $relacionado['tipo']; ?></td>
						    					<td><em><?= $relacionado['observacion']; ?></em></td>
						    					<td><?= $relacionado['relacion']; ?></td>
                                                <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
                                                <td>
                                                    <a href="<?= site_url('relacionados/activo/'. $relacionado['id']); ?>" class="btn btn-sm btn-block btn-<?= ($relacionado['activo'] == 1) ? 'success' : 'danger'; ?>"><?= ($relacionado['activo'] == 1) ? '<i class="fa fa-check"></i> Activo' : '<i class="fa fa-times"></i> Inactivo'; ?></a>
                                                </td>
						    					<td>
							    					<a href="<?= site_url('relacionados/editar/'. $relacionado['id']); ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a>
						    					</td>
						    					<td>
                                                    <a href="<?= site_url('relacionados/eliminar/'. $relacionado['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a>
							    	            </td>
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
                                              <?php if(($this->session->userdata('privilegio') == 'ADM') OR ($this->session->userdata('privilegio') == 'EDT')){ ?>
											 	{ "bSortable": false, "aTargets": [ 7, 8 ] },
											 	{ "bSearchable": false, "aTargets": [ 0, 6, 7, 8] }
                                              <?php }else{ ?>
											 	{ "bSearchable": false, "aTargets": [ 0 ] }
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