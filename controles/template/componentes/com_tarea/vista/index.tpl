        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Mis Controles</h4>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-list"></i> Mis Controles</div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">#</th>
                <th class="text-center">item</th>
                <th class="text-center">capítulo</th>
                <th class="text-center">actividad</th>
                <th class="text-center">fecha comprometida</th>
                <th class="text-center">estado</th>
</tr>

</thead>
<tbody class="text-center">

{foreach item=tarea key=key from=$tareas}
            
            <tr>
                <td style="width: 5%; text-align: center;">{$key + 1}</td>
                <td>{$tarea.capitulo}</td>
                <td><strong>{$tarea.capitulo_padre}</strong></td>
                <td><a href="index.php?com=control&amp;accion=detalle&amp;id={$tarea.id}">{$tarea.nombre}</a></td> 
                <td>{$tarea.fecha}</td>
                <td><a href="index.php?com=control&amp;accion=detalle&amp;id={$tarea.id}" title="{$tarea.estado_glosa}" data-toggle="tooltip" class="capitulo"><img src="template/imagenes/estado_{$tarea.estado}.png" alt="{$tarea.estado_glosa}" /></a></td>
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
 { "bSearchable": false, "aTargets": [ 5 ] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div>