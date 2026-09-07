<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Actividades</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=actividad&amp;accion=agregar" class="btn btn-info"><i class="fa fa-plus"></i> Nueva Actividad</a>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-tasks"></i> Actividades<a href="index.php?com=actividad&amp;accion=agregar" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nueva Actividad</a></div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="row">
        
        <div class="col-md-3">
        <div class="btn-group btn-group-sm btn-group-justified">
<div class="btn-group">
<a href="#" title="Expandir todo" class="btn-expandir btn btn-default btn-block btn-sm"><i class="fa fa-plus-square"></i> Expandir todo</a>
</div>
<div class="btn-group">
<a href="#" title="Contraer todo" class="btn-contraer btn btn-default btn-block btn-sm"><i class="fa fa-minus-square"></i> Contraer todo</a>
</div>
</div>
        </div>
        
        <div class="col-md-4 col-md-offset-5">
        <form action="index.php">
        <fieldset>
        <div class="form-group">
        <select class="form-control input-sm" name="contrato">
        {foreach item=value from=$contratos}
        <option value="{$value.id}" {if $contrato eq $value.id}selected="selected"{/if}>{$value.nombre}</option>
        {/foreach}
        </select>
        </div>
        <input type="hidden" name="com" value="actividad" />
        </fieldset>
        </form>
        </div>
        
        </div>

        <div class="table-responsive">

{$actividades}

</div>
        </div>
</div>
    </div>
</div>
<script>

$(function(){

{if $map1}

var map1 = [{$map1}];

{literal}
        var options1 = {openImg: "template/imagenes/tv-collapsable.gif", shutImg: "template/imagenes/tv-expandable.gif", leafImg: "template/imagenes/tv-item.gif", lastOpenImg: "template/imagenes/tv-collapsable-last.gif", lastShutImg: "template/imagenes/tv-expandable-last.gif", lastLeafImg: "template/imagenes/tv-item-last.gif", vertLineImg: "template/imagenes/vertline.gif", blankImg: "template/imagenes/blank.gif", collapse: false, column: 1, striped: false, highlight: true, state:true};
{/literal}

$('#treeTableActividad').jqTreeTable(map1, options1);


{/if}


$('form select[name=contrato]').change(function(e){
    $('form').submit();
});

});
</script>