            <div class="navbar-title hidden-xs">
			    <h4><i class="fa fa-angle-right"></i> Proceso Periodo <?= $periodo_actual['periodo']; ?></h4>
			</div>
            <div class="row">
                
                <div class="col-lg-3 col-md-4">
                    
                    <?php if($etapas){ ?>
                    
                    <div class="panel panel-alt no-border">
                        <div class="panel-heading lg">
                            Avance del Periodo <?= $periodo_actual['periodo']; ?>
                        </div>
                        <div class="panel-body">
                            <h3 class="color-alt mt-10">
                                ETAPA <?= $etapa_actual['correlativo']; ?> <span class="text-muted pull-right"><?= ($etapa_siguiente['correlativo']) ? 'ETAPA '. $etapa_siguiente['correlativo'] : ''; ?></span>
                            </h3>
                            
                            <div class="progress progress-bar-sm ">
                                                                
                                <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?= $porcentaje_avance; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje_avance; ?>%"><?= $porcentaje_avance; ?>%</div>
                                
                            </div>
                            <div class="clearfix mt-0 text-muted">
                                <div class="pull-left text-uppercase"><?= ($etapa_anterior) ? formato_fecha($etapa_anterior['limite']) : '1 de Enero de '. $periodo_actual['periodo']; ?></div>
                                <div class="pull-right text-uppercase"><?= formato_fecha($etapa_actual['limite']); ?></div>
                            </div>
                            
                        </div>
                         <div class="list-group">
                            <a href="#modal-etapas" class="list-group-item" data-toggle="modal">
                                Ver todas las etapas <i class="fa fa-angle-right pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <?php } ?>
                    
                    <?php if($evaluadores){ ?>
                    
                    <div class="panel panel-alt no-border">
                        <div class="panel-heading lg">
                            <?= (count($evaluadores) == 1) ? 'Mi Evaluador' : 'Mis Evaluadores'; ?>
                        </div>
                        <div class="panel-body">
                            <?php foreach($evaluadores as $evaluador){ ?>
                            <h4 class="color-alt mb-0"><?= $evaluador['nombre']; ?></h4>
                            <p><?= $evaluador['cargo']; ?></p>
                            <?php } ?>
                        </div>
                        <div class="list-group">
                            <?php foreach($evaluadores as $evaluador){ ?>
                                <?php if($evaluador['email']){ ?>
                                <a href="mailto:<?= $evaluador['email']; ?>?subject=Consulta%20sobre%20evaluación%20EPPO" class="list-group-item">
                                    Enviar e-mail a <?= word_limiter($evaluador['nombre'], 1, ''); ?><i class="fa fa-angle-right pull-right"></i>
                                </a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                    <?php } ?>
                    
                    <div class="panel no-border panel-alt">
                        <div class="panel-heading lg">
                            Guía EPPO
                        </div>
                        <div class="panel-body">
                            <p>El objetivo general de la EPPO es evaluar el cumplimiento de objetivos trazados considerando el Plan Estratégico de la Empresa, permitiendo un intercambio entre las jefaturas y subordinados respecto a las fortalezas, debilidades y aspectos a mejorar en las habilidades que requiere cada colaborador(a) en sus áreas de trabajo.</p>
                        </div>
                        <div class="list-group">
                            <a href="<?= base_url(); ?>uploads/Guia_EPPO_2019.pdf" target="_blank" class="list-group-item">
                                Descargar Guía <i class="fa fa-download pull-right"></i>
                            </a>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-lg-9 col-md-8">
                    
                    <?php if($periodo_actual){ ?>
                    
                    <div class="panel panel-default no-border">
                        <div class="panel-heading lg">
                            Mi Formulario
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-9 col-md-7">
                                    
                                    <h3 class="mb-0 color-alt text-uppercase">    
                                        <span class="text-muted"><?= $this->session->userdata('nombre'); ?> <i class="fa fa-angle-right"></i></span> 
                                        <?= ($formulario) ? 'FORMULARIO '. $formulario['estadoformulario_nombre'] : 'CREACIÓN DE FORMULARIO'; ?>
                                        <?php if(($formulario['estadoformulario'] == 'EVA') OR ($formulario['estadoformulario'] == 'APE')){ ?> 
                                            <?= ($resultadoapelacion['puntaje']) ? $resultadoapelacion['puntaje'] : $resultado['puntaje']; ?> PTS.
                                        <?php } ?>
                                    </h3>
                                    
                                    <?php if($formulario){ ?><h4 class="text-muted"><?= ($formulario['modificado']) ? 'Última modificación el '. formato_fecha($formulario['modificado']) : 'Creado el '. formato_fecha($formulario['creado']); ?></h4><?php }else{ ?><br /><?php } ?>
                                    
                                    <p><?= ($formulario) ? nl2br($formulario['estadoformulario_descripcion']) : 'Aun no hay disponible el Formulario para el Periodo '.  $periodo_actual['periodo']  .'. Debe presionar el botón <a href="'.  site_url('formulario/crear')  .'" class="btn-crear-formulario"><u>Crear Formulario</u></a> para comenzar su proceso de evaluación.'; ?></p>
                                </div>
                                <p class="visible-sm visible-xs">&nbsp;</p>
                                <div class="col-lg-3 col-md-5">
                                    <?php if($formulario){ ?>
                                    <a href="<?= site_url('formulario'); ?>" class="btn btn-lg btn-block btn-info"><i class="fa fa-file-o"></i> Formulario</a>
                                    <? }else{ ?>
                                    <a href="<?= site_url('formulario/crear'); ?>" class="btn btn-lg btn-block btn-info btn-crear-formulario"><i class="fa fa-plus"></i> Crear Formulario <?= $periodo_actual['periodo']; ?></a>
                                    <?php } ?>
                                    <!--
                                    <a href="" class="btn btn-lg btn-block btn-default"><i class="fa fa-history"></i> Ver anteriores</a>
                                    -->
                                </div>
                            </div>
                            
                            
                        </div>
                        
                    </div>
                    
                    <?php }else{ ?>
                    
                    <div class="alert alert-warning">
                        <p><strong>ADVERTENCIA!</strong> No se ha creado ningún periodo. Favor contactarse con el área de Informática.</p>
                    </div>
                    
                    <?php } ?>
                    
                    <?php if($etapa_actual){ ?>
                    
                    <div class="panel panel-default no-border">
                        <div class="panel-heading lg">
                            Etapa actual
                        </div>
                        <div class="panel-body">
                            
                            <div class="row">
                                <div class="col-lg-8 text-justify">
                                    <h3 class="mt-0 color-alt text-uppercase"><span class="text-muted">PERIODO <?= $periodo_actual['periodo']; ?> <i class="fa fa-angle-right"></i></span> ETAPA <?= $etapa_actual['correlativo'] ?> <?= $etapa_actual['nombre']; ?></h3>
                                    <p><?= nl2br($etapa_actual['descripcion']); ?></p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <?php } ?>
                    
                    <?php if($evaluados){ ?>
                    
                    <div class="panel no-border">
                        <div class="panel-heading lg">
                            Mis evaluados del Periodo <?= $periodo_actual['periodo']; ?>
                        </div>
                        <table class="table table-stripped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">NOMBRE</th>
                                    <th class="text-center">CARGO</th>
                                    <th class="text-center">MODIFICADO</th>
                                    <th class="text-center"><abbr title="ESTADO FORMUALARIO">EST</abbr></th>
                                    <th class="text-center" style="width: 10%;"></th>
                                    <th class="text-center" style="width: 10%;"></th>
                                    <th class="text-center" style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($evaluados as $evaluado){ ?>
                                <tr>
                                    <td class="text-center"><?= $evaluado['nombre']; ?></td>
                                    <td class="text-center"><?= $evaluado['cargo']; ?></td>
                                    <td class="text-center"><?= ($evaluado['modificado']) ? $evaluado['modificado'] : (($evaluado['creado']) ? 'No se ha modificado' : ''); ?></td>
                                    <td class="text-center text-uppercase">
                                        <strong><?= ($evaluado['id']) ? '<abbr title="'. $evaluado['estadoformulario_descripcion'] .'">'. $evaluado['estadoformulario_nombre'] .'</abbr>' : 'NO CREADO'; ?></strong>
                                        <?php if($evaluado['estadoformulario'] == 'EVA'){ ?>
                                            <br /><?= ($evaluado['resultadoapelacion_puntaje']) ? $evaluado['resultadoapelacion_puntaje'] : $evaluado['resultado_puntaje']; ?> PTS.
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($evaluado['id']){ ?>
                                        <a href="<?= site_url('evaluador/formulario/'. $evaluado['id']); ?>" class="btn btn-danger btn-block"><i class="fa fa-file-o"></i> Formulario</a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($evaluado['id']){ ?>
                                        <a href="<?= site_url('formulario/excel/'. $evaluado['id']); ?>" class="btn btn-info btn-block"><i class="fa fa-file-excel-o"></i> Excel</a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="mailto:<?= $evaluado['email']; ?>?subject=Consulta%20sobre%20evaluación%20EPPO" class="btn btn-default btn-block"><i class="fa fa-envelope"></i> Enviar correo</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        
                        </table>
                    </div>
                    
                    <?php } ?>

                </div>
                
                
            </div>

            <?php if($etapas){ ?>

            <div class="modal fade" id="modal-etapas" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">EPPO <?= $periodo_actual['periodo']; ?> - ETAPAS Y LÍMITES</h4>
                        </div>
                        
                        <div class="modal-body">
                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20%;">ETAPAS</th>
                                        <th class="text-center">ACCIONES</th>
                                        <th class="text-center" style="width: 20%;">FECHA LÍMITE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($etapas as $etapa){ ?>
                                    <tr>
                                        <td class="text-center"><strong class="text-uppercase text-primary">Etapa <?= $etapa['correlativo']; ?> <?= $etapa['nombre']; ?></strong></td>
                                        <td>
                                            <p class="text-justify"><?= nl2br($etapa['descripcion']); ?></p>
                                        </td>
                                        <td class="text-center"><?= formato_fecha($etapa['limite']); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                            
                        </div>

                        
                    </div>
                </div>
            </div>

            <?php } ?>

            <script>
                $(function(){
                    
                    $('.btn-crear-formulario').click(function(e){
                        $(e.currentTarget).addClass('disabled').html('').append($('<i>', { class : 'fa fa-spin fa-cog' }), ' Cargando').blur(); 
                    });
                    
                });
            </script>
