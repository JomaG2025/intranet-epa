            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i>  Proceso Periodo <?= $periodo_actual['periodo']; ?> <i class="fa fa-angle-right"></i> Formulario</h4>
			</div>
            <div class="navbar-action hidden-xs">
			    <a href="<?= site_url(''); ?>" class="btn btn-default"><i class="fa fa-angle-left"></i> Volver al proceso actual</a>
			</div>	
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default no-border">
                        
                        <div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> <a href="<?= site_url(''); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-angle-left"></i> Volver</a></div>
                        
                        <div class="panel-body">

                            <form id="form-formulario" action="#" method="post" enctype="multipart/form-data">                                
                            
                                <div class="well text-center mb-40">
                                    <h2 class="text-primary mb-0 text-uppercase">FORMULARIO <?= $formulario['estadoformulario_nombre']; ?></h2>
                                    <?php if(($formulario['estadoformulario'] == 'APE') OR (($formulario['estadoformulario'] == 'EVA'))){ ?>
                                        <?php if($resultadoapelacion){ ?>
                                        <span class="lead">PUNTAJE APELACIÓN: <?= $resultadoapelacion['puntaje']; ?> PTS. (PUNTAJE EVALUACIÓN: <?= $resultado['puntaje']; ?> PTS.)</span>
                                        <?php }else{ ?>
                                        <span class="lead">PUNTAJE EVALUACIÓN: <?= $resultado['puntaje']; ?> PTS.</span>
                                        <?php } ?>
                                    <?php } ?>

                                    <p class="mt-10"><?= nl2br($formulario['estadoformulario_descripcion']); ?></p>
                                    
                                    <?php if(($formulario['estadoformulario'] == 'EVA') AND ($etapa_actual['puedeapelar'])){ ?>
                                    <a href="#modal-apelacion" class="btn btn-warning submit" data-toggle="modal"><i class="fa fa-warning"></i> Apelar evaluación</a>
                                    <?php } ?>
                                    
                                </div>
                                
                                <h2 class="text-primary text-uppercase">FORMULARIO EPPO <?= $formulario['periodo']; ?></h2>
                                <hr />
                                
                                <div class="clearfix mb-10"></div>
                                
                                <span class="pull-right"><i class="fa fa-asterisk text-danger"></i> Campos requeridos</span>
                                
                                <div class="clearfix mb-20"></div>

                                <fieldset>

                                    <div class="row">

                                        <div class="col-md-7">

                                            <div class="form-group row form-horizontal">

                                                <label class="control-label col-sm-2 text-right">Evaluado</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" maxlength="255" disabled value="<?= $formulario['nombre']; ?>" />
                                                </div>
                                                
                                            </div>

                                        </div>

                                        <div class="col-md-5">

                                            <div class="form-group row form-horizontal">

                                                <label class="control-label col-sm-2 col-md-3 text-right"><i class="fa fa-asterisk text-danger"></i> Nivel</label>
                                                <div class="col-sm-10 col-md-9">
                                                    <select name="tiponivel" class="form-control" required <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'disabled' : '';?>>
                                                        <option value="">Seleccione...</option>
                                                        <?php foreach($tiponiveles as $tiponivel){ ?>
                                                        <option value="<?= $tiponivel['abrev']; ?>" <?= ($formulario['tiponivel'] == $tiponivel['abrev']) ? 'selected' : ''; ?>><?= $tiponivel['nombre']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-7">

                                            <div class="form-group row form-horizontal">
                                                
                                                <?php if($evaluadores){ ?>
                                                <label class="control-label col-sm-2 text-right"><?= (count($evaluadores) == 1) ? 'Evaluador' : 'Evaluadores'; ?></label>
                                                <div class="col-sm-10">
                                                    <?php foreach($evaluadores as $evaluador){ ?>
                                                    <input type="text" class="form-control mb-15" maxlength="255" disabled value="<?= $evaluador['nombre']; ?>" tabindex="-1" />
                                                    <?php } ?>
                                                </div>
                                                <?php } ?>

                                            </div>

                                        </div>

                                        <div class="col-md-5">

                                            <div class="form-group row form-horizontal">

                                                <label class="control-label col-sm-2 col-md-3 text-right"><i class="fa fa-asterisk text-danger"></i> Contrato</label>
                                                <div class="col-sm-10 col-md-9">
                                                    <select name="tipocontrato" class="form-control" required <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'disabled' : '';?>>
                                                        <option value="">Seleccione...</option>
                                                        <?php foreach($tipocontratos as $tipocontrato){ ?>
                                                        <option value="<?= $tipocontrato['abrev']; ?>" <?= ($formulario['tipocontrato'] == $tipocontrato['abrev']) ? 'selected' : ''; ?>><?= $tipocontrato['nombre']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </fieldset>

                                <h3 class="mt-40">OBJETIVOS A EVALUAR</h3>
                                <p class="mb-20">1. Objetivos por Área o Departamento. </p>

                                <fieldset id="objetivoarea">
                                    <div class="table-responsive">
                                        <table id="table-objetivosarea" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 14%">PROYECTO/PROCESO</th>
                                                    <th style="width: 14%">DESCRIPCIÓN</th>
                                                    <th style="width: 14%">OBJETIVO</th>
                                                    <th style="width: 14%">META</th>
                                                    <th style="width: 14%">INDICADOR DE<br />GESTIÓN</th>
                                                    <th style="width: 14%">PLAN DE ACCIÓN<br />CONSENSUADO</th>
                                                    
                                                    <?php if(($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>
                                                    
                                                        <th style="width: 6%"><abbr title="RETROALIMENTACIÓN">RET</abbr></th>

                                                        <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>
                                                            <th style="width: 10%"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                                        <?php } ?>

                                                    <?php }else{ ?>

                                                        <th style="width: 16%"></th>
                                                    
                                                    <?php } ?>                               
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($objetivosarea as $key => $objetivoarea){ ?>
                                                
                                                    <tr <?= ($objetivoarea['retroalimentaciones']) ? 'class="warning"' : ''; ?>>
                                                        <td><textarea name="objetivosarea[<?= $key; ?>][proyecto]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoarea['proyecto']; ?></textarea></td>
                                                        <td><textarea name="objetivosarea[<?= $key; ?>][descripcion]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoarea['descripcion']; ?></textarea></td>
                                                        <td><textarea name="objetivosarea[<?= $key; ?>][objetivo]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoarea['objetivo']; ?></textarea></td>
                                                        <td><textarea name="objetivosarea[<?= $key; ?>][meta]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoarea['meta']; ?></textarea></td>
                                                        <td><textarea name="objetivosarea[<?= $key; ?>][indicador]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoarea['indicador']; ?></textarea></td>
                                                        <td><textarea name="objetivosarea[<?= $key; ?>][plan]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoarea['plan']; ?></textarea></td>
                                                        <td class="text-center" <?= ($formulario['estadoformulario'] == 'EVA') ? 'rowspan="2"' : ''; ?>>
                                                            <input type="hidden" name="objetivosarea[<?= $key ?>][id]" value="<?= $objetivoarea['id']; ?>" />
                                                            <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>
                                                            <button type="button" class="btn btn-xs btn-danger btn-eliminar-objetivoarea" tabindex="-1"><i class="fa fa-times"></i></button>
                                                            <?php } ?>
                                                            <?php if($objetivoarea['retroalimentaciones']){ ?>
                                                            <br />
                                                            <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs btn-warning mt-10" data-toggle="modal" data-objetivoarea="<?= $objetivoarea['id'] ?>" tabindex="-1"><i class="fa fa-commenting"></i></button>
                                                            <?php } ?>
                                                        </td>

                                                        <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>
                                                            <td class="text-center" rowspan="2"><?= $objetivoarea['evaluacion_puntaje']; ?></td>
                                                        <?php } ?>

                                                    </tr>

                                                    <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>

                                                        <tr>
                                                            <td colspan="6">
                                                                <textarea class="form-control" rows="3" placeholder="Detalles de la evaluación" readonly><?= $objetivoarea['evaluacion_observacion']; ?></textarea>
                                                            </td>
                                                        </tr>

                                                    <?php } ?>
                                                
                                                <?php } ?>
                                                
                                                <?php if(( ! $objetivosarea) AND ($formulario['estadoformulario'] != 'CON') AND ($formulario['estadoformulario'] != 'EVA') AND ($formulario['estadoformulario'] != 'APE')){ ?>
                                                
                                                <tr>
                                                    <td><textarea name="objetivosarea[0][proyecto]" class="form-control" rows="5" data-index="0"></textarea></td>
                                                    <td><textarea name="objetivosarea[0][descripcion]" class="form-control" rows="5" data-index="0"></textarea></td>
                                                    <td><textarea name="objetivosarea[0][objetivo]" class="form-control" rows="5" data-index="0"></textarea></td>
                                                    <td><textarea name="objetivosarea[0][meta]" class="form-control" rows="5" data-index="0"></textarea></td>
                                                    <td><textarea name="objetivosarea[0][indicador]" class="form-control" rows="5" data-index="0"></textarea></td>
                                                    <td><textarea name="objetivosarea[0][plan]" class="form-control" rows="5" data-index="0"></textarea></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-danger btn-eliminar-objetivoarea" tabindex="-1"><i class="fa fa-times"></i></button>
                                                    </td>
                                                </tr>
                                                
                                                <?php } ?>
                                                
                                            </tbody>

                                        </table>
                                        <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>
                                        <button type="button" class="btn btn-info pull-right btn-agregar-objetivoarea"><i class="fa fa-plus"></i> Agregar otro objetivo por Área</button>
                                        <?php } ?>
                                    </div>
                                </fieldset>

                                <p class="mt-10 mb-20">2. Objetivos Individuales. </p>

                                <fieldset id="objetivoindividual">
                                    <div class="table-responsive">
                                        <table id="table-objetivosindividuales-transversales" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10%">ORIGEN</th>
                                                    <th style="width: 14%">DIMENSIÓN</th>
                                                    <th style="width: 30%">OBJETIVO</th>
                                                    <th style="width: 30%">PLAN DE ACCIÓN</th>
                                                    
                                                    <?php if(($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>
                                                    
                                                        <th style="width: 6%"><abbr title="RETROALIMENTACIÓN">RET</abbr></th>

                                                        <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>
                                                            <th style="width: 10%"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                                        <?php } ?>
                                                    
                                                    <?php }else{ ?>
                                                    
                                                        <th style="width: 16%"></th>
                                                    
                                                    <?php } ?> 
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($objetivosindividuales['transversales'] as $key => $objetivoindividual){ ?>
                                                
                                                <tr <?= ($objetivoindividual['retroalimentaciones']) ? 'class="warning"' : ''; ?>>
                                                    <?php if($key == 0){ ?>
                                                    <td rowspan="<?= (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')) ? (count($objetivosindividuales['transversales']) * 2) : count($objetivosindividuales['transversales']); ?>">
                                                        <strong>PLAN ESTRATEGICO</strong><br />Competencias transversales
                                                    </td>
                                                    <?php } ?>
                                                    
                                                    <td class="text-uppercase"><?= $objetivoindividual['dimension']; ?></td>
                                                    <td><?= $objetivoindividual['objetivo']; ?></td>
                                                    <td>
                                                        <textarea name="objetivosindividuales[<?= $key; ?>][plan]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoindividual['plan']; ?></textarea>
                                                    </td>
                                                    <td class="text-center" <?= ($formulario['estadoformulario'] == 'EVA') ? 'rowspan="2"' : ''; ?>>
                                                        <input type="hidden" name="objetivosindividuales[<?= $key ?>][competenciatransversal]" value="<?= $objetivoindividual['competenciatransversal']; ?>" />
                                                        <input type="hidden" name="objetivosindividuales[<?= $key ?>][id]" value="<?= $objetivoindividual['id']; ?>" />
                                                        <?php if($objetivoindividual['retroalimentaciones']){ ?>
                                                        <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs btn-warning mt-10" data-toggle="modal" data-objetivoindividual="<?= $objetivoindividual['id'] ?>" tabindex="-1"><i class="fa fa-commenting"></i></button>
                                                        <?php } ?>
                                                    </td>
                                                    
                                                    <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>
                                                        <td class="text-center" rowspan="2"><?= $objetivoindividual['evaluacion_puntaje']; ?></td>
                                                    <?php } ?>
                                                    
                                                </tr>
                                                
                                                    <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>

                                                        <tr>
                                                            <td colspan="3">
                                                                <textarea class="form-control" rows="3" placeholder="Detalles de la evaluación" readonly><?= $objetivoindividual['evaluacion_observacion']; ?></textarea>
                                                            </td>
                                                        </tr>

                                                    <?php } ?>
                                                
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>
                                        <table id="table-objetivosindividuales" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10%">ORIGEN</th>
                                                    <th style="width: 14%">DIMENSIÓN</th>
                                                    <th style="width: 30%">OBJETIVO</th>
                                                    <th style="width: 30%">PLAN DE ACCIÓN</th>
                                                    <?php if(($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>
                                                    
                                                        <th style="width: 6%"><abbr title="RETROALIMENTACIÓN">RET</abbr></th>

                                                        <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>
                                                            <th style="width: 10%"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                                        <?php } ?>
                                                    
                                                    <?php }else{ ?>
                                                    
                                                        <th style="width: 16%"></th>
                                                    
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($objetivosindividuales['notransversales'] as $key => $objetivoindividual){ ?>
                                                
                                                <tr <?= ($objetivoindividual['retroalimentaciones']) ? 'class="warning"' : ''; ?>>
                                                    <?php if($key == 0){ ?>
                                                    <td rowspan="<?= (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')) ? (count($objetivosindividuales['notransversales']) * 2) : count($objetivosindividuales['notransversales']); ?>">
                                                        <strong>DESCRIPTOR DE CARGO</strong><br />Competencias Específicas
                                                    </td>
                                                    <?php } ?>
                                                    
                                                    <?php $key = $key + count($objetivosindividuales['transversales']); ?>                                                    
                                                    
                                                    <td><textarea name="objetivosindividuales[<?= $key; ?>][dimension]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoindividual['dimension']; ?></textarea></td>
                                                    <td><textarea name="objetivosindividuales[<?= $key; ?>][objetivo]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoindividual['objetivo']; ?></textarea></td>
                                                    <td><textarea name="objetivosindividuales[<?= $key; ?>][plan]" class="form-control" rows="5" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $objetivoindividual['plan']; ?></textarea></td>
                                                    <td class="text-center" <?= ($formulario['estadoformulario'] == 'EVA') ? 'rowspan="2"' : ''; ?>>
                                                        <input type="hidden" name="objetivosindividuales[<?= $key ?>][id]" value="<?= $objetivoindividual['id']; ?>" />
                                                        <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>
                                                        <button type="button" class="btn btn-xs btn-danger btn-eliminar-objetivoindividual" tabindex="-1"><i class="fa fa-times"></i></button>
                                                        <?php } ?>
                                                        <?php if($objetivoindividual['retroalimentaciones']){ ?>
                                                        <br />
                                                        <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs btn-warning mt-10" data-toggle="modal" data-objetivoindividual="<?= $objetivoindividual['id'] ?>" tabindex="-1"><i class="fa fa-commenting"></i></button>
                                                        <?php } ?>
                                                    </td>
                                                    
                                                    <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>
                                                        <td class="text-center" rowspan="2"><?= $objetivoindividual['evaluacion_puntaje']; ?></td>
                                                    <?php } ?>
                                                    
                                                </tr>
                                                
                                                    <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>

                                                        <tr>
                                                            <td colspan="3">
                                                                <textarea class="form-control" rows="3" placeholder="Detalles de la evaluación" readonly><?= $objetivoindividual['evaluacion_observacion']; ?></textarea>
                                                            </td>
                                                        </tr>

                                                    <?php } ?>
                                                
                                                <?php } ?>
                                                
                                                <?php if(( ! $objetivosindividuales['notransversales']) AND ($formulario['estadoformulario'] != 'CON') AND ($formulario['estadoformulario'] != 'EVA') AND ($formulario['estadoformulario'] != 'APE')){ ?>
                                                
                                                <tr>
                                                    <td rowspan="1">
                                                        <strong>DESCRIPTOR DE CARGO</strong><br />Competencias Específicas
                                                    </td>
                                                    <td><textarea name="objetivosindividuales[<?= count($objetivosindividuales['transversales']); ?>][dimension]" class="form-control" rows="5" data-index="<?= count($objetivosindividuales['transversales']); ?>"></textarea></td>
                                                    <td><textarea name="objetivosindividuales[<?= count($objetivosindividuales['transversales']); ?>][objetivo]" class="form-control" rows="5" data-index="<?= count($objetivosindividuales['transversales']); ?>"></textarea></td>
                                                    <td><textarea name="objetivosindividuales[<?= count($objetivosindividuales['transversales']); ?>][plan]" class="form-control" rows="5" data-index="<?= count($objetivosindividuales['transversales']); ?>"></textarea></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-xs btn-danger btn-eliminar-objetivoindividual" tabindex="-1"><i class="fa fa-times"></i></button>
                                                    </td>
                                                </tr>
                                                
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>
                                        
                                        <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>
                                        <button type="button" class="btn btn-info pull-right btn-agregar-objetivoindividual"><i class="fa fa-plus"></i> Agregar otro objetivo individual</button>
                                        <?php } ?>
                                    </div>
                                </fieldset>

                                <h3 class="mt-40 mb-20">CAPACITACIÓN</h3>

                                <fieldset id="capacitacion">

                                    <div class="form-group">

                                        <label class="control-label mr-40"><i class="fa fa-asterisk text-danger"></i> El Trabajador Requiere Capacitación:</label>
                                        <label class="radio-inline">
                                            <input type="radio" value="0" name="radio-capacitacion" <?= ( ! $capacitaciones) ? 'checked' : ''; ?> <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'disabled' : '';?> /> No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" value="1" name="radio-capacitacion" <?= ($capacitaciones) ? 'checked' : ''; ?> <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'disabled' : '';?> /> Si
                                        </label>

                                    </div>

                                    <div class="row" id="div-capacitaciones" <?= ( ! $capacitaciones) ? 'style="display:none;"' : ''; ?>>

                                        <div class="col-md-8">

											<div class="table-responsive">

												<table id="table-capacitaciones" class="table table-striped table-bordered">
		                                            <thead>
		                                                <tr>
		                                                    <th style="width: 90%">SUGERENCIA DE CAPACITACIÓN</th>
		                                                    <th style="width: 10%"></th>
		                                                </tr>
		                                            </thead>
		                                            <tbody>
                                                        
                                                        <?php foreach($capacitaciones as $key => $capacitacion){ ?>
                                                
                                                        <tr>
                                                            <td><textarea name="capacitaciones[<?= $key; ?>][descripcion]" class="form-control" rows="3" data-index="<?= $key; ?>" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $capacitacion['descripcion']; ?></textarea></td>
                                                            <td class="text-center">
                                                                <input type="hidden" name="capacitaciones[<?= $key ?>][id]" value="<?= $capacitacion['id']; ?>" />
                                                                <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>
                                                                <button type="button" class="btn btn-xs btn-danger btn-eliminar-capacitacion" tabindex="-1"><i class="fa fa-times"></i></button>
                                                                <?php } ?>
                                                                <?php if($capacitacion['retroalimentaciones']){ ?>
                                                                <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs btn-warning mt-10" data-toggle="modal" data-capacitacion="<?= $capacitacion['id'] ?>" tabindex="-1"><i class="fa fa-commenting"></i></button>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>

                                                        <?php } ?>
                                                        
                                                        <?php if(( ! $capacitaciones) AND ($formulario['estadoformulario'] != 'CON') AND ($formulario['estadoformulario'] != 'EVA') AND ($formulario['estadoformulario'] != 'APE')){ ?>
                                                
                                                        <tr>
                                                            <td><textarea name="capacitaciones[0][descripcion]" class="form-control" rows="3" data-index="0"></textarea></td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-xs btn-danger btn-eliminar-capacitacion" tabindex="-1"><i class="fa fa-times"></i></button>
                                                            </td>
                                                        </tr>

                                                        <?php } ?>
                                                        
                                                    </tbody>
                                                </table>
                                                
                                                <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>
                                                <button type="button" class="btn btn-info pull-right btn-agregar-capacitacion"><i class="fa fa-plus"></i> Agregar otra sugerencia de Capacitación</button>
                                                <?php } ?>
                                            </div>

                                        </div>

                                    </div>

                                </fieldset>
                                
                                <h3 class="mt-20 mb-20">OBSERVACIONES, COMENTARIOS Y/O SUGERENCIAS <u>DEL EVALUADO</u></h3>
                                
                                <fieldset>

                                    <div class="row">

                                        <div class="col-md-8">

											<div class="form-group">
                                                <textarea name="observaciones" class="form-control" rows="5" placeholder="Opcional" <?= (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'readonly' : '';?>><?= $formulario['observaciones']; ?></textarea>
                                            </div>

                                        </div>

                                    </div>

                                </fieldset>
                                
                                <?php if(($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')){ ?>
                                
                                <h3 class="mt-20 mb-20">OBSERVACIONES FINALES RESULTADO EVALUACIÓN</h3>
                                
                                <fieldset>

                                    <div class="row">

                                        <div class="col-md-8">

											<div class="form-group">
                                                <textarea class="form-control" rows="5" name="resultado[observacion]" placeholder="Resultados" readonly><?= $resultado['observacion']; ?></textarea>
                                            </div>

                                        </div>

                                    </div>

                                </fieldset>
                                
                                <?php } ?>
                                
                                <?php if($formulario['apelacion']){ ?>
                                
                                <h3 class="mt-20 mb-20">APELACIÓN DEL EVALUADO</h3>
                                
                                <fieldset>

                                    <div class="row">

                                        <div class="col-md-8">

											<div class="form-group">
                                                <textarea class="form-control" rows="5" name="formulario[apelacion]" placeholder="Resultados" readonly><?= $formulario['apelacion']; ?></textarea>
                                            </div>

                                        </div>

                                    </div>

                                </fieldset>
                                
                                <?php } ?>
                                
                                <?php if($resultadoapelacion){ ?>
                                
                                <h3 class="mt-20 mb-20" id="resultado-apelacion">RESULTADO APELACIÓN</h3>
                                
                                <fieldset>

                                    <div class="row">

                                        <div class="col-md-8">

											<div class="form-group">
                                                <textarea class="form-control" rows="5" name="resultadoapelacion[observacion]" placeholder="Resultado de la apelación" readonly><?= $resultadoapelacion['observacion']; ?></textarea>
                                            </div>

                                        </div>

                                    </div>

                                </fieldset>
                                
                                <?php } ?>
                                
                                <fieldset class="mt-40 mb-40">
                                    
                                    <div class="row">
                                    
                                    <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>
                                    
                                        <div class="col-lg-offset-3 col-lg-2 col-md-4">
                                            <a href="<?= site_url(''); ?>" class="btn btn-lg btn-default btn-block btn-cancelar submit"><i class="fa fa-times"></i> Cancelar</a>
                                        </div>
                                        <p class="visible-xs visible-sm"></p>
                                        <div class="col-lg-2 col-md-4">
                                            <a href="<?= site_url('formulario/grabar'); ?>" class="btn btn-lg btn-default btn-block btn-grabar-formulario submit"><i class="fa fa-save"></i> Grabar</a>
                                        </div>
                                        <p class="visible-xs visible-sm"></p>
                                        <div class="col-lg-2 col-md-4">
                                            <input type="hidden" name="id" value="<?= $formulario['id']; ?>" />
                                            <a href="<?= site_url('formulario/enviar'); ?>" class="btn btn-lg btn-danger btn-block btn-enviar-formulario submit"><i class="fa fa-check"></i> Enviar</a>
                                        </div>
                                    
                                    <?php }else if(($formulario['estadoformulario'] == 'EVA') AND ($etapa_actual['puedeapelar'])){ ?>
                                        
                                        <div class="col-lg-offset-4 col-md-offset-2 col-lg-2 col-md-4">
                                            <a href="<?= site_url(''); ?>" class="btn btn-lg btn-default btn-block btn-cancelar"><i class="fa fa-times"></i> Cancelar</a>
                                        </div>
                                        <p class="visible-xs visible-sm"></p>
                                        <div class="col-lg-2 col-md-4">
                                            <a href="#modal-apelacion" class="btn btn-lg btn-warning btn-block submit" data-toggle="modal"><i class="fa fa-warning"></i> Apelar evaluación</a>
                                        </div>
                                        
                                    <?php }else{ ?>
                                        
                                        <div class="col-lg-offset-5 col-md-offset-4 col-lg-2 col-md-4">
                                            <a href="<?= site_url(''); ?>" class="btn btn-lg btn-default btn-block btn-cancelar"><i class="fa fa-times"></i> Cancelar</a>
                                        </div>
                                        
                                    <?php } ?>
                                        
                                    </div>
                                    
                                </fieldset>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <div class="modal fade" id="modal-retroalimentacion" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Retroalimentación</h4>
                        </div>
                        
                        <div class="modal-body">
                            <div class="timeline-centered">
                                    
                                <div class="timeline-entries">
                                     <div class="text-center"><i class="fa fa-cog fa-spin fa-2x fa-fw"></i><br />Cargando...</div>                    
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-apelacion" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <form id="form-apelacion" action="<?= site_url('formulario/apelar') ?>" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Apelación</h4>
                            </div>

                            <div class="modal-body">
                                <fieldset>
                                    <div class="form-group">
                                        <textarea name="apelacion" rows="3" placeholder="Detalles de la apelación" class="form-control" required><?= $formulario['apelacion']; ?></textarea>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="id" value="<?= $formulario['id']; ?>" />
                                <button type="button" class="btn btn-default submit" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-warning submit btn-apelar">Enviar apelación</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <script>

                $(function(){
                    
                    <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>
                    
                    $('.btn-agregar-objetivoarea').click(function(e){
                        
                        e.preventDefault();
                        $(e.currentTarget).blur();
                        
                        var max = ($('#table-objetivosarea tbody tr').length > 0) ? $('#table-objetivosarea tbody tr td textarea[name*=objetivosarea]').last().data('index') + 1 : 0;
                        
                        $('#table-objetivosarea tbody').append(
                            $('<tr>').append(
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosarea['+ max +'][proyecto]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosarea['+ max +'][descripcion]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosarea['+ max +'][objetivo]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosarea['+ max +'][meta]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosarea['+ max +'][indicador]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosarea['+ max +'][plan]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>', {
                                    class : 'text-center'   
                                }).append(
                                    $('<button>', {
                                        type : 'button',
                                        class : 'btn btn-xs btn-danger btn-eliminar-objetivoarea'
                                    }).append(
                                        $('<i>', {
                                            class : 'fa fa-times'
                                        })
                                    )
                                )
                            )
                        ); 
                        
                        $('#table-objetivoarea tbody tr:last-child td:first-child textarea').focus();
                    });
                    
                    $('#table-objetivosarea').on('click', '.btn-eliminar-objetivoarea', function(e){
                        e.preventDefault();
                        if(confirm('Desea eliminar este objetivo?')){ $(e.currentTarget).parent().parent().remove(); }
                    });
                    
                    $('.btn-agregar-objetivoindividual').click(function(e){
                        
                        e.preventDefault();
                        $(e.currentTarget).blur();
                        
                        var max = ($('#table-objetivosindividuales tbody tr').length > 0) ? $('#table-objetivosindividuales tbody tr td textarea[name*=objetivosindividuales]').last().data('index') + 1 : $('#table-objetivosindividuales-transversales tbody tr td textarea[name*=objetivosindividuales]').last().data('index') + 1;
                        
                        $('#table-objetivosindividuales tbody').append(
                            $('<tr>').append(
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosindividuales['+ max +'][dimension]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosindividuales['+ max +'][objetivo]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'objetivosindividuales['+ max +'][plan]',
                                        class : 'form-control',
                                        rows : 5,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>', {
                                    class : 'text-center'   
                                }).append(
                                    $('<button>', {
                                        type : 'button',
                                        class : 'btn btn-xs btn-danger btn-eliminar-objetivoindividual btn-eliminar'
                                    }).append(
                                        $('<i>', {
                                            class : 'fa fa-times'
                                        })
                                    )
                                )
                            )
                        );
                        
                        if($('#table-objetivosindividuales tbody tr').length == 1)
                        {
                            $('#table-objetivosindividuales tbody tr').prepend(
                                $('<td>', {
                                    rowspan : 1   
                                }).append(
                                    $('<strong>').append(
                                        'DESCRIPTOR DE CARGO'   
                                    ),
                                    $('<br>'),
                                    'Competencias Específicas'
                                )
                            );
                        }
                        else
                        {
                            $('#table-objetivosindividuales tbody tr:first-child td:first-child').attr('rowspan', $('#table-objetivosindividuales tbody tr').length + 1);
                        }
                        
                        $('#table-objetivosindividuales tbody tr:last-child td:first-child textarea').focus();
                    });
                    
                    $('#table-objetivosindividuales').on('click', '.btn-eliminar-objetivoindividual', function(e){
                        e.preventDefault();
                        if(confirm('Desea eliminar este objetivo?'))
                        { 
                            $(e.currentTarget).parent().parent().remove();
                            
                            if($('#table-objetivosindividuales tbody tr').length != 0)
                            {
                                $('#table-objetivosindividuales tbody tr td[rowspan]').remove();
                           
                                $('#table-objetivosindividuales tbody tr:first-child').prepend(
                                    $('<td>', {
                                        rowspan : $('#table-objetivosindividuales tbody tr').length   
                                    }).append(
                                        $('<strong>').append(
                                            'DESCRIPTOR DE CARGO'   
                                        ),
                                        $('<br>'),
                                        'Competencias Específicas'
                                    )
                                );
                            }
                        }
                    });
                    
                    $('input[name=radio-capacitacion]').change(function(e){                        
                        if($(e.currentTarget).val() == 1)
                        { 
                            $('#div-capacitaciones').slideDown(); 
                        }
                        else
                        { 
                            $('#div-capacitaciones').slideUp(); 
                        }                        
                    });
                    
                    $('.btn-agregar-capacitacion').click(function(e){
                        
                        e.preventDefault();
                        $(e.currentTarget).blur();
                        
                        var max = ($('#table-capacitaciones tbody tr').length > 0) ? $('#table-capacitaciones tbody tr td textarea[name*=capacitaciones]').last().data('index') + 1 : 0;
                        
                        $('#table-capacitaciones tbody').append(
                            $('<tr>').append(
                                $('<td>').append(
                                    $('<textarea>', {
                                        name : 'capacitaciones['+ max +'][descripcion]',
                                        class : 'form-control',
                                        rows : 3,
                                        'data-index' : max
                                    })    
                                ),
                                $('<td>', {
                                    class : 'text-center'   
                                }).append(
                                    $('<button>', {
                                        type : 'button',
                                        class : 'btn btn-xs btn-danger btn-eliminar-capacitacion btn-eliminar'
                                    }).append(
                                        $('<i>', {
                                            class : 'fa fa-times'
                                        })
                                    )
                                )
                            )
                        ); 
                        
                        $('#table-capacitaciones tbody tr textarea:last-child').focus();
                    });
                    
                    $('#table-capacitaciones').on('click', '.btn-eliminar-capacitacion', function(e){
                        e.preventDefault();
                        if(confirm('Desea eliminar esta sugerencia de Capacitación?')){ $(e.currentTarget).parent().parent().remove(); }
                    });
                    
                    $('.btn-grabar-formulario').click(function(e){
                        e.preventDefault(); 
                        $('#form-formulario').attr('action', $(e.currentTarget).attr('href')).submit();
                    });
                    
                    $('.btn-enviar-formulario').click(function(e){
                        e.preventDefault(); 
                        if(confirm('Desea enviar el formulario a su evaluador para su revisión?')) $('#form-formulario').attr('action', $(e.currentTarget).attr('href')).submit();
                    });
                    
                    <?php }else if(($formulario['estadoformulario'] == 'EVA') AND ($etapa_actual['puedeapelar'])){ ?>
                    
                    $('.btn-apelar').click(function(e){
                        
                        e.preventDefault();
                        
                       if(confirm('Desea apelar al Comité de evaluación su Formulario?')){ 
                           $('#modal-apelacion').on('hide.bs.modal', function(e) {
                               e.preventDefault();
                           });
                           $('#form-apelacion').submit();
                        }

                    });
                    
                    <?php } ?>
                    
                    $('#modal-retroalimentacion').on('show.bs.modal', function(e){
                                                
                        if(typeof $(e.relatedTarget).data('objetivoarea') !== 'undefined')
                        {
                            var item = 'objetivoarea';
                            var id = $(e.relatedTarget).data('objetivoarea');
                        }
                        else if(typeof $(e.relatedTarget).data('objetivoindividual') !== 'undefined')
                        {
                            var item = 'objetivoindividual';
                            var id = $(e.relatedTarget).data('objetivoindividual');
                        }
                        else if(typeof $(e.relatedTarget).data('capacitacion') !== 'undefined')
                        {
                            var item = 'capacitacion';
                            var id = $(e.relatedTarget).data('capacitacion');
                        }
                                                
                        $.ajax({
                            url: '<?= site_url('api/retroalimentaciones'); ?>/' + item + '/' + id,
                            statusCode : {
                                200 : function(data){
                                    
                                    $('#modal-retroalimentacion .timeline-entries').empty();

                                    $.each(data, function(i, item){
                                    
                                        $('#modal-retroalimentacion .timeline-entries').append(
                                            $('<div>', {
                                                class : 'timeline-entry'   
                                            }).append(
                                                $('<div>', {
                                                    class : 'timeline-entry-inner'   
                                                }).append(
                                                    $('<div>', {
                                                        class : 'timeline-icon'       
                                                    }).append(
                                                        $('<img>', {
                                                            src : eval('sexo.'+ item.usuario.sexo),
                                                            class : 'img-responsive img-circle',
                                                            alt : ''
                                                        })   
                                                    ),
                                                    $('<div>', {
                                                        class : 'timeline-label'    
                                                    }).append(
                                                        $('<p>').append(
                                                            $('<strong>', {
                                                                class : 'text-primary'   
                                                            }).append(
                                                                item.usuario.nombre   
                                                            ), ' ',
                                                            $('<span>').append(
                                                                item.creado   
                                                            )
                                                        ),
                                                        item.retroalimentacion
                                                    )
                                                )
                                            )
                                        );
                                    });                       
                                },
                                
                                204 : function()
                                {
                                    $('#modal-retroalimentacion .timeline-entries').empty();
                                }
                            }
                        });
                    });
                });
                
            </script>
