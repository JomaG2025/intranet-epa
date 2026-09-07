        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Responsabilidades</h4>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Responsabilidades</div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">id</th>
<th class="text-center">Usuario</th>
<th class="text-center"></th>
</tr>

</thead>
<tbody class="text-center">

{foreach item=usuario from=$usuarios}
            
            <tr>
                <td style="width: 5%; text-align: center;">{$usuario.id}</td>
                <td><a href="index.php?com=privilegio&amp;accion=editar&amp;usuario={$usuario.id}">{$usuario.usuario}</a></td> 
                <td style="width: 10%;"><a href="index.php?com=privilegio&amp;accion=editar&amp;usuario={$usuario.id}" class="btn btn-sm btn-block btn-warning"><i class="fa fa-key"></i> Editar</a></td>
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
 { "bSortable": false, "aTargets": [ 2 ] },
 { "bSearchable": false, "aTargets": [ 0, 2] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div>