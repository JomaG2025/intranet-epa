        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Punto de Control</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=control" class="btn btn-info" title="Volver"><i class="fa fa-caret-left"></i> Volver</a>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-tasks"></i> Punto de Control <a href="index.php?com=control" class="pull-right" title="Volver"><i class="fa fa-caret-left"></i> Volver</a></div>

<div class="panel-body">
        
        <div class="row">
        
        <div class="col-md-8">
        
        <h3 class="text-uppercase text-primary">{$actividad.nombre}</h3>
                            
                            <ol class="breadcrumb">
                                <li><a href="index.php?com=control&amp;contrato={$contrato.id}">{$contrato.nombre}</a></li>
     <li>Capítulo {$actividad.capitulo}</li>
     <li class="active">{$actividad.nombre}</li>
</ol>
        
        <div class="alert alert-{$control.tipoalerta} alert-dismissible" role="alert">
<strong>{$control.nombre_estado}</strong> {$control.descripcion_estado}
{if $control.estado eq 'APR' or $control.estado eq 'REC'}
{if $resultado.observacion neq ''}
                    <br />
                    Observaciones: {$resultado.observacion}
{/if}
                {/if}
</div>
                            
                            {if $actividad.descripcion neq ''}
                                {$actividad.descripcion|nl2br}
                                <p>&nbsp;</p>
                            {/if}

            {foreach item=value from=$responsables}
 {if $value.usuario eq $SESSION_ID and $value.apr eq '1'}
                                    {if $actividad.descripcion neq ''}
                                        <a href="#" class="btn btn-info btn-xs btn-editar"><i class="fa fa-edit"></i> Editar observación</a>
                                    {else}
                                        <a href="#" class="btn btn-info btn-xs btn-editar"><i class="fa fa-edit"></i> Agregar observación</a>
                                    {/if}
                                    <p class="clearfix">&nbsp;</p>
 {/if}
            {/foreach}
                            
                            {if $SESSION_TIPO eq 'ADM'}
                                {if $actividad.descripcion neq ''}
                                    <a href="#" class="btn btn-info btn-xs btn-editar"><i class="fa fa-edit"></i> Editar observación</a>
                                {else}
                                    <a href="#" class="btn btn-info btn-xs btn-editar"><i class="fa fa-edit"></i> Agregar observación</a>
                                {/if}
                                <p class="clearfix">&nbsp;</p>
                            {/if}
                            
                            {if $control.adjuntos neq '0'}
            
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <i class="fa fa-folder"></i> Documentación adjunta
                                    </div>

                                    <div class="panel-body">

                                        <ul class="list-group">

                                        {foreach item=adjunto from=$adjuntos}
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-xs-11">
                                                        {if $adjunto.archivo}
                                                        <a href="index.php?com=adjunto&amp;accion=descargar&amp;id={$adjunto.id}">
                                                            <span class="fa-stack fa fa-2x pull-left" style="margin-bottom: 20px;">
                                                                <i class="fa fa-file fa-stack-2x"></i>
                                                                <span class="fa fa-stack-1x fa-inverse">.{$adjunto.extension}</span>
                                                            </span>
                                                        </a>
                                                        {/if}

                                                        <h4 style="margin: 0;">{$adjunto.nombre}</h4>
                                                        {if $adjunto.observacion}<p class="small" style="margin: 0;">{$adjunto.observacion}</p>{/if}
                                                        {if $adjunto.archivo}<a href="index.php?com=adjunto&amp;accion=descargar&amp;id={$adjunto.id}">{$adjunto.archivo}</a>{/if}

                                                        <p><strong>{$adjunto.nombre_usuario}</strong> &bull; {$adjunto.fecha} a las {$adjunto.hora} hrs.{if $adjunto.archivo} &bull; {$adjunto.size}{/if}</p>
                                                    </div>
                                                    <div class="col-xs-1">
                                                        {foreach item=value from=$responsables}
                                                            {if $value.usuario eq $SESSION_ID and $value.dig eq '1' and $control.estado neq 'APR'}
                                                                <div class="btn-group pull-right">
                                                                    <i data-toggle="dropdown" class="dropdown-toggle fa fa-cog fa-lg"></i>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="index.php?com=adjunto&amp;accion=eliminar&amp;id={$adjunto.id}" class="btn-eliminar">Eliminar</a></li>
                                                                    </ul>
                                                                </div>
                                                            {/if}
                                                        {/foreach}  
                                                    </div>
                                                </div>
                                            </li>
                                        {foreachelse}

                                            <li class="list-group-item">
                                                <p>Aún no se ha registrado Documentación.</p>
                                            </li>

                                        {/foreach}

                                        </ul>

                                    </div>
                                </div>
                            {/if}
            
            {if $control.estado neq 'APR'}
            
                {if $control.adjuntos neq '0'}
                           
                    {foreach item=value from=$responsables}
                        {if $value.usuario eq $SESSION_ID and $value.dig eq '1'}
                        
<div class="panel panel-default">
<div class="panel-heading">
<i class="fa fa-cloud-upload"></i> Adjuntar Documentación
</div>
<div class="panel-body">
<form action="index.php?com=adjunto&amp;accion=grabar" method="post" enctype="multipart/form-data">
<fieldset>
<div class="row">
<div class="form-group col-md-8">
<label>Nombre</label>
<input type="text" name="nombre" class="form-control" required autocomplete="off" placeholder="Nombre de la Documentación"/>
</div>
<div class="form-group col-md-4">
<label>Archivo</label>
<input type="file" name="adjunto" class="form-control" />
</div>
</div>
<div class="row">
<div class="col-md-8 form-group">
<textarea name="observacion" class="form-control" rows="4" placeholder="Descripción de la Documentación"></textarea>
</div>
<div class="col-md-4 form-group">
<input type="hidden" name="actividad" value="{$actividad.id}" /> 
                                        <input type="hidden" name="control" value="{$control.id}" />
                                        <input type="hidden" name="usuario" value="{$SESSION_ID}" />
                                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
</div>
</div>
</fieldset>
</form>
</div>
</div>
                    
                        {/if}
                        
                    {/foreach}
                    
                {/if}
                            
            {/if}
            
            {if ($ultimo_adjunto neq '' and $control.estado neq 'APR') or ($control.adjuntos eq '0' and $control.estado neq 'APR')}
            
                {foreach item=value from=$responsables}
                    {if $value.usuario eq $SESSION_ID and $value.apr eq '1'}
                        
                    <div class="panel panel-default">
<div class="panel-heading">
<i class="fa fa-check"></i> Ingresar Resultado
</div>

<div class="panel-body">
<form action="index.php?com=resultado&amp;accion=grabar" method="post">
<fieldset>
<div class="row">
<div class="form-group col-md-8">
<label>Observaciones</label>
<textarea class="form-control" name="observacion" rows="4" required></textarea>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Estado</label>
<select class="form-control" name="estado">
                                        <option value="REC">Rechazado</option>
                                        <option value="APR">Aprobado</option>
                                    </select>
</div>
<div class="form-group">
                                    <input type="hidden" name="actividad" value="{$actividad.id}" />  
                                    <input type="hidden" name="control" value="{$control.id}" />
                                    <input type="hidden" name="aprobador" value="{$SESSION_ID}" />
                                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button> 
</div>
</div>
</div>
</fieldset>
</form>
</div>
                    </div>
                    
                    {/if}
                        
                {/foreach}
                
            {/if}

        </div>
        
        <div class="col-md-4">
        <div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-file"></i> Documentación Vigente</div>

<div class="panel-body">
<ul class="list-group">
<li class="list-group-item">
                                            <i class="fa fa-book pull-left fa-3x"></i>
{if $contrato.archivo neq ''}
                <p><strong>Descargar Manual:</strong><br /><a href="index.php?com=contrato&amp;accion=descargar&amp;id={$contrato.id}">{$contrato.nombre}</a></p>
                {else}
                     <p>{$contrato.nombre}<br/>{$contrato.descripcion}</p>
                {/if}
</li>

{if $manual.nombre neq ''}
<li class="list-group-item">
<i class="fa fa-file-text pull-left fa-3x"></i>
<p><strong>Descargar sólo capítulo:</strong><br /><a href="index.php?com=manual&amp;accion=descargar&amp;id={$manual.id}">{$manual.nombre}</a></p>                
</li>
            {/if}
</ul>
 
</div>
        </div>
        
        <div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-info"></i> Control</div>

<div class="panel-body">
<ul class="list-group">
<li class="list-group-item">
<i class="fa fa-calendar pull-left fa-3x"></i>
                <p><strong>Fecha comprometida</strong><br />{$control.fecha}</p>
</li>
<li class="list-group-item">
<i class="fa fa-history pull-left fa-3x"></i>
                <p><strong>Último evento</strong><br />{$registro.estado} el {$registro.fecha}</p>
</li>
</ul>
</div>
        </div>
        
        <div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-bell"></i> Actividad</div>

<div class="panel-body">
<ul class="list-group">
<li class="list-group-item">
<i class="fa fa-calendar pull-left fa-3x"></i>
<p><strong>Planificación</strong><br />
{foreach item=mes from=$array_meses}
                    {$mes}
                {/foreach}
            </p>
</li>
<li class="list-group-item">
<div class="pull-left mr-20">
<i class="fa fa-user fa-3x"></i>
</div>
                                            <div class="pull-left">
                                                <p><strong>Usuarios responsables</strong><br /><small>
{foreach item=value from=$responsables}
                    
                    {if $value.apr eq 1}
                    Aprobador: {$value.nombre}<br />                   
                    {/if}
                    
                    {if $value.dig eq 1}
                    Digitador: {$value.nombre}<br />                       
                    {/if}
                    
                    {if $value.ale eq 1}
                    Alertado: {$value.nombre}<br />                       
                    {/if}
                    
                {foreachelse}
                            
                    No existen usuarios asignados a esta actividad.   
                    
                {/foreach}
     </small></p>
                                            </div>
<div class="clearfix"></div>
</li>
{if $grupo.nombre neq ''}   
<li class="list-group-item">
<i class="fa fa-users pull-left fa-3x"></i>
<p><strong>Grupo</strong><br />
{$grupo.nombre}{if $grupo.descripcion neq ''}&bulls;{$grupo.descripcion}{/if}
            </p>
</li>
{/if}
</ul>
</div>
        </div>
        
        <div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-folder"></i> Últimos documentos de la Actividad</div>

<div class="panel-body">
<ul class="list-group">
{foreach item=adjunto from=$otros_adjuntos}
<li class="list-group-item">
<i class="fa fa-file"></i> {$adjunto.fecha} <a href="index.php?com=adjunto&amp;accion=descargar&amp;id={$adjunto.id}"><small>{$adjunto.nombre} &bull; {$adjunto.size} &bull; {$adjunto.extension}</small></a>
</li>
{foreachelse}
                       <li class="list-group-item">No se han registrado archivos adjuntos en otros controles.</li>               
                    {/foreach}
</ul>
</div>
        </div>
        
        {if $SESSION_TIPO eq 'ADM'}
        
        <div class="panel panel-default">
<div class="panel-heading"><i class="fa fa-cogs"></i> Administrador</div>

<div class="panel-body">
<div class="row">
<div class="col-md-6">
<a href="index.php?com=control&amp;accion=editar&amp;id={$control.id}" class="btn btn-block btn-warning"><i class="fa fa-cogs"></i> Editar</a>
</div>
<p class="clearfix visible-xs visible-sm"></p>
<div class="col-md-6">
<a href="index.php?com=control&amp;accion=eliminar&amp;id={$control.id}" class="btn btn-block btn-danger btn-eliminar"><i class="fa fa-times"></i> Eliminar</a>
</div>
</div>
</div>
        </div>
        
        {/if}

        </div>
        
        </div>
        
    </div>
</div>
<div class="modal fade" id="modal-descripcion" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog">
   <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Editar descripción</h4>
    </div>
    <div class="modal-body">
    
    <div class="row">
    
    <div class="col-xs-12">
    
    <form id="form-descripcion" action="index.php?com=control&amp;accion=grabar_descripcion" method="post">
    
    <div class="form-group">
    
    <textarea name="descripcion" class="form-control" rows="5">{$actividad.descripcion}</textarea>
    <input type="hidden" name="actividad" value="{$actividad.id} " />
    <input type="hidden" name="control" value="{$control.id} " />
    
    </div>

    </form>
     
    </div>
    
    </div>
       
</div>
<div class="modal-footer">
<div class="row">
<div class="col-xs-6 col-md-3 col-md-push-6">
<button type="button" class="btn btn-success btn-block btn-grabar-descripcion"><i class="fa fa-check"></i> Aceptar</button>
</div>
<div class="col-xs-6 col-md-3 col-md-push-6">
<button type="button" class="btn btn-primary btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
$(function(){
$('.btn-editar').click(function(e){
e.preventDefault();
$('#modal-descripcion').modal('show');
});

$('.btn-grabar-descripcion').click(function(e){
$('#form-descripcion').submit();
});
});
</script>