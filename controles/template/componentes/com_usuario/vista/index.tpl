        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=usuario&amp;accion=agregar" class="btn btn-info" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Usuario</a>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios<a href="index.php?com=usuario&amp;accion=agregar" class="btn btn-info btn-xs pull-right" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Usuario</a></div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">id</th>
<th class="text-center">Usuario</th>
<th class="text-center">Privilegio</th>
<th class="text-center">Estado</th>
<th class="text-center"></th>
<th class="text-center"></th>

</tr>

</thead>
<tbody class="text-center">

{foreach item=usuario from=$usuarios}
            
            <tr>
<td style="width: 5%;">{$usuario.id}</td>
<td><a href="index.php?com=usuario&amp;accion=editar&amp;id={$usuario.id}">{$usuario.usuario}</a></td>
<td>{$usuario.tipo_nombre}</td>
<td><a href="index.php?com=usuario&amp;accion=activo&amp;id={$usuario.id}" class="btn btn-sm btn-block btn-{if $usuario.activo eq 1}success{else}danger{/if}">{if $usuario.activo eq 1}<i class="fa fa-check"></i> Activo {else} <i class="fa fa-times"></i> Inactivo{/if}</a></td>
<td><a href="index.php?com=usuario&amp;accion=editar&amp;id={$usuario.id}" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
<td><a href="index.php?com=usuario&amp;accion=eliminar&amp;id={$usuario.id}" class="btn btn-sm btn-block btn-danger btn-eliminar" title=""><i class="fa fa-trash-o"></i> Eliminar</a></td>
            </tr>
            
            {/foreach}

</tbody>

</table>

<script>
    $(function(){
        $('.table').dataTable({
         bStateSave : true,      
      fnStateSave :function(settings,data){
        localStorage.setItem("dataTables_state", JSON.stringify(data));
      },
      fnStateLoad: function(settings) {
        return JSON.parse(localStorage.getItem("dataTables_state"));
      },
     'aoColumnDefs': [
 { "bSortable": false, "aTargets": [ 4, 5 ] },
 { "bSearchable": false, "aTargets": [ 0, 3, 4, 5] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div>