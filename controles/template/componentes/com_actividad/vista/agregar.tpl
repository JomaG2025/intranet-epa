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
    
    <legend>Nueva Actividad</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Estado</label>
<select name="activo" class="form-control">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
</div>

<div class="row">
<div class="col-md-6 form-group">
<label>Capítulo</label>
<input type="text" class="form-control" name="capitulo" pattern="[a-zA-Z0-9.-*]+" placeholder="00.00" autocomplete="off" required/>
</div>
<div class="col-md-6 form-group">
<br />
<div class="checkbox">
<label>
    <input type="checkbox" name="padre" value="1" /> Capítulo Padre
    </label>
</div>
</div>
</div>

<div class="row">
<div class="col-md-6 form-group">
<label>Días alerta</label>
<input type="number" class="form-control" name="alerta" autocomplete="off" value="10" required/>
</div>

<div class="col-md-6 form-group">
<label>Planificación</label>
<select name="contrato" class="form-control" disabled="disabled">
                {foreach item=contrato from=$contratos}
                
                <option value="{$contrato.id}" {if $SESSION_CONTRATO eq $contrato.id}selected="selected"{/if} >{$contrato.nombre}</option>
                
                {/foreach}
                
</select>
</div>
</div>
        </div>
        
        <div class="col-md-6">
        <div class="form-group">
        <label>Nombre</label>
        <input type="text" class="form-control" name="nombre" maxlength="255" autocomplete="off" required/>
        </div>
        <div class="row">
<div class="col-md-6 form-group">
<label>Grupo</label>
<select name="grupo" class="form-control">
                    <option value="">Sin grupo</option>
                    {foreach item=value from=$grupos}
                        <option value="{$value.id}">{$value.nombre}</option>
                    {/foreach}
                </select>
</div>

<div class="col-md-6 form-group">
<label>Manual</label>
<select name="manual" class="form-control">
                    <option value="">Sin manual</option>
                    {foreach item=value from=$manuales}
                        <option value="{$value.id}">{$value.nombre} - {$value.version}</option>
                    {/foreach}
                </select>   
</div>
</div>

        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion"></textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=actividad" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="contrato" value="{$SESSION_CONTRATO}" />
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