            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Mi historial <i class="fa fa-angle-right"></i> <?= $formulario['periodo']; ?></h4>
			</div>

            <div class="navbar-action hidden-xs">
			    <a href="<?= site_url('historial'); ?>" class="btn btn-default"><i class="fa fa-angle-left"></i> Volver</a>
			</div>	

            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default no-border">
                        
                        <div class="panel-heading visible-xs"><a href="<?= site_url('historial'); ?>" class="btn btn-info btn-xs pull-right"><i class="fa fa-angle-left"></i> Volver</a></div>
                        
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

                            <form action="#" method="post" enctype="multipart/form-data">
                                
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
                                                    
                                                    <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>
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
                                                        <td class="text-center" <?= (($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'rowspan="2"' : ''; ?>>
                                                            <?php if($objetivoarea['retroalimentaciones']){ ?>
                                                            <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs <?= ($objetivoarea['retroalimentaciones']) ? 'btn-warning' : 'btn-default'; ?>" data-toggle="modal" data-objetivoarea="<?= $objetivoarea['id']; ?>"><i class="fa fa-commenting"></i></button>
                                                            <?php } ?>
                                                        </td>

                                                        <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>

                                                            <td class="text-center" rowspan="2"><?= $objetivoarea['evaluacion_puntaje']; ?></td>

                                                        <?php } ?>

                                                    </tr>
                                                
                                                    <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>

                                                        <tr>
                                                            <td colspan="6">
                                                                <textarea class="form-control" rows="3" name="evaluacion[objetivosarea][<?= $key; ?>][observacion]" placeholder="Detalles de la evaluación" readonly><?= $objetivoarea['evaluacion_observacion']; ?></textarea>
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
                                                    
                                                    <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>
                                                        <th style="width: 10%"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                                    <?php } ?>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($objetivosindividuales['transversales'] as $key => $objetivoindividual){ ?>
                                                
                                                    <tr>
                                                        <?php if($key == 0){ ?>
                                                        <td rowspan="<?= (($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? (count($objetivosindividuales['transversales']) * 2) : count($objetivosindividuales['transversales']); ?>">
                                                            <strong>PLAN ESTRATEGICO</strong><br />Competencias transversales
                                                        </td>
                                                        <?php } ?>

                                                        <td class="text-uppercase"><?= $objetivoindividual['dimension']; ?></td>
                                                        <td><?= $objetivoindividual['objetivo']; ?></td>
                                                        <td>
                                                            <textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoindividual['plan']; ?></textarea>
                                                        </td>
                                                        <td class="text-center" <?= (($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'rowspan="2"' : ''; ?>>
                                                            <?php if($objetivoindividual['retroalimentaciones']){ ?>
                                                            <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs <?= ($objetivoindividual['retroalimentaciones']) ? 'btn-warning' : 'btn-default'; ?>" data-toggle="modal" data-objetivoindividual="<?= $objetivoindividual['id'] ?>"><i class="fa fa-commenting"></i></button>
                                                            <?php } ?>
                                                        </td>

                                                        <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>

                                                            <td class="text-center" rowspan="2"><?= $objetivoindividual['evaluacion_puntaje']; ?></td>

                                                        <?php } ?>

                                                    </tr>
                                                
                                                    <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>

                                                        <tr>
                                                            <td colspan="3">
                                                                <textarea class="form-control" rows="3" name="evaluacion[objetivosindividuales][<?= $key; ?>][observacion]" placeholder="Detalles de la evaluación" readonly><?= $objetivoindividual['evaluacion_observacion']; ?></textarea>
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
                                                    
                                                    <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>
                                                        <th style="width: 10%"><abbr title="EVALUACIÓN">EVA</abbr></th>
                                                    <?php } ?>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($objetivosindividuales['notransversales'] as $key => $objetivoindividual){ ?>
                                                
                                                    <tr>
                                                        <?php if($key == 0){ ?>
                                                        <td rowspan="<?= (($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? (count($objetivosindividuales['notransversales']) * 2) : count($objetivosindividuales['notransversales']); ?>">
                                                            <strong>DESCRIPTOR DE CARGO</strong><br />Competencias Específicas
                                                        </td>
                                                        <?php } ?>  

                                                        <?php $key = $key + count($objetivosindividuales['transversales']); ?>

                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoindividual['dimension']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoindividual['objetivo']; ?></textarea></td>
                                                        <td><textarea class="form-control" rows="5" readonly tabindex="-1"><?= $objetivoindividual['plan']; ?></textarea></td>
                                                        <td class="text-center" <?= (($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')) ? 'rowspan="2"' : ''; ?>>
                                                            <?php if($objetivoindividual['retroalimentaciones']){ ?>
                                                            <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs <?= ($objetivoindividual['retroalimentaciones']) ? 'btn-warning' : 'btn-default'; ?>" data-toggle="modal" data-objetivoindividual="<?= $objetivoindividual['id'] ?>"><i class="fa fa-commenting"></i></button>
                                                            <?php } ?>
                                                        </td>

                                                        <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>

                                                            <td class="text-center" rowspan="2"><?= $objetivoindividual['evaluacion_puntaje']; ?></td>

                                                        <?php } ?>

                                                    </tr>
                                                
                                                    <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>

                                                        <tr>
                                                            <td colspan="3">
                                                                <textarea class="form-control" rows="3" name="evaluacion[objetivosindividuales][<?= $key; ?>][observacion]" placeholder="Detalles de la evaluación" readonly><?= $objetivoindividual['evaluacion_observacion']; ?></textarea>
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
                                                                <td><textarea class="form-control" rows="2" readonly tabindex="-1"><?= $capacitacion['descripcion']; ?></textarea></td>
                                                                <td class="text-center">
                                                                    <?php if($capacitacion['retroalimentaciones']){ ?>
                                                                    <button type="button" data-target="#modal-retroalimentacion" class="btn btn-xs <?= ($capacitacion['retroalimentaciones']) ? 'btn-warning' : 'btn-default'; ?>" data-toggle="modal" data-capacitacion="<?= $capacitacion['id'] ?>"><i class="fa fa-commenting"></i></button>
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
                                
                                <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?>
                                
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
                                        
                                        <div class="col-lg-offset-5 col-md-offset-4 col-lg-2 col-md-4">
                                            <a href="<?= site_url('historial'); ?>" class="btn btn-lg btn-default btn-block btn-cancelar"><i class="fa fa-times"></i> Cancelar</a>
                                        </div>
                                        
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

                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>

            <script>
                $(function(){
                    
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