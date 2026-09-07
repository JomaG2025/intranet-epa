            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Mis evaluados Periodo <?= $formulario['periodo']; ?> <i class="fa fa-angle-right"></i> <?= $formulario['nombre']; ?></h4>
			</div>
            <div class="navbar-action hidden-xs">
			    <a href="<?= site_url('evaluador'); ?>" class="btn btn-default"><i class="fa fa-angle-left"></i> Volver</a>
			</div>	
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default no-border">
                        
                        <div class="panel-heading visible-xs"><a href="<?= site_url('evaluador'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-angle-left"></i> Volver</a></div>
                        
                        <div class="panel-body">
                            
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
                            </div>

                            <form id="form-formulario" action="<?= site_url('evaluador/evaluar') ?>" method="post" enctype="multipart/form-data">
                                
                                <h2 class="text-primary text-uppercase">FORMULARIO EPPO <?= $formulario['periodo']; ?></h2>
                                <hr />
                                
                                <div class="clearfix mb-10"></div>
                                
                                <span class="pull-right"><i class="fa fa-asterisk text-danger"></i> Campos requeridos</span>
                                
                                <div class="clearfix mb-20"></div>

                                <fieldset>

                                    <div class="row">

                                        <div class="col-md-7">

                                            <div class="form-group row form-horizontal">

                                                <label class="control-label col-sm-2 text-right"><i class="fa fa-asterisk text-danger"></i> Evaluado</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" maxlength="255" disabled value="<?= $formulario['nombre']; ?>" tabindex="-1" />
                                                </div>
                                                
                                            </div>

                                        </div>

                                        <div class="col-md-5">

                                            <div class="form-group row form-horizontal">

                                                <label class="control-label col-sm-2 col-md-3 text-right"><i class="fa fa-asterisk text-danger"></i> Nivel</label>
                                                <div class="col-sm-10 col-md-9">
                                                    <select class="form-control" disabled tabindex="-1">
                                                        <option value="">Sin selección</option>
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
                                                <label class="control-label col-sm-2 text-right"><i class="fa fa-asterisk text-danger"></i> <?= (count($evaluadores) == 1) ? 'Evaluador' : 'Evaluadores'; ?></label>
                                                <div class="col-sm-10">
                                                    <?php foreach($evaluadores as $value){ ?>
                                                    <input type="text" class="form-control mb-15" maxlength="255" disabled value="<?= $value['nombre']; ?>" tabindex="-1" />
                                                    <?php } ?>
                                                </div>
                                                <?php } ?>

                                            </div>

                                        </div>

                                        <div class="col-md-5">

                                            <div class="form-group row form-horizontal">

                                                <label class="control-label col-sm-2 col-md-3 text-right"><i class="fa fa-asterisk text-danger"></i> Contrato</label>
                                                <div class="col-sm-10 col-md-9">
                                                    <select class="form-control" disabled tabindex="-1">
                                                        <option value="">Sin selección</option>
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
                                                    <th style="width: 6%"><abbr title="RETROALIMENTACIÓN">RET</abbr></th>
                                                    
                                                    <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>
                                                        <th style="width: 10%"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                                    <?php } ?>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($objetivosarea as $key => $objetivoarea){ ?>
                                                
                                                    <tr>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoarea['proyecto']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoarea['descripcion']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoarea['objetivo']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoarea['meta']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoarea['indicador']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoarea['plan']; ?></textarea></td>
                                                        <td class="text-center" <?= (($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))) ? 'rowspan="2"' : ''; ?>>
                                                            <?php if($objetivoarea['retroalimentaciones'] OR ($evaluador AND (($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')))){ ?>
                                                            <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs <?= ($objetivoarea['retroalimentaciones']) ? 'btn-warning' : 'btn-default'; ?>" data-toggle="modal" data-objetivoarea="<?= $objetivoarea['id']; ?>" tabindex="-1"><i class="fa fa-commenting"></i></button>
                                                            <?php } ?>
                                                        </td>

                                                        <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>

                                                            <td class="text-center" rowspan="2">
                                                                
                                                                <?php if($etapa_actual['puedeevaluar']){ ?>
                                                                
                                                                    <input type="hidden" name="evaluacion[objetivosarea][<?= $key; ?>][objetivoarea]" value="<?= $objetivoarea['id']; ?>" />
                                                                    <select name="evaluacion[objetivosarea][<?= $key; ?>][sigla]" class="form-control">

                                                                        <?php foreach($mediciones as $medicion){ ?>

                                                                        <option value="<?= $medicion['sigla']; ?>" <?= ($objetivoarea['evaluacion_sigla'] == $medicion['sigla']) ? 'selected' : ''; ?>><?= $medicion['puntaje']; ?></option>

                                                                        <?php } ?>

                                                                    </select>
                                                                
                                                                <?php }else{ ?>
                                                                
                                                                    <?= $objetivoarea['evaluacion_puntaje']; ?>
                                                                
                                                                <?php } ?>
                                                                
                                                            </td>

                                                        <?php } ?>

                                                    </tr>
                                                
                                                    <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>

                                                        <tr>
                                                            <td colspan="6">
                                                                <textarea class="form-control" rows="3" name="evaluacion[objetivosarea][<?= $key; ?>][observacion]" placeholder="Detalles de la evaluación" <?= ( ! $etapa_actual['puedeevaluar']) ? 'readonly' : '';?>><?= $objetivoarea['evaluacion_observacion']; ?></textarea>
                                                            </td>
                                                        </tr>

                                                    <?php } ?>
                                                
                                                <?php } ?>
                                                
                                            </tbody>

                                        </table>
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
                                                    <th style="width: 6%"><abbr title="RETROALIMENTACIÓN">RET</abbr></th>
                                                    
                                                    <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>
                                                        <th style="width: 10%"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                                    <?php } ?>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($objetivosindividuales['transversales'] as $key => $objetivoindividual){ ?>
                                                
                                                    <tr>
                                                        <?php if($key == 0){ ?>
                                                        <td rowspan="<?= (($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))) ? (count($objetivosindividuales['transversales']) * 2) : count($objetivosindividuales['transversales']); ?>">
                                                            <strong>PLAN ESTRATEGICO</strong><br />Competencias transversales
                                                        </td>
                                                        <?php } ?>

                                                        <td class="text-uppercase"><?= $objetivoindividual['dimension']; ?></td>
                                                        <td><?= $objetivoindividual['objetivo']; ?></td>
                                                        <td>
                                                            <textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoindividual['plan']; ?></textarea>
                                                        </td>
                                                        <td class="text-center" <?= (($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA')))? 'rowspan="2"' : ''; ?>>
                                                            <?php if($objetivoindividual['retroalimentaciones'] OR ($evaluador AND (($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')))){ ?>
                                                            <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs <?= ($objetivoindividual['retroalimentaciones']) ? 'btn-warning' : 'btn-default'; ?>" data-toggle="modal" data-objetivoindividual="<?= $objetivoindividual['id'] ?>" tabindex="-1"><i class="fa fa-commenting"></i></button>
                                                            <?php } ?>
                                                        </td>

                                                        <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>

                                                            <td class="text-center" rowspan="2">
                                                                
                                                                <?php if($etapa_actual['puedeevaluar']){ ?>
                                                                    <input type="hidden" name="evaluacion[objetivosindividuales][<?= $key; ?>][objetivoindividual]" value="<?= $objetivoindividual['id']; ?>" />
                                                                    <select name="evaluacion[objetivosindividuales][<?= $key; ?>][sigla]" class="form-control">

                                                                        <?php foreach($mediciones as $medicion){ ?>

                                                                        <option value="<?= $medicion['sigla']; ?>" <?= ($objetivoindividual['evaluacion_sigla'] == $medicion['sigla']) ? 'selected' : ''; ?>><?= $medicion['puntaje']; ?></option>

                                                                        <?php } ?>

                                                                    </select>
                                                                
                                                                <?php }else{ ?>
                                                                
                                                                    <?= $objetivoindividual['evaluacion_puntaje']; ?>
                                                                
                                                                <?php } ?>
                                                                
                                                            </td>

                                                        <?php } ?>

                                                    </tr>
                                                
                                                    <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>

                                                        <tr>
                                                            <td colspan="3">
                                                                <textarea class="form-control" rows="3" name="evaluacion[objetivosindividuales][<?= $key; ?>][observacion]" placeholder="Detalles de la evaluación" <?= ( ! $etapa_actual['puedeevaluar']) ? 'readonly' : '';?>><?= $objetivoindividual['evaluacion_observacion']; ?></textarea>
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
                                                    <th style="width: 6%"><abbr title="RETROALIMENTACIÓN">RET</abbr></th>
                                                    
                                                    <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>
                                                        <th style="width: 10%"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                                    <?php } ?>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($objetivosindividuales['notransversales'] as $key => $objetivoindividual){ ?>
                                                
                                                    <tr>
                                                        <?php if($key == 0){ ?>
                                                        <td rowspan="<?= (($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))) ? (count($objetivosindividuales['notransversales']) * 2) : count($objetivosindividuales['notransversales']); ?>">
                                                            <strong>DESCRIPTOR DE CARGO</strong><br />Competencias Específicas
                                                        </td>
                                                        <?php } ?>  

                                                        <?php $key = $key + count($objetivosindividuales['transversales']); ?>

                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoindividual['dimension']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoindividual['objetivo']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoindividual['plan']; ?></textarea></td>
                                                        <td class="text-center" <?= (($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))) ? 'rowspan="2"' : ''; ?>>
                                                            <?php if($objetivoindividual['retroalimentaciones'] OR ($evaluador AND (($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')))){ ?>
                                                            <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs <?= ($objetivoindividual['retroalimentaciones']) ? 'btn-warning' : 'btn-default'; ?>" data-toggle="modal" data-objetivoindividual="<?= $objetivoindividual['id'] ?>" tabindex="-1"><i class="fa fa-commenting"></i></button>
                                                            <?php } ?>
                                                        </td>

                                                        <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>

                                                            <td class="text-center" rowspan="2">
                                                                
                                                                <?php if($etapa_actual['puedeevaluar']){ ?>
                                                                
                                                                    <input type="hidden" name="evaluacion[objetivosindividuales][<?= $key; ?>][objetivoindividual]" value="<?= $objetivoindividual['id']; ?>" />
                                                                    <select name="evaluacion[objetivosindividuales][<?= $key; ?>][sigla]" class="form-control">

                                                                        <?php foreach($mediciones as $medicion){ ?>

                                                                        <option value="<?= $medicion['sigla']; ?>" <?= ($objetivoindividual['evaluacion_sigla'] == $medicion['sigla']) ? 'selected' : ''; ?>><?= $medicion['puntaje']; ?></option>

                                                                        <?php } ?>

                                                                    </select>
                                                                
                                                                <?php }else{ ?>
                                                                
                                                                    <?= $objetivoindividual['evaluacion_puntaje']; ?>
                                                                
                                                                <?php } ?>
                                                                
                                                            </td>

                                                        <?php } ?>

                                                    </tr>
                                                
                                                    <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>

                                                        <tr>
                                                            <td colspan="3">
                                                                <textarea class="form-control" rows="3" name="evaluacion[objetivosindividuales][<?= $key; ?>][observacion]" placeholder="Detalles de la evaluación" <?= ( ! $etapa_actual['puedeevaluar']) ? 'readonly' : '';?>><?= $objetivoindividual['evaluacion_observacion']; ?></textarea>
                                                            </td>
                                                        </tr>

                                                    <?php } ?>
                                                                                       
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>                                        
                                    </div>
                                </fieldset>

                                <h3 class="mt-40 mb-20">CAPACITACIÓN</h3>

                                <fieldset id="capacitacion">

                                    <div class="form-group">

                                        <label class="control-label mr-40"><i class="fa fa-asterisk text-danger"></i> El Trabajador Requiere Capacitación:</label>
                                        <label class="radio-inline">
                                            <input type="radio" value="0" name="radio-capacitacion" <?= ( ! $capacitaciones) ? 'checked' : ''; ?> tabindex="-1" disabled /> No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" value="1" name="radio-capacitacion" <?= ($capacitaciones) ? 'checked' : ''; ?> tabindex="-1" disabled /> Si
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
                                                                <td><textarea class="form-control" rows="3" readonly tabindex="-1"><?= $capacitacion['descripcion']; ?></textarea></td>
                                                                <td class="text-center">
                                                                    <?php if($capacitacion['retroalimentaciones'] OR ($evaluador AND (($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')))){ ?>
                                                                    <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs <?= ($capacitacion['retroalimentaciones']) ? 'btn-warning' : 'btn-default'; ?>" data-toggle="modal" data-capacitacion="<?= $capacitacion['id'] ?>" tabindex="-1"><i class="fa fa-commenting"></i></button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>

                                                        <?php } ?>
                                                        
                                                    </tbody>
                                                </table>
                                                
                                            </div>

                                        </div>

                                    </div>

                                </fieldset>
                                
                                <h3 class="mt-20 mb-20">OBSERVACIONES, COMENTARIOS Y/O SUGERENCIAS <u>DEL EVALUADO</u></h3>
                                
                                <fieldset>

                                    <div class="row">

                                        <div class="col-md-8">

											<div class="form-group">
                                                <textarea class="form-control" rows="5" placeholder="Opcional" readonly tabindex="-1"><?= $formulario['observaciones']; ?></textarea>
                                            </div>

                                        </div>

                                    </div>

                                </fieldset>
                                
                                <?php if(($etapa_actual['puedeevaluar'] AND (($formulario['estadoformulario'] == 'CON') OR ($formulario['estadoformulario'] == 'EVA'))) OR (($formulario['estadoformulario'] == 'APE') OR ($formulario['estadoformulario'] == 'EVA'))){ ?>
                                
                                <h3 class="mt-20 mb-20">OBSERVACIONES FINALES RESULTADO EVALUACIÓN</h3>
                                
                                <fieldset>

                                    <div class="row">

                                        <div class="col-md-8">

											<div class="form-group">
                                                <textarea class="form-control" rows="5" name="resultado[observacion]" placeholder="Resultados" <?= ( ! $etapa_actual['puedeevaluar']) ? 'readonly' : '';?>><?= $resultado['observacion']; ?></textarea>
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
                                                <textarea class="form-control" rows="5" name="resultado[observacion]" placeholder="Resultados" readonly><?= $formulario['apelacion']; ?></textarea>
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
                                        
                                        <?php if($evaluador AND (($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA'))){ ?>
                                        
                                            <div class="col-lg-offset-4 col-md-offset-2 col-lg-2 col-md-4">
                                                <a href="<?= site_url('evaluador'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar submit"><i class="fa fa-times"></i> Cancelar</a>
                                            </div>
                                            <p class="visible-xs visible-sm"></p>
                                            <div class="col-lg-2 col-md-4">
                                                <a href="<?= site_url('evaluador/consensuar/' . $formulario['id']); ?>" class="btn btn-lg btn-danger btn-block btn-consensuar-formulario submit"><i class="fa fa-lock"></i> Consensuar</a>
                                            </div>
                                        
                                        
                                        <?php }else if($formulario['estadoformulario'] == 'CON'){ ?>
                                        
                                            <?php if($evaluador){ ?>
                                        
                                                <?php if($etapa_actual['puedeevaluar']){ ?>

                                                    <div class="col-lg-offset-3 col-lg-2 col-md-4">
                                                        <a href="<?= site_url('evaluador'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar submit"><i class="fa fa-times"></i> Cancelar</a>
                                                    </div>
                                                    <p class="visible-xs visible-sm"></p>
                                                    <div class="col-lg-2 col-md-4">
                                                        <a href="<?= site_url('evaluador/reabrir/' . $formulario['id']); ?>" class="btn btn-lg btn-danger btn-block btn-reabrir-formulario submit"><i class="fa fa-lock"></i> Re-abrir</a>
                                                    </div>
                                                    <p class="visible-xs visible-sm"></p>
                                                    <div class="col-lg-2 col-md-4">
                                                        <input type="hidden" name="id" value="<?= $formulario['id']; ?>" />
                                                        <button type="submit" class="btn btn-block btn-warning btn-lg"><i class="fa fa-check"></i> Evaluar</button>   
                                                    </div>

                                                <?php }else{ ?>

                                                    <div class="col-lg-offset-4 col-md-offset-2 col-lg-2 col-md-4">
                                                        <a href="<?= site_url('evaluador'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar submit"><i class="fa fa-times"></i> Cancelar</a>
                                                    </div>
                                                    <p class="visible-xs visible-sm"></p>
                                                    <div class="col-lg-2 col-md-4">
                                                        <a href="<?= site_url('evaluador/reabrir/' . $formulario['id']); ?>" class="btn btn-lg btn-danger btn-block btn-reabrir-formulario submit"><i class="fa fa-lock"></i> Re-abrir</a>
                                                    </div>

                                                <?php } ?>
                                        
                                            <?php }else{ ?>
                                        
                                                <?php if($etapa_actual['puedeevaluar']){ ?>

                                                    <div class="col-lg-offset-4 col-md-offset-2 col-lg-2 col-md-4">
                                                        <a href="<?= site_url('evaluador'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar submit"><i class="fa fa-times"></i> Cancelar</a>
                                                    </div>
                                                    <p class="visible-xs visible-sm"></p>
                                                    <div class="col-lg-2 col-md-4">
                                                        <input type="hidden" name="id" value="<?= $formulario['id']; ?>" />
                                                        <button type="submit" class="btn btn-block btn-warning btn-lg"><i class="fa fa-check"></i> Evaluar</button>   
                                                    </div>

                                                <?php }else{ ?>

                                                    <div class="col-lg-offset-5 col-md-offset-4 col-lg-2 col-md-4">
                                                        <a href="<?= site_url('evaluador'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar"><i class="fa fa-times"></i> Cancelar</a>
                                                    </div>

                                                <?php } ?>
                                        
                                            <?php } ?>
                                        
                                        <?php }else if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>
                                        
                                            <?php if($etapa_actual['puedeevaluar']){ ?>
                                        
                                                <div class="col-lg-offset-4 col-md-offset-2 col-lg-2 col-md-4">
                                                    <a href="<?= site_url('evaluador'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar"><i class="fa fa-times"></i> Cancelar</a>
                                                </div>
                                                <p class="visible-xs visible-sm"></p>
                                                <div class="col-lg-2 col-md-4">
                                                    <input type="hidden" name="id" value="<?= $formulario['id']; ?>" />
                                                    <button type="submit" class="btn btn-block btn-warning btn-lg"><i class="fa fa-check"></i> Evaluar</button>   
                                                </div>
                                        
                                            <?php }else{ ?>
                                        
                                                <div class="col-lg-offset-5 col-md-offset-4 col-lg-2 col-md-4">
                                                    <a href="<?= site_url('evaluador'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar"><i class="fa fa-times"></i> Cancelar</a>
                                                </div>
                                        
                                            <?php } ?>
                                        
                                        <?php }else{ ?>
                                        
                                            <div class="col-lg-offset-5 col-md-offset-4 col-lg-2 col-md-4">
                                                <a href="<?= site_url('evaluador'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar"><i class="fa fa-times"></i> Cancelar</a>
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
                        
                        <form action="<?= site_url('evaluador/retroalimentar') ?>" method="post">
                        
                            <div class="modal-body">
                                <div class="timeline-centered">
                                    
                                    <div class="timeline-entries">
                                         <div class="text-center"><i class="fa fa-cog fa-spin fa-2x fa-fw"></i><br />Cargando...</div>                    
                                    </div>
                                    
                                    <?php if(($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA')){ ?>

                                    <div class="timeline-entry">

                                        <div class="timeline-entry-inner">

                                            <div class="timeline-icon">
                                                <?php if($this->session->userdata('sexo') == 'F'){ ?>
                                                    <img src="data:image/png;base64,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" class="img-responsive img-circle" alt=""/>
                                                    <?php }else{ ?>
                                                    <img src="data:image/png;base64,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" class="img-responsive img-circle" alt=""/>
                                                <?php } ?>
                                            </div>

                                            <div class="timeline-label">

                                                <fieldset>
                                                    <div class="form-group">
                                                        <textarea name="retroalimentacion" rows="3" class="form-control" placeholder="Retroalimentación" required autofocus></textarea>
                                                    </div>
                                                </fieldset>
                                                    
                                            </div>
                                        </div>

                                    </div>
                                    
                                    <?php } ?>

                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <?php if($evaluador AND (($formulario['estadoformulario'] == 'INI') OR ($formulario['estadoformulario'] == 'ENV') OR ($formulario['estadoformulario'] == 'RET') OR ($formulario['estadoformulario'] == 'REA'))){ ?>
                                <input type="hidden" name="formulario" value="<?= $formulario['id']; ?>" />
                                <input type="hidden" name="item" value="" />
                                <input type="hidden" name="id" value="" />
                                <button type="submit" class="btn btn-primary">Grabar</button>
                                <?php } ?>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>

            <script>
                $(function(){
                    
                    $('body').on('click', '.btn-consensuar-formulario', function(e){
                        if(confirm('Desea consensuar el formulario? Una vez realizado no se podrán realizar cambios ni retroalimentaciones a menos que sea re-abierto.'))
                        {
                            $('form button[type=submit], form .submit').addClass('disabled').empty().append($('<i>', { class : 'fa fa-spin fa-cog' }), ' Cargando').blur();
                        }
                        else
                        {
                            return false;
                        }
                    });

                    $('body').on('click', '.btn-reabrir-formulario', function(e){
                        if(confirm('Desea re-abrir el Formulario para que el eveluado pueda realizar cambios.'))
                        {
                            $('form button[type=submit], form .submit').addClass('disabled').empty().append($('<i>', { class : 'fa fa-spin fa-cog' }), ' Cargando').blur();
                        }
                        else
                        {
                            return false;
                        }
                    });
                    
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
                        
                        $('#modal-retroalimentacion input[name=item]').val(item);
                        $('#modal-retroalimentacion input[name=id]').val(id);
                        
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