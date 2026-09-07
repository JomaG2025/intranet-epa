<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Manuales</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Manuales</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=manual&amp;accion=grabar" enctype="multipart/form-data">
    
    <fieldset>
    
    <legend>Nuevo Manual</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Nombre</label>
<input type="text" name="nombre" maxlength="255" class="form-control" autocomplete="off" required/>
</div>

<div class="form-group">
<label>Archivo</label>
<input type="file" name="archivo" class="form-control" required />
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Fecha</label>
<input type="text" name="fecha" class="form-control" autocomplete="off" required/>
</div>
<div class="form-group col-md-6">
<label>Versión</label>
<input type="text" name="version" maxlength="255" class="form-control" autocomplete="off" required/>
</div>
</div>

        </div>
        
        <div class="col-md-6">
        <div class="form-group">
<label>Planificación</label>
<select name="contrato" class="form-control">
<option value="">Sin planificación</option>
{foreach item=contrato from=$contratos}
<option value="{$contrato.id}">{$contrato.nombre}</option>
{/foreach}
</select>
</div>
        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion"></textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=manual" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
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
$('form input[name=fecha]').datepicker();
$('body').on('keypress', 'form input[name=fecha]', function(e){
return false;
});
});
</script>