<?php /* Smarty version Smarty-3.1.8, created on 2023-06-13 15:53:55
         compiled from "template/componentes/com_privilegio/vista/editar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2862547085ca21ffee7cc97-88380703%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd50c518ebcc562a7b11c88916983980d87edd151' => 
    array (
      0 => 'template/componentes/com_privilegio/vista/editar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2862547085ca21ffee7cc97-88380703',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca21fff0c4a56_96787266',
  'variables' => 
  array (
    'anos' => 0,
    'value' => 0,
    'ano' => 0,
    'contratos' => 0,
    'contrato' => 0,
    'usuario' => 0,
    'actividades' => 0,
    'actividad' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca21fff0c4a56_96787266')) {function content_5ca21fff0c4a56_96787266($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
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
                                                <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['anos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                                                    <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['ano']->value==$_smarty_tpl->tpl_vars['value']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Planificación a consultar</label>
                                            <select class="form-control input-sm" name="contrato">
                                                <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contratos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                                                    <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['contrato']->value==$_smarty_tpl->tpl_vars['value']->value['id']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['value']->value['nombre'];?>
</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                                    
                                        <input type="hidden" name="com" value="privilegio" />
                                        <input type="hidden" name="accion" value="editar" />
                                        <input type="hidden" name="usuario" value="<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
" />
                                        
                                    </fieldset>

                                </div>
                                
                            </form>
        
        <form action="index.php?com=privilegio&amp;accion=grabar" method="post">

<fieldset>

        <legend>Editar Responsabilidades <?php echo $_smarty_tpl->tpl_vars['usuario']->value['usuario'];?>
</legend>
        
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
 <?php  $_smarty_tpl->tpl_vars['actividad'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['actividad']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['actividades']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['actividad']->key => $_smarty_tpl->tpl_vars['actividad']->value){
$_smarty_tpl->tpl_vars['actividad']->_loop = true;
?>
                    
                            <tr>
                                <td style="width: 3%; text-align: center;">
                                <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['id'];?>
[]" id="apr_<?php echo $_smarty_tpl->tpl_vars['actividad']->value['capitulo_format'];?>
" value="apr" <?php if ($_smarty_tpl->tpl_vars['actividad']->value['apr']=='1'){?> checked="checked"<?php }?> title="APROBADOR"/>
                                </td>
                                <td style="width: 3%; text-align: center;">
                                <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['id'];?>
[]" id="dig_<?php echo $_smarty_tpl->tpl_vars['actividad']->value['capitulo_format'];?>
" value="dig" <?php if ($_smarty_tpl->tpl_vars['actividad']->value['dig']=='1'){?> checked="checked"<?php }?> title="DIGITADOR"/>
                                </td>
                                <td style="width: 3%; text-align: center;">
                                <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['id'];?>
[]" id="ale_<?php echo $_smarty_tpl->tpl_vars['actividad']->value['capitulo_format'];?>
" value="ale" <?php if ($_smarty_tpl->tpl_vars['actividad']->value['ale']=='1'){?> checked="checked"<?php }?> title="ALERTADO"/>
                                </td>
                                <td style="width: 15%;"><?php echo $_smarty_tpl->tpl_vars['actividad']->value['capitulo'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['actividad']->value['nombre'];?>
</td>
                            </tr>
                            
                        <?php } ?>    

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
<input type="hidden" name="usuario" value="<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
" />
<input type="hidden" name="contrato" value="<?php echo $_smarty_tpl->tpl_vars['contrato']->value;?>
" />
<input type="hidden" name="ano" value="<?php echo $_smarty_tpl->tpl_vars['ano']->value;?>
" />
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
</script><?php }} ?>