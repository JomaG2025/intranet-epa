<?php /* Smarty version Smarty-3.1.8, created on 2026-07-10 11:48:37
         compiled from "template/componentes/com_control/vista/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3309538505c9e1102c5dff3-97031992%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c4f4a3877e6ce1c12f1d367e3697383e1dbe5616' => 
    array (
      0 => 'template/componentes/com_control/vista/index.tpl',
      1 => 1783698510,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3309538505c9e1102c5dff3-97031992',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5c9e1102d82a60_92361966',
  'variables' => 
  array (
    'SESSION_TIPO' => 0,
    'ver' => 0,
    'anos' => 0,
    'value' => 0,
    'ano' => 0,
    'contrato_fijo' => 0,
    'contrato' => 0,
    'contrato_nombre' => 0,
    'contratos' => 0,
    'controles' => 0,
    'map1' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9e1102d82a60_92361966')) {function content_5c9e1102d82a60_92361966($_smarty_tpl) {?><div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Controles</h4>
</div>
<?php if ($_smarty_tpl->tpl_vars['SESSION_TIPO']->value=='ADM'){?>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=control&amp;accion=agregar" class="btn btn-info pull-right" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Control</a>
</div>
<?php }?>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-tasks"></i> Controles<?php if ($_smarty_tpl->tpl_vars['SESSION_TIPO']->value=='ADM'){?><a href="index.php?com=control&amp;accion=agregar" class="btn btn-info btn-xs pull-right" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Control</a><?php }?></div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="row">
        
        <div class="col-md-6">
                                    <label>&nbsp;</label>
        <div class="btn-group btn-group-sm btn-group-justified">
<div class="btn-group">
<a href="index.php?com=control&amp;ver=ano" title="Ver por año" class="btn btn-default btn-block btn-sm <?php if ($_smarty_tpl->tpl_vars['ver']->value=='ano'){?>active<?php }?>"><i class="fa fa-calendar"></i> <span class="hidden-xs">Ver por año</span><span class="visible-xs">Año</span></a>
</div>
<div class="btn-group">
<a href="index.php?com=control&amp;ver=mes" title="Ver por mes actual" class="btn btn-default btn-block btn-sm <?php if ($_smarty_tpl->tpl_vars['ver']->value=='mes'){?>active<?php }?>"><i class="fa fa-calendar"></i> <span class="hidden-xs">Ver por mes</span><span class="visible-xs">Mes</span></a>
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
<div class="form-group col-md-8">
                                                    <label>Planificación a consultar</label>
        <?php if ($_smarty_tpl->tpl_vars['contrato_fijo']->value){?>
        <input type="hidden" name="contrato" value="<?php echo $_smarty_tpl->tpl_vars['contrato']->value;?>
">
        <input type="text" class="form-control input-sm" value="<?php echo $_smarty_tpl->tpl_vars['contrato_nombre']->value;?>
" disabled="disabled">
        <?php }else{ ?>
        <select class="form-control input-sm" name="contrato">
        <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contratos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                                                            <?php if ($_smarty_tpl->tpl_vars['value']->value['activo']=='1'){?>
        <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['contrato']->value==$_smarty_tpl->tpl_vars['value']->value['id']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['value']->value['nombre'];?>
</option>
                                                            <?php }?>
        <?php } ?>
        </select>
        <?php }?>
        </div>
        </div>
        <input type="hidden" name="com" value="control" />
        </fieldset>
        </form>
        </div>
        
        </div>

        <div class="table-responsive">

<?php echo $_smarty_tpl->tpl_vars['controles']->value;?>


</div>
        </div>
</div>
    </div>
</div>
<script>

$(function(){

<?php if ($_smarty_tpl->tpl_vars['map1']->value){?>

var map1 = [<?php echo $_smarty_tpl->tpl_vars['map1']->value;?>
];


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


$('#treeTable').jqTreeTable(map1, options1);


<?php }?>


$('form select[name=ano], form select[name=contrato]').change(function(e){
    $('form').submit();
});

});

</script><?php }} ?>