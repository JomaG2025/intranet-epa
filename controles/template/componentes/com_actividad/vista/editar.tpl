<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Actividades</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Actividades</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=actividad&amp;accion=grabar">
    
    <fieldset>
    
    <legend>Editar Actividad</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Estado</label>
<select name="activo" class="form-control">
                    <option value="1" {if $actividad.activo eq 1}selected="selected"{/if}>Activo</option>
                    <option value="0" {if $actividad.activo eq 0}selected="selected"{/if}>Inactivo</option>
                </select>
</div>

<div class="row">
<div class="col-md-6 form-group">
<label>Capítulo</label>
<input type="text" class="form-control" name="capitulo" pattern="[a-zA-Z0-9.-*]+" autocomplete="off" value="{$actividad.capitulo}" required/>
</div>
<div class="col-md-6 form-group">
<br />
<div class="checkbox">
<label>
    <input type="checkbox" name="padre" value="1" {if $actividad.padre eq 1}checked="checked"{/if}  /> Capítulo Padre
    </label>
</div>
</div>
</div>

<div class="row">
<div class="col-md-6 form-group">
<label>Días alerta</label>
<input type="number" class="form-control" value="{$actividad.alerta}" name="alerta" autocomplete="off" required/>
</div>

<div class="col-md-6 form-group">
<label>Planificación</label>
<select name="contrato" class="form-control" disabled="disabled">
                {foreach item=contrato from=$contratos}
                
                <option value="{$contrato.id}" {if $actividad.contrato eq $contrato.id}selected="selected"{/if} >{$contrato.nombre}</option>
                
                {/foreach}
                
</select>
</div>
</div>
        </div>
        
        <div class="col-md-6">
        <div class="form-group">
        <label>Nombre</label>
        <input type="text" class="form-control" name="nombre" maxlength="255" autocomplete="off" value="{$actividad.nombre}" required/>
        </div>
        <div class="row">
<div class="col-md-6 form-group">
<label>Grupo</label>
<select name="grupo" class="form-control">
                    <option value="" {if '' eq $actividad.grupo} selected="selected" {/if}>Sin grupo</option>
                    {foreach item=value from=$grupos}
                        <option value="{$value.id}" {if $value.id eq $actividad.grupo} selected="selected" {/if}>{$value.nombre}</option>
                    {/foreach}
                </select>
</div>

<div class="col-md-6 form-group">
<label>Manual</label>
<select name="manual" class="form-control">
                    <option value="" {if '' eq $actividad.manual} selected="selected" {/if}>Sin manual</option>
                    {foreach item=value from=$manuales}
                        <option value="{$value.id}" {if $value.id eq $actividad.manual} selected="selected" {/if}>{$value.nombre} - {$value.version}</option>
                    {/foreach}
                </select>   
</div>
</div>

        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion">{$actividad.descripcion}</textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=actividad" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="id" value="{$actividad.id}" />
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