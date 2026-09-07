        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Manuales</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=manual&amp;accion=agregar" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Manual</a>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Manuales<a href="index.php?com=manual&amp;accion=agregar" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Manual</a></div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">id</th>
<th class="text-center">Nombre</th>
<th class="text-center">Contrato</th>
<th class="text-center">Fecha</th>
<th class="text-center">Versión</th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
</tr>

</thead>
<tbody class="text-center">

{foreach item=manual from=$manuales}

<tr>
<td style="width: 5%;">{$manual.id}</td>
<td><a href="index.php?com=manual&amp;accion=editar&amp;id={$manual.id}">{$manual.nombre}</a></td>
<td>{$manual.nombre_contrato}</td>
<td>{$manual.fecha}</td>
<td>{$manual.version}</td>
<td><a href="index.php?com=manual&amp;accion=descargar&amp;id={$manual.id}" class="btn btn-sm btn-block btn-default"><i class="fa fa-file"> </i> Descargar</a></td>
<td><a href="index.php?com=manual&amp;accion=editar&amp;id={$manual.id}" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
<td><a href="index.php?com=manual&amp;accion=eliminar&amp;id={$manual.id}" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>
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
 { "bSortable": false, "aTargets": [ 5, 6, 7 ] },
 { "bSearchable": false, "aTargets": [ 0, 5, 6, 7] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div>