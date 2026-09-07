			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Garantías <?= $this->uri->segment(2); ?></h4>
			</div>
            <div class="navbar-action hidden-xs">
			    <a href="<?= site_url('garantias/nueva'); ?>" class="btn btn-info" title="Nuevo"><i class="fa fa-plus"></i> Nueva Garantía</a>
			</div>		
            <div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Garantías <?= $this->uri->segment(2); ?><a href="<?= site_url('garantias/nueva'); ?>" class="btn btn-info btn-xs pull-right" title="Nueva"><i class="fa fa-plus"></i> Nueva Garantía</a></div>
				<div class="panel-body">
			        
			        <div class="row">
			        
			        	<div class="col-lg-12">
				        
					        <div class="table-responsive">
									
								<table class="table table-hover table-condensed table-striped">
										
									<thead>
										<tr>
											<th class="text-center">id</th>
											<th class="text-center">Estado</th>
											<th class="text-center">Rut</th>
                                            <th>Razón Social</th>
											<th class="text-center">Inicio</th>
											<th class="text-center">Término</th>
											<th class="text-center">Monto</th>
											<th></th>
                                            <?php if($this->session->userdata('privilegio') == 'ADM'){ ?>
											<th></th>	
                                            <?php } ?>
										</tr>
									</thead>
									<tbody>
									
									<?php foreach($garantias as $garantia){ ?>
									
										<tr <?php if($garantia['estado'] == 'ALE'){ ?>class="warning"<?php }else if($garantia['estado'] == 'VEN'){ ?>class="danger"<?php }else if(($garantia['estado'] == 'DEV') OR ($garantia['estado'] == 'COB')){ ?>class="success"<?php } ?>>
											<td class="text-center" style="width: 5%;"><?= $garantia['id']; ?></td>
											<td class="text-center"><?php if(($garantia['estado'] == 'ALE') OR ($garantia['estado'] == 'VEN')){ ?><i class="fa fa-warning"></i> <?php }else if(($garantia['estado'] == 'DEV') OR ($garantia['estado'] == 'COB')){ ?><i class="fa fa-check-circle"></i> <?php } ?><?= $garantia['estado_nombre']; ?></td>
                                            <td class="text-center"><?= formato_monto($garantia['proveedor']); ?>-<?= $garantia['proveedor_dv']; ?></td>
                                            <td><?= $garantia['proveedor_razon']; ?></td>
                                            <td class="text-center"><?= $garantia['inicio']; ?></td>
                                            <td class="text-center"><?= $garantia['termino']; ?></td>
                                            <td class="text-center"><?= $garantia['moneda']; ?> <?= $garantia['moneda_simbolo']?><?= ($garantia['moneda'] != 'CLP') ? formato_monto_decimal($garantia['monto']) : formato_monto($garantia['monto']); ?></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('garantias/editar/'. $garantia['id']); ?>" class="btn btn-sm btn-block btn-default" title=""><i class="fa fa-edit"></i> Ver</a></td>
                                            <?php if($this->session->userdata('privilegio') == 'ADM'){ ?>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('garantias/eliminar/'. $garantia['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar" title=""><i class="fa fa-trash-o"></i> Eliminar</a></td>
                                            <?php } ?>
									
										</tr>
									<?php } ?>
									
									</tbody>
									
								</table>
								
								<script>
								    $(function(){
								        $('.table').dataTable({
                                            order: [[ 0, 'desc' ]],
                                            bStateSave : true,
                                            fnStateSave :function(settings,data){
                                                localStorage.setItem("dataTables_state", JSON.stringify(data));
								            },
                                            fnStateLoad: function(settings) {
                                                return JSON.parse(localStorage.getItem("dataTables_state"));
                                            },
                                            'aoColumnDefs': [
                                            <?php if($this->session->userdata('privilegio') == 'ADM'){ ?>
                                                { "bSortable": false, "aTargets": [ 7, 8 ] },
                                                { "bSearchable": false, "aTargets": [ 0, 7, 8 ] }
                                            <?php }else{ ?>
                                                { "bSortable": false, "aTargets": [ 7 ] },
                                                { "bSearchable": false, "aTargets": [ 0, 7 ] } 
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