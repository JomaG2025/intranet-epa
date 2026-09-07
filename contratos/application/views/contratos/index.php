			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Contratos <?= $this->uri->segment(2); ?></h4>
			</div>
            <div class="navbar-action hidden-xs">
			    <a href="<?= site_url('contratos/nuevo'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Contrato</a>
			</div>		
            <div class="panel panel-default" style="margin-bottom:6px;">
                <div class="panel-body" style="padding:10px 15px 4px;">
                    <form method="get" action="<?= site_url('contratos'); ?>" class="form-inline">
                        <div class="form-group" style="margin:0 4px 6px 0;">
                            <select name="estado" class="form-control input-sm">
                                <option value="">Todos los estados</option>
                                <?php foreach($estados as $e): ?>
                                <option value="<?= $e['abrev']; ?>" <?= ($filtros['estado'] === $e['abrev']) ? 'selected' : ''; ?>><?= $e['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0 4px 6px 0;">
                            <input type="text" name="proveedor" class="form-control input-sm" placeholder="Razón social" value="<?= htmlspecialchars($filtros['proveedor'] ? $filtros['proveedor'] : ''); ?>" style="width:190px;">
                        </div>
                        <div class="form-group" style="margin:0 4px 6px 0;">
                            <input type="text" name="termino_desde" class="form-control input-sm datepicker-filtro" placeholder="Término desde" value="<?= $filtros['termino_desde'] ? $filtros['termino_desde'] : ''; ?>" style="width:124px;">
                        </div>
                        <div class="form-group" style="margin:0 4px 6px 0;">
                            <input type="text" name="termino_hasta" class="form-control input-sm datepicker-filtro" placeholder="Término hasta" value="<?= $filtros['termino_hasta'] ? $filtros['termino_hasta'] : ''; ?>" style="width:124px;">
                        </div>
                        <div class="form-group" style="margin:0 4px 6px 0;">
                            <select name="responsable" class="form-control input-sm">
                                <option value="">Todos los responsables</option>
                                <?php foreach($responsables as $r): ?>
                                <option value="<?= $r['usuario']; ?>" <?= ($filtros['responsable'] === $r['usuario']) ? 'selected' : ''; ?>><?= $r['usuario']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" style="margin-bottom:6px;"><i class="fa fa-search"></i> Filtrar</button>
                        <?php if (!empty(array_filter($filtros))): ?>
                        <a href="<?= site_url('contratos'); ?>" class="btn btn-default btn-sm" style="margin-bottom:6px;"><i class="fa fa-times"></i> Limpiar</a>
                        <span class="text-muted" style="font-size:12px;margin-left:8px;"><?= count($contratos); ?> contrato(s)</span>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Contratos <?= $this->uri->segment(2); ?><a href="<?= site_url('contratos/nueva'); ?>" class="btn btn-info btn-xs pull-right" title="Nueva"><i class="fa fa-plus"></i> Nueva Garantía</a></div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
					        <div class="table-responsive">
								<table class="table table-hover table-condensed table-striped">
									<thead>
										<tr>
											<th class="text-center">id</th>
											<th class="text-center">Estado</th>
											<th class="text-center">Identificación</th>
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
									
									<?php foreach($contratos as $contrato){ ?>
										<tr <?php if($contrato['estado'] == 'ALE'){ ?>class="warning"<?php }else if($contrato['estado'] == 'VEN'){ ?>class="danger"<?php } ?>>
											<td class="text-center" style="width: 5%;"><?= $contrato['id']; ?></td>
											<td class="text-center"><?php if(($contrato['estado'] == 'ALE') OR ($contrato['estado'] == 'VEN')){ ?><i class="fa fa-warning"></i> <?php } ?><?= $contrato['estado_nombre']; ?></td>
                                            <td class="text-center"><?= $contrato['identificacion']; ?></td>
                                            <td class="text-center"><?= formato_monto($contrato['proveedor']); ?>-<?= $contrato['proveedor_dv']; ?></td>
                                            <td><?= $contrato['proveedor_razon']; ?></td>
                                            <td class="text-center"><?= $contrato['inicio']; ?></td>
                                            <td class="text-center"><?= $contrato['termino']; ?></td>
                                            <td class="text-center"><?= $contrato['moneda']; ?> <?= $contrato['moneda_simbolo']?><?= ($contrato['moneda'] != 'CLP') ? formato_monto_decimal($contrato['monto']) : formato_monto($contrato['monto']); ?></td>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('contratos/editar/'. $contrato['id']); ?>" class="btn btn-sm btn-block btn-default" title=""><i class="fa fa-edit"></i> Ver</a></td>
                                            <?php if($this->session->userdata('privilegio') == 'ADM'){ ?>
											<td style="text-align: center; width: 10%;"><a href="<?= site_url('contratos/eliminar/'. $contrato['id']); ?>" class="btn btn-sm btn-block btn-danger btn-eliminar" title=""><i class="fa fa-trash-o"></i> Eliminar</a></td>
                                            <?php } ?>
										</tr>
									<?php } ?>
									
									</tbody>
								</table>
								<script>
								    $(function(){
								        $('.table').dataTable({
                                            order: [[ 0, 'desc' ]],
                                            'aoColumnDefs': [
                                            <?php if($this->session->userdata('privilegio') == 'ADM'){ ?>
                                                { "bSortable": false, "aTargets": [ 8, 9 ] },
                                                { "bSearchable": false, "aTargets": [ 0, 8, 9 ] }
                                            <?php }else{ ?>
                                                { "bSortable": false, "aTargets": [ 8 ] },
                                                { "bSearchable": false, "aTargets": [ 0, 8 ] }
                                            <?php } ?>
                                            ]
								        });
								        $('.datepicker-filtro').datepicker();
								    });
								</script>
							</div>
			        	</div>						
					</div>
			    </div>
			</div>