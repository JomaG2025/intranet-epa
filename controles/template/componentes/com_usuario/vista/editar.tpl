        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
    <form method="post" action="index.php?com=usuario&amp;accion=grabar">
    
    <fieldset>
    
    <legend>Nuevo Usuario</legend>
    
    <div class="row">
    
    <div class="col-md-6">
<div class="form-group">
<label>Usuario</label>
<select name="usuario" class="form-control" disabled>
<option value="{$usuario.usuario}">{$usuario.usuario}</option>
</select>
</div>             
        </div>
        
        <div class="col-md-6">
        <div class="form-group">
<label>Privilegio</label>
<select name="tipo" class="form-control">
{foreach item=tipo key=key from=$array_usuarios}
                        <option value="{$key}" {if $key eq $usuario.tipo} selected="selected"{/if}>{$tipo}</option>
                    {/foreach}
</select>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=usuario" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="id" value="{$usuario.id}" />
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