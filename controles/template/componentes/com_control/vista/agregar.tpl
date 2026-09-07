<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Controles</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-tasks"></i> Controles</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=control&amp;accion=grabar">
    
    <fieldset>
    
    <legend>Nuevo Punto de Control</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Actividad</label>
                <select name="actividad" class="form-control">
                    {foreach item=value from=$actividades}
                                                            {if $actividad neq ''}
                                                                <option value="{$value.id}" {if $actividad eq $value.id}selected{/if}>{$value.capitulo} - {$value.nombre}</option>
                                                            {else}
                        <option value="{$value.id}">{$value.capitulo} - {$value.nombre}</option>
                                                            {/if}
                    {/foreach}
                </select>
</div>
        </div>
        
        <div class="col-md-6">
        <div class="row">
<div class="col-md-4 form-group">
<label>Fecha</label>
<input type="text" class="form-control" name="fecha" autocomplete="off" required/>
</div>
<div class="col-md-4 form-group">
<label>Adjuntos</label>
<select name="adjuntos" class="form-control">
    <option value="1">REQUIERE ADJUNTAR ARCHIVOS</option>
                    <option value="0">NO REQUIERE ADJUNTAR ARCHIVOS</option>
                </select>
</div>
<div class="col-md-4 form-group">
<label>Días alerta</label>
<input type="number" class="form-control" name="alerta" autocomplete="off" required/>
</div>
</div>    
        </div>
      
    </div>
    <div class="row">
    <div class="form-group col-md-12">
    <label>Programar Punto de Control en otros Meses</label>
    <div class="row">
    <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="01" /> ENE
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="02" /> FEB
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="03" /> MAR
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="04" /> ABR
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="05" /> MAY
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="06" /> JUN
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="07" /> JUL
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="08" /> AGO
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="09" /> SEP
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="10" /> OCT
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="11" /> NOV
    </label>
    </div>
             </div>
             <div class="col-sm-1">
    <div class="checkbox">
<label>
    <input type="checkbox" name="mes[]" value="12" /> DIC
    </label>
    </div>
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

obtenerActividad();

$('form select[name=actividad]').change(function(e){
obtenerActividad();
});

$('form input[name=fecha]').datepicker();
$('body').on('keypress', 'form input[name=fecha]', function(e){
return false;
});

$('body').on('change', 'form input[name=fecha], form select[name=actividad]', function(e){

verificarControl();

var fecha = $(e.currentTarget).val();
fecha = fecha.split('-');
var mes = fecha[1];

$('form input[type=checkbox]').prop('checked', false);
$('form input[type=checkbox]').unbind('click');
$('form input[type=checkbox][value='+  mes  +']').prop('checked', true);
$('form input[type=checkbox][value='+  mes  +']').click(function(e){
return false;
});
});

function obtenerActividad()
{
$.ajax({
url : 'componentes/com_control/helpers/actividad.php',
type : 'get',
dataType : 'json',
data : {
'id' : $('form select[name=actividad] option:selected').val()
},
statusCode : {
200 : function(data)
{
$('form input[name=alerta]').val(data.alerta);
}
} 
});
}

function verificarControl()
{
$.ajax({
url : 'componentes/com_control/helpers/control.php',
type : 'get',
dataType : 'json',
data : {
'actividad' : $('form select[name=actividad] option:selected').val(),
'fecha' : $('form input[name=fecha]').val()
},
statusCode : {
200 : function()
{
return true;
},
406 : function(data)
{
alert('El mes seleccionado ya posee un punto de control. Seleccione otro Mes.');
$('form input[name=fecha]').val('').focus();

return false;
}
} 
});
}
});
</script>