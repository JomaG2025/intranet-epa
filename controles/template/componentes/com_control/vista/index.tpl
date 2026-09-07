<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Controles</h4>
</div>
{if $SESSION_TIPO eq 'ADM'}
<div class="navbar-action hidden-xs">
    <a href="index.php?com=control&amp;accion=agregar" class="btn btn-info pull-right" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Control</a>
</div>
{/if}
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-tasks"></i> Controles{if $SESSION_TIPO eq 'ADM'}<a href="index.php?com=control&amp;accion=agregar" class="btn btn-info btn-xs pull-right" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Control</a>{/if}</div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="row">
        
        <div class="col-md-6">
                                    <label>&nbsp;</label>
        <div class="btn-group btn-group-sm btn-group-justified">
<div class="btn-group">
<a href="index.php?com=control&amp;ver=ano" title="Ver por año" class="btn btn-default btn-block btn-sm {if $ver eq 'ano'}active{/if}"><i class="fa fa-calendar"></i> <span class="hidden-xs">Ver por año</span><span class="visible-xs">Año</span></a>
</div>
<div class="btn-group">
<a href="index.php?com=control&amp;ver=mes" title="Ver por mes actual" class="btn btn-default btn-block btn-sm {if $ver eq 'mes'}active{/if}"><i class="fa fa-calendar"></i> <span class="hidden-xs">Ver por mes</span><span class="visible-xs">Mes</span></a>
</div>
<div class="btn-group">
<a href="#" title="Expandir todo" class="btn-expandir btn btn-default btn-block btn-sm"><i class="fa fa-plus-square"></i> <span class="hidden-xs">Expandir todo</span><span class="visible-xs">Expandir</span></a>
</div>
<div class="btn-group">
<a href="#" title="Contraer todo" class="btn-contraer btn btn-default btn-block btn-sm"><i class="fa fa-minus-square"></i> <span class="hidden-xs">Contraer todo</span><span class="visible-xs">Contraer</span></a>
</div>
</div>
        </div>
        
        <p class="clearfix visible-xs visible-sm">&nbsp;</p>
        
        <div class="col-md-6">
        <form action="index.php">
        <fieldset>
        <div class="row">
        <div class="form-group col-md-4">
                                                    <label>Año a consultar</label>
        <select class="form-control input-sm" name="ano">
        {foreach item=value from=$anos}
        <option value="{$value}" {if $ano eq $value}selected="selected"{/if}>{$value}</option>
        {/foreach}
        </select>
        </div>
<div class="form-group col-md-8">
                                                    <label>Planificación a consultar</label>
        {if $contrato_fijo}
        <input type="hidden" name="contrato" value="{$contrato}">
        <input type="text" class="form-control input-sm" value="{$contrato_nombre}" disabled="disabled">
        {else}
        <select class="form-control input-sm" name="contrato">
        {foreach item=value from=$contratos}
                                                            {if $value.activo eq '1'}
        <option value="{$value.id}" {if $contrato eq $value.id}selected="selected"{/if}>{$value.nombre}</option>
                                                            {/if}
        {/foreach}
        </select>
        {/if}
        </div>
        </div>
        <input type="hidden" name="com" value="control" />
        </fieldset>
        </form>
        </div>
        
        </div>

        <div class="table-responsive">

{$controles}

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
        var options1 = {
                                openImg: "template/imagenes/tv-collapsable.gif", 
                                shutImg: "template/imagenes/tv-expandable.gif", 
                                leafImg: "template/imagenes/tv-item.gif", 
                                lastOpenImg: "template/imagenes/tv-collapsable-last.gif", 
                                lastShutImg: "template/imagenes/tv-expandable-last.gif", 
                                lastLeafImg: "template/imagenes/tv-item-last.gif", 
                                vertLineImg: "template/imagenes/vertline.gif", 
                                blankImg: "template/imagenes/blank.gif", 
                                collapse: false, 
                                column: 1, 
                                striped: false, 
                                highlight: true, 
                                state:true,
                                initialState:'expanded'
                        };
{/literal}

$('#treeTable').jqTreeTable(map1, options1);


{/if}


$('form select[name=ano], form select[name=contrato]').change(function(e){
    $('form').submit();
});

});

</script>