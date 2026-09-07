<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Planificaciones</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Planificaciones</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=contrato&amp;accion=grabar" enctype="multipart/form-data">
    
    <fieldset>
    
    <legend>Editar Planificación</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Nombre</label>
<input type="text" name="nombre" maxlength="255" class="form-control" value="{$contrato.nombre}" autocomplete="off" required/>
</div>

<div class="form-group">
<label>Archivo</label>
{if $contrato.archivo}
<div class="row">
<div class="col-md-6">
<input type="file" name="archivo" class="form-control"/>
</div>
<div class="col-md-6">
<a href="index.php?com=contrato&amp;accion=descargar&amp;id={$contrato.id}" class="btn btn-block btn-default" title="Descargar archivo"><i class="fa fa-file"></i> Descargar</a>
</div>
</div>
{else}
<input type="file" name="archivo" class="form-control"/>
{/if}
</div>
      
        </div>
        
        <div class="col-md-6">
        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion">{$contrato.descripcion}</textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=contrato" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="id" value="{$contrato.id}" />
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