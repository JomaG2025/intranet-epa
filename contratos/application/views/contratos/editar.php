			<div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Contratos</h4>
			</div>
        	<div class="panel panel-default no-border">
				<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Contratos</div>
				<div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12">
							 <form method="post" action="<?= site_url('contratos/actualizar'); ?>" enctype="multipart/form-data">
							     <fieldset>
							    	
								    <legend>Editar Contrato</legend>
                                    <div class="row">
                                        <div class="col-lg-7 col-lg-offset-1 col-md-12">
                                            <div class="form-horizontal">
                                                
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">ID:</label>
                                                    <div class="col-md-6">
                                                        <strong><?= formato_monto($contrato['id']); ?></strong>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">Creado:</label>
                                                    <div class="col-md-6">
                                                        <strong><?= formato_fecha_hora($contrato['creado']); ?></strong> <span class="text-muted"><?= formato_fecha_atras($contrato['creado']); ?></span>
                                                    </div>
                                                </div>
                                                
                                                <?php if($contrato['modificado']){ ?>
                                                
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">Modificado:</label>
                                                    <div class="col-md-6">
                                                        <strong><?= formato_fecha_hora($contrato['modificado']); ?></strong> <span class="text-muted"><?= formato_fecha_atras($contrato['modificado']); ?></span>
                                                    </div>
                                                </div>
                                                
                                                <?php } ?>
                                                
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right control-label">Estado:</label>
                                                    <div class="col-md-4">
                                                        <select name="estado" class="form-control" autocomplete="off" disabled>
                                                        
                                                            <?php foreach($estados as $estado){ ?>
                                                            
                                                                <option value="<?= $estado['abrev']; ?>" <?= ($contrato['estado'] == $estado['abrev']) ? 'selected="selected"' : ''; ?>><?= $estado['nombre']; ?></option>
                                                            
                                                            <?php } ?>
                                                        
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                        
                                                    <label class="col-md-2 text-right control-label">Identificación</label>
                                                    <div class="col-md-10">
                                                        <input type="text" maxlength="255" value="<?= $contrato['identificacion']; ?>" name="identificacion" placeholder="Identificación y Nº del acto administrativo" class="form-control" />
                                                    </div>
                                                        
                                                </div>
                                                
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Fecha doc.</label>
                                                    <div class="col-md-4">
                                                        <input type="text" value="<?= $contrato['fecha']; ?>" class="form-control" name="fecha" required />
                                                    </div>
                                                        
                                                </div>
							
                                                <div class="form-group">
                                                        
                                                    <label class="col-md-2 text-right control-label">RUT</label>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-9 col-xs-10 pr-5">
                                                                <input type="text" autocomplete="off" class="form-control" name="rut" id="rut" maxlength="10" pattern="^[0-9.]{7,10}" value="<?= $proveedor['rut']; ?>" required />
                                                            </div>
                                                            <div class="col-md-3 col-xs-2 pl-5">
                                                                <input type="text" class="form-control" name="dv" maxlength="1" id="dv" pattern="^[0-9kK]" value="<?= $proveedor['dv']; ?>" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">
                                                        
                                                    <label class="col-md-2 text-right control-label">Razón Social</label>
                                                    <div class="col-md-10">
                                                        <input type="text" maxlength="255" name="razon" class="form-control" value="<?= $proveedor['razon']; ?>" required />
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Fecha de Inicio</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="inicio" value="<?= $contrato['inicio']; ?>" required />
                                                    </div>
                                                        
                                                    <label class="col-md-2 text-right control-label">Término</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="termino" value="<?= $contrato['termino']; ?>" />
                                                    </div>
                                                        
                                                </div>
                                                
                                                <div class="form-group">
                                                
                                                    <label class="col-md-2 col-md-offset-6 text-right control-label">Días alerta</label>
                                                    <div class="col-md-4">
                                                        <select name="diasalerta" class="form-control" autocomplete="off">
                                                            <option value="10" <?= ($contrato['diasalerta'] == 10) ? 'selected' : ''; ?>>10 días</option>
                                                            <option value="15" <?= ($contrato['diasalerta'] == 15) ? 'selected' : ''; ?>>15 días</option>
                                                            <option value="20" <?= ($contrato['diasalerta'] == 20) ? 'selected' : ''; ?>>20 días</option>
                                                            <option value="30" <?= ($contrato['diasalerta'] == 30) ? 'selected' : ''; ?>>30 días</option>
                                                            <option value="45" <?= ($contrato['diasalerta'] == 45) ? 'selected' : ''; ?>>45 días</option>
                                                            <option value="60" <?= ($contrato['diasalerta'] == 60) ? 'selected' : ''; ?>>60 días</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Tipo de plazo</label>
                                                    <div class="col-md-4">
                                                        <select name="tipoplazo" class="form-control" autocomplete="off">
                                                            
                                                            <?php foreach($tipoplazos as $tipoplazo){ ?>
                                                                
                                                            <option value="<?= $tipoplazo['abrev']; ?>" <?= ($contrato['tipoplazo'] == $tipoplazo['abrev']) ? 'selected' : ''; ?>><?= $tipoplazo['nombre']; ?></option>
                                                                
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                        
                                                    <label class="col-md-2 text-right control-label">Tipo de pago</label>
                                                    <div class="col-md-4">
                                                        <select name="tipopago" class="form-control" autocomplete="off">
                                                            
                                                            <?php foreach($tipopagos as $tipopago){ ?>
                                                                
                                                            <option value="<?= $tipopago['abrev']; ?>" <?= ($contrato['tipopago'] == $tipopago['abrev']) ? 'selected' : ''; ?>><?= $tipopago['nombre']; ?></option>
                                                                
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right control-label">Glosa</label>
                                                    <div class="col-md-10">
                                                        <textarea name="glosa" class="form-control" rows="3" required><?= $contrato['glosa']; ?></textarea>
                                                    </div>
                                                        
                                                </div>
                                                    
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right">Adjuntos</label>
                                                    <div class="col-md-10">

                                                        <?php foreach($archivos as $key => $archivo){ ?>
                                                        <p><a href="<?= site_url('archivo/descargar/' . $archivo['id']);?>">contrato-tercero-epa-<?= $contrato['fecha']; ?><?= $archivo['file_ext']; ?></a> <?php if(($contrato['estado'] != 'DEV') AND ($contrato['estado'] != 'COB')){?><a href="<?= site_url('archivo/eliminar/' . $archivo['id']); ?>" class="btn btn-xs btn-danger btn-eliminar"><i class="fa fa-times"></i> Eliminar</a><?php } ?></p>
                                                        <?php } ?>                                                                
                                                        
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

                                                                        <option value="<?= $moneda['cod']; ?>" <?= ($moneda['cod'] == $contrato['moneda']) ? 'selected="selected"' : ''; ?>><?= $moneda['nombre']; ?></option>

                                                                        <?php } ?>
                                                                            
                                                                    </select>
                                                                </div>
                                                                    
                                                                <p class="visible-xs"></p>
                                                                    
                                                                <div class="col-sm-6">
                                                                    <input type="number" name="monto" class="form-control" min="0" step="any" value="<?= ($contrato['moneda'] == 'CLP') ? intval($contrato['monto']) : $contrato['monto']; ?>" required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    
                                                    <label class="col-md-2 text-right control-label">Contraparte técnica</label>
                                                    <div class="col-md-10">
                                                            
                                                        <div class="well">
                                                            <?php $contrapartes = array_column($contrapartes, 'usuario'); ?>
                                                            <?php foreach($usuarios as $item){ ?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="contrapartes[]" value="<?= $item['usuario']; ?>" <?= (in_array($item['usuario'], $contrapartes)) ? 'checked' : ''; ?> />
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
                                                    <input type="hidden" name="id" value="<?= $contrato['id']; ?>" />
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

            <?php if($contrato['termino']): ?>
            <div class="panel panel-default" style="margin-top:15px;">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-history"></i> Prórrogas
                        <button type="button" class="btn btn-xs btn-primary pull-right" id="btn-nueva-prorroga"><i class="fa fa-plus"></i> Registrar prórroga</button>
                    </h4>
                </div>
                <div class="panel-body" style="padding-bottom:8px;">
                    <?php if($prorrogas): ?>
                    <table class="table table-condensed table-bordered table-hover" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:155px;">Registrado</th>
                                <th class="text-center" style="width:130px;">Término anterior</th>
                                <th class="text-center" style="width:130px;">Término nuevo</th>
                                <th>Motivo</th>
                                <th class="text-center" style="width:90px;">Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($prorrogas as $p): ?>
                            <tr>
                                <td class="text-center"><small><?= $p['creado']; ?></small></td>
                                <td class="text-center text-muted"><?= $p['termino_anterior']; ?></td>
                                <td class="text-center"><strong><?= $p['termino_nuevo']; ?></strong></td>
                                <td><?= htmlspecialchars($p['motivo'] ? $p['motivo'] : ''); ?></td>
                                <td class="text-center"><small><?= $p['usuario']; ?></small></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p class="text-muted" style="margin:0;">Sin prórrogas registradas.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="panel panel-default" style="margin-top:15px;">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-flag-o"></i> Hitos y Estados de Pago</h4>
                </div>
                <div class="panel-body">
                    <?php if($hitos): ?>
                    <table class="table table-condensed table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th class="text-center" style="width:130px;">Fecha compromiso</th>
                                <th class="text-right" style="width:120px;">Monto</th>
                                <th class="text-center" style="width:115px;">Estado</th>
                                <th class="text-center" style="width:130px;">Cumplimiento</th>
                                <th class="text-center" style="width:175px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $h_labels = array('PEN'=>'Pendiente','REV'=>'En revisión','PAG'=>'Pagado','REC'=>'Rechazado');
                            $h_clases = array('PEN'=>'default',  'REV'=>'warning',    'PAG'=>'success','REC'=>'danger');
                            foreach($hitos as $h):
                            ?>
                            <tr id="fila-hito-<?= $h['id']; ?>">
                                <td>
                                    <?= htmlspecialchars($h['descripcion']); ?>
                                    <?php if($h['observacion']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($h['observacion']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $h['fecha_comprometida'] ? $h['fecha_comprometida'] : '—'; ?></td>
                                <td class="text-right"><?= ($h['monto'] !== NULL) ? '$ '.number_format($h['monto'], 0, ',', '.') : '—'; ?></td>
                                <td class="text-center">
                                    <span class="label label-<?= $h_clases[$h['estado']]; ?>" id="estado-label-<?= $h['id']; ?>">
                                        <?= $h_labels[$h['estado']]; ?>
                                    </span>
                                </td>
                                <td class="text-center" id="fecha-cumplimiento-<?= $h['id']; ?>">
                                    <?= $h['fecha_cumplimiento'] ? $h['fecha_cumplimiento'] : '—'; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-xs" style="margin-right:3px;">
                                        <button class="btn btn-default btn-estado-hito" data-id="<?= $h['id']; ?>" data-estado="PEN" title="Pendiente"><i class="fa fa-clock-o"></i></button>
                                        <button class="btn btn-warning btn-estado-hito" data-id="<?= $h['id']; ?>" data-estado="REV" title="En revisión"><i class="fa fa-refresh"></i></button>
                                        <button class="btn btn-success btn-estado-hito" data-id="<?= $h['id']; ?>" data-estado="PAG" title="Pagado"><i class="fa fa-check"></i></button>
                                        <button class="btn btn-danger  btn-estado-hito" data-id="<?= $h['id']; ?>" data-estado="REC" title="Rechazado"><i class="fa fa-ban"></i></button>
                                    </div>
                                    <button class="btn btn-xs btn-default btn-editar-hito"
                                        data-id="<?= $h['id']; ?>"
                                        data-descripcion="<?= htmlspecialchars($h['descripcion'], ENT_QUOTES); ?>"
                                        data-fecha="<?= $h['fecha_comprometida']; ?>"
                                        data-monto="<?= $h['monto']; ?>"
                                        data-observacion="<?= htmlspecialchars(($h['observacion'] ? $h['observacion'] : ''), ENT_QUOTES); ?>"
                                        title="Editar"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-danger btn-eliminar-hito" data-id="<?= $h['id']; ?>" title="Eliminar"><i class="fa fa-trash-o"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p class="text-muted">No hay hitos registrados para este contrato.</p>
                    <?php endif; ?>
                    <?php
                    $th_suma = 0;
                    foreach($hitos as $h) if($h['monto'] !== NULL) $th_suma += floatval($h['monto']);
                    $th_contrato = floatval($contrato['monto']);
                    if ($th_contrato > 0):
                        $th_disp = $th_contrato - $th_suma;
                        $th_pct  = min(100, $th_contrato > 0 ? round($th_suma / $th_contrato * 100) : 0);
                    ?>
                    <div style="margin:10px 0 8px;padding:8px 10px;background:#f9f9f9;border-radius:4px;border:1px solid #eee;">
                        <div class="row" style="margin-bottom:5px;">
                            <div class="col-xs-4">
                                <small class="text-muted">Monto contrato</small><br>
                                <strong><?= $contrato['moneda']; ?> <?= number_format($th_contrato, 0, ',', '.'); ?></strong>
                            </div>
                            <div class="col-xs-4">
                                <small class="text-muted">Comprometido en hitos</small><br>
                                <strong <?= $th_suma > $th_contrato ? 'class="text-danger"' : ''; ?>><?= $contrato['moneda']; ?> <?= number_format($th_suma, 0, ',', '.'); ?></strong>
                            </div>
                            <div class="col-xs-4">
                                <small class="text-muted">Disponible</small><br>
                                <strong class="<?= $th_disp < 0 ? 'text-danger' : 'text-success'; ?>"><?= $contrato['moneda']; ?> <?= number_format(abs($th_disp), 0, ',', '.'); ?><?= $th_disp < 0 ? ' <small>(excedido)</small>' : ''; ?></strong>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;margin:0;">
                            <div class="progress-bar <?= $th_pct >= 100 ? 'progress-bar-danger' : ($th_pct >= 80 ? 'progress-bar-warning' : 'progress-bar-success'); ?>" style="width:<?= $th_pct; ?>%;min-width:0;"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-nuevo-hito"><i class="fa fa-plus"></i> Agregar hito</button>
                </div>
            </div>

            <div class="panel panel-default" style="margin-top:15px;">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-clock-o"></i> Historial</h4>
                </div>
                <div class="panel-body" style="padding:15px 20px 10px;">
                <?php if(empty($historial)): ?>
                    <p class="text-muted" style="margin:0;">Sin eventos registrados.</p>
                <?php else:
                    $estado_m = array(
                        'ING' => array('#5cb85c', 'Ingresado'),
                        'ALE' => array('#f0ad4e', 'Alertado'),
                        'VEN' => array('#d9534f', 'Vencido'),
                    );
                    $orig_m = array(
                        'manual'       => 'Manual',
                        'notificacion' => 'Automático',
                        'prorroga'     => 'Por prórroga',
                    );
                    $ev_r_map = array(
                        'NEW:contrato' => array('#5cb85c', 'Contrato creado'),
                        'UPD:contrato' => array('#5bc0de', 'Contrato modificado'),
                        'PRR:contrato' => array('#9b59b6', 'Prórroga registrada'),
                        'DEL:contrato' => array('#d9534f', 'Contrato eliminado'),
                    );
                ?>
                    <ul style="list-style:none;padding:0;margin:0;border-left:2px solid #e5e5e5;margin-left:8px;">
                    <?php foreach($historial as $item):
                        if ($item['tipo'] === 'estado'):
                            $e       = $item['data'];
                            $color   = isset($estado_m[$e['estado_nuevo']]) ? $estado_m[$e['estado_nuevo']][0] : '#aaa';
                            $nom_nvo = isset($estado_m[$e['estado_nuevo']]) ? $estado_m[$e['estado_nuevo']][1] : $e['estado_nuevo'];
                            $nom_ant = ($e['estado_anterior'] && isset($estado_m[$e['estado_anterior']])) ? $estado_m[$e['estado_anterior']][1] : $e['estado_anterior'];
                            $origen  = isset($orig_m[$e['origen']]) ? $orig_m[$e['origen']] : $e['origen'];
                    ?>
                        <li style="padding:6px 0 10px 24px;position:relative;">
                            <span style="position:absolute;left:-8px;top:8px;width:14px;height:14px;border-radius:50%;background:<?= $color; ?>;display:inline-block;border:2px solid #fff;box-shadow:0 0 0 2px <?= $color; ?>;"></span>
                            <small class="text-muted"><?= $e['creado']; ?></small><br>
                            <strong>Estado → <?= $nom_nvo; ?></strong><?php if($nom_ant): ?> <span class="text-muted" style="font-size:12px;">(antes: <?= $nom_ant; ?>)</span><?php endif; ?><br>
                            <small class="text-muted"><?= $origen; ?><?= $e['usuario'] ? ' — ' . $e['usuario'] : ''; ?></small>
                        </li>
                    <?php elseif($item['tipo'] === 'audit'):
                            $e     = $item['data'];
                            $key   = $e['evento'] . ':' . $e['recurso'];
                            $color = isset($ev_r_map[$key]) ? $ev_r_map[$key][0] : '#aaa';
                            $desc  = isset($ev_r_map[$key]) ? $ev_r_map[$key][1] : (isset($e['evento_nombre']) ? $e['evento_nombre'] : $e['evento']);
                    ?>
                        <li style="padding:6px 0 10px 24px;position:relative;">
                            <span style="position:absolute;left:-8px;top:8px;width:14px;height:14px;border-radius:50%;background:<?= $color; ?>;display:inline-block;border:2px solid #fff;box-shadow:0 0 0 2px <?= $color; ?>;"></span>
                            <small class="text-muted"><?= $e['fecha']; ?></small><br>
                            <strong><?= $desc; ?></strong><?php if($e['usuario']): ?> — <small class="text-muted"><?= $e['usuario']; ?></small><?php endif; ?>
                        </li>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                </div>
            </div>

            <div class="modal fade" id="modal-hito" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Hito</h4>
                        </div>
                        <div class="modal-body">
                            <form id="form-hito">
                                <input type="hidden" name="id" id="hito-id" value="">
                                <input type="hidden" name="contrato" value="<?= $contrato['id']; ?>">
                                <div class="form-group">
                                    <label>Descripción <span class="text-danger">*</span></label>
                                    <input type="text" name="descripcion" id="hito-descripcion" class="form-control" maxlength="255">
                                </div>
                                <div class="form-group">
                                    <label>Fecha comprometida</label>
                                    <input type="text" name="fecha_comprometida" id="hito-fecha" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Monto</label>
                                    <input type="number" name="monto" id="hito-monto" class="form-control" min="0" step="1" placeholder="Opcional">
                                    <small id="hito-monto-hint" class="help-block" style="display:none;margin-bottom:0;"></small>
                                </div>
                                <div class="form-group">
                                    <label>Observación / Mecanismo de verificación</label>
                                    <textarea name="observacion" id="hito-observacion" class="form-control" rows="3" placeholder="Ej: Informe de avance, acta de recepción, factura..."></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-success" id="btn-guardar-hito"><i class="fa fa-check"></i> Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($contrato['termino']): ?>
            <div class="modal fade" id="modal-prorroga" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-history"></i> Registrar prórroga</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Término actual</label>
                                <p class="form-control-static"><strong><?= $contrato['termino']; ?></strong></p>
                            </div>
                            <div class="form-group">
                                <label>Nueva fecha de término <span class="text-danger">*</span></label>
                                <input type="text" id="prorroga-termino" class="form-control" placeholder="Nueva fecha de término">
                                <span class="help-block text-danger" id="prorroga-error" style="display:none;"></span>
                            </div>
                            <div class="form-group">
                                <label>Motivo <span class="text-muted">(opcional)</span></label>
                                <textarea id="prorroga-motivo" class="form-control" rows="3" placeholder="Razón de la prórroga, resolución que la autoriza, etc."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-success" id="btn-guardar-prorroga"><i class="fa fa-check"></i> Registrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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

                    // Hitos
                    var _mCont  = <?= floatval($contrato['monto']); ?>;
                    var _mBase  = <?php $tb=0; foreach($hitos as $h) if($h['monto']!==NULL) $tb+=floatval($h['monto']); echo $tb; ?>;
                    var _mEdit  = 0;

                    function _hitoActualizarDisponible() {
                        if (_mCont <= 0) { $('#hito-monto-hint').hide(); return; }
                        var monto     = parseFloat($('#hito-monto').val()) || 0;
                        var disponible = _mCont - (_mBase - _mEdit + monto);
                        var fmt = function(n){ return Math.round(Math.abs(n)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); };
                        var txt = (disponible < 0 ? 'Excede en <?= $contrato['moneda']; ?> ' : 'Disponible <?= $contrato['moneda']; ?> ') + fmt(disponible);
                        $('#hito-monto-hint').text(txt)
                            .toggleClass('text-danger', disponible < 0)
                            .toggleClass('text-muted',  disponible >= 0)
                            .show();
                    }

                    $('#hito-monto').on('input', _hitoActualizarDisponible);

                    $('#hito-fecha').datepicker();

                    $('#btn-nuevo-hito').on('click', function(){
                        $('#form-hito')[0].reset();
                        $('#hito-id').val('');
                        $('#modal-hito .modal-title').text('Nuevo hito');
                        _mEdit = 0;
                        _hitoActualizarDisponible();
                        $('#modal-hito').modal('show');
                    });

                    $('body').on('click', '.btn-editar-hito', function(){
                        var b = $(this);
                        $('#hito-id').val(b.data('id'));
                        $('#hito-descripcion').val(b.data('descripcion'));
                        $('#hito-fecha').val(b.data('fecha'));
                        $('#hito-monto').val(b.data('monto'));
                        $('#hito-observacion').val(b.data('observacion'));
                        $('#modal-hito .modal-title').text('Editar hito');
                        _mEdit = parseFloat(b.data('monto')) || 0;
                        _hitoActualizarDisponible();
                        $('#modal-hito').modal('show');
                    });

                    $('#btn-guardar-hito').on('click', function(){
                        if (!$.trim($('#hito-descripcion').val())) {
                            alert('La descripción es obligatoria.');
                            $('#hito-descripcion').focus();
                            return;
                        }
                        if (_mCont > 0) {
                            var monto     = parseFloat($('#hito-monto').val()) || 0;
                            var disponible = _mCont - (_mBase - _mEdit);
                            var fmt = function(n){ return Math.round(Math.abs(n)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); };
                            if (monto > 0 && monto > disponible) {
                                alert('El monto ($' + fmt(monto) + ') supera el disponible ($' + fmt(disponible) + ').\nReduzca el monto o ajuste otros hitos.');
                                return;
                            }
                        }
                        $.post('<?= site_url('hito/guardar'); ?>', $('#form-hito').serialize())
                            .done(function(resp){
                                if (resp.ok) { $('#modal-hito').modal('hide'); location.reload(); }
                                else { alert(resp.error || 'Error al guardar'); }
                            })
                            .fail(function(){ alert('Error de conexión al guardar hito'); });
                    });

                    $('body').on('click', '.btn-eliminar-hito', function(){
                        if (!confirm('¿Eliminar este hito?')) return;
                        var id = $(this).data('id');
                        $.post('<?= site_url('hito/eliminar'); ?>/' + id)
                            .done(function(resp){
                                if (resp.ok) { $('#fila-hito-' + id).fadeOut(300, function(){ $(this).remove(); }); }
                                else { alert(resp.error || 'Error al eliminar'); }
                            })
                            .fail(function(){ alert('Error de conexión al eliminar hito'); });
                    });

                    $('body').on('click', '.btn-estado-hito', function(){
                        var id     = $(this).data('id');
                        var estado = $(this).data('estado');
                        var labels  = {PEN:'Pendiente', REV:'En revisión', PAG:'Pagado', REC:'Rechazado'};
                        var classes = {PEN:'default',   REV:'warning',     PAG:'success', REC:'danger'};

                        $.post('<?= site_url('hito/estado'); ?>/' + id + '/' + estado)
                            .done(function(resp){
                                if (resp.ok) {
                                    var lbl = $('#estado-label-' + id);
                                    lbl.removeClass('label-default label-warning label-success label-danger')
                                       .addClass('label-' + classes[estado])
                                       .text(labels[estado]);
                                    if (resp.fecha_cumplimiento) {
                                        $('#fecha-cumplimiento-' + id).text(resp.fecha_cumplimiento);
                                    }
                                } else {
                                    alert(resp.error || 'Error al cambiar estado');
                                }
                            })
                            .fail(function(){ alert('Error de conexión al cambiar estado'); });
                    });

                    // Prórroga
                    <?php if($contrato['termino']): ?>
                    $('#prorroga-termino').datepicker();

                    $('#modal-prorroga').on('show.bs.modal', function(){
                        $('#prorroga-termino').val('');
                        $('#prorroga-motivo').val('');
                        $('#prorroga-error').hide().text('');
                        $('#btn-guardar-prorroga').prop('disabled', false);
                    });

                    $('#btn-nueva-prorroga').on('click', function(){
                        $('#modal-prorroga').modal('show');
                    });

                    $('#btn-guardar-prorroga').on('click', function(){
                        var termino = $.trim($('#prorroga-termino').val());
                        if (!termino) {
                            $('#prorroga-error').text('Ingrese la nueva fecha de término.').show();
                            return;
                        }
                        $('#prorroga-error').hide();
                        var btn = $(this).prop('disabled', true);
                        $.post('<?= site_url('prorroga/guardar'); ?>', {
                            contrato: <?= $contrato['id']; ?>,
                            termino_nuevo: termino,
                            motivo: $('#prorroga-motivo').val()
                        })
                        .done(function(resp){
                            if (resp.ok) {
                                $('#modal-prorroga').modal('hide');
                                location.reload();
                            } else {
                                $('#prorroga-error').text(resp.error || 'Error al registrar.').show();
                                btn.prop('disabled', false);
                            }
                        })
                        .fail(function(){
                            $('#prorroga-error').text('Error de conexión.').show();
                            btn.prop('disabled', false);
                        });
                    });
                    <?php endif; ?>

                });
            </script>