        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Responsabilidades</h4>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Responsabilidades</div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
                            
                            <form class="form-filtro" action="index.php">
        
                                <div class="row">
                                    
                                    <fieldset>

                                        <div class="form-group col-md-2 col-md-offset-6">
                                            <label>Año a consultar</label>
                                            <select class="form-control input-sm" name="ano">
                                                {foreach item=value from=$anos}
                                                    <option value="{$value}" {if $ano eq $value}selected="selected"{/if}>{$value}</option>
                                                {/foreach}
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Planificación a consultar</label>
                                            <select class="form-control input-sm" name="contrato">
                                                {foreach item=value from=$contratos}
                                                    <option value="{$value.id}" {if $contrato eq $value.id}selected="selected"{/if}>{$value.nombre}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                                    
                                        <input type="hidden" name="com" value="privilegio" />
                                        <input type="hidden" name="accion" value="editar" />
                                        <input type="hidden" name="usuario" value="{$usuario.id}" />
                                        
                                    </fieldset>

                                </div>
                                
                            </form>
        
        <form action="index.php?com=privilegio&amp;accion=grabar" method="post">

<fieldset>

        <legend>Editar Responsabilidades {$usuario.usuario}</legend>
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th>APR</th>
<th>DIG</th>
<th>ALE</th>
<th>Capítulo</th>
<th>Actividad</th>

</tr>

</thead>
<tbody>
 {foreach item=actividad from=$actividades}
                    
                            <tr>
                                <td style="width: 3%; text-align: center;">
                                <input type="checkbox" name="{$actividad.id}[]" id="apr_{$actividad.capitulo_format}" value="apr" {if $actividad.apr eq '1'} checked="checked"{/if} title="APROBADOR"/>
                                </td>
                                <td style="width: 3%; text-align: center;">
                                <input type="checkbox" name="{$actividad.id}[]" id="dig_{$actividad.capitulo_format}" value="dig" {if $actividad.dig eq '1'} checked="checked"{/if} title="DIGITADOR"/>
                                </td>
                                <td style="width: 3%; text-align: center;">
                                <input type="checkbox" name="{$actividad.id}[]" id="ale_{$actividad.capitulo_format}" value="ale" {if $actividad.ale eq '1'} checked="checked"{/if} title="ALERTADO"/>
                                </td>
                                <td style="width: 15%;">{$actividad.capitulo}</td>
                                <td>{$actividad.nombre}</td>
                            </tr>
                            
                        {/foreach}    

</tbody>

</table>

</div>

<div class="row">
<div class="col-md-6 col-md-offset-6">
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=privilegio" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="usuario" value="{$usuario.id}" />
<input type="hidden" name="contrato" value="{$contrato}" />
<input type="hidden" name="ano" value="{$ano}" />
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
            
$('input[type=checkbox]').change(function(e){
if($(e.currentTarget).prop('checked'))
{
var id = $(e.currentTarget).attr('id');
                    var todos = $('input[type=checkbox][id^='+  id  + '_]');
todos.prop('checked', true);
}
});

$('.form-filtro select[name=ano], .form-filtro select[name=contrato]').change(function(e){
$('.form-filtro').submit();
});
});
</script>