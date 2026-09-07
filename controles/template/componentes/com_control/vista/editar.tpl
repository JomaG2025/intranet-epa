<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Punto de Control</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-tasks"></i> Punto de Control</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=control&amp;accion=grabar">
    
    <fieldset>
    
    <legend>Editar Punto de Control</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Actividad</label>
                <select name="actividad" class="form-control" disabled="disabled">
                    {foreach item=value from=$actividades}
                        <option value="{$value.id}" {if $control.actividad eq $value.id}selected="selected"{/if}>{$value.capitulo} - {$value.nombre}</option>
                    {/foreach}
                </select>
</div>
        </div>
        
        <div class="col-md-6">
        <div class="row">
<div class="col-md-4 form-group">
<label>Fecha</label>
                                                        <input type="text" class="form-control" name="fecha" value="{$control.fecha}" autocomplete="off" required />
</div>
<div class="col-md-4 form-group">
<label>Adjuntos</label>
<select name="adjuntos" class="form-control">
    <option value="1" {if $control.adjuntos eq '1'}selected="selected"{/if}>REQUIERE ADJUNTAR ARCHIVOS</option>
                    <option value="0" {if $control.adjuntos eq '0'}selected="selected"{/if}>NO REQUIERE ADJUNTAR ARCHIVOS</option>
                </select>
</div>
<div class="col-md-4 form-group">
<label>Días alerta</label>
<input type="number" class="form-control" value="{$control.alerta}" name="alerta" autocomplete="off" required/>
</div>
</div>
<div class="row">
<div class="col-md-12 form-group">
<label>Estado</label>
<select name="estado" class="form-control">
                    {foreach key=key item=estado from=$array_estados}
                        <option value="{$key}" {if $control.estado eq $key}selected="selected"{/if}>{$estado}</option>
                    {/foreach}                
                </select> 
</div>
</div>    
        </div>
      
    </div>
    
    <div class="row">
    <div class="col-md-offset-6 col-md-6">
    <div class="row">
<div class="col-sm-6">
<a href="index.php?com=control" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="id" value="{$control.id}" />
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
                <script>
$(function(){

$('form input[name=fecha]').datepicker({ minDate: new Date('{$fechamin}'), maxDate: new Date('{$fechamax}'), changeMonth: false });
$('body').on('keypress', 'form input[name=fecha]', function(e){
return false;
});

});
</script>