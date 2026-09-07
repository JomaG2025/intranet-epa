<?php /* Smarty version Smarty-3.1.8, created on 2023-09-22 09:48:54
         compiled from "template/componentes/com_actividad/vista/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11250841395c9e07222afaf5-39049798%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8140b3d0b35491721f83eaf0b7db971f195ed589' => 
    array (
      0 => 'template/componentes/com_actividad/vista/index.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11250841395c9e07222afaf5-39049798',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5c9e072235e728_58810968',
  'variables' => 
  array (
    'contratos' => 0,
    'value' => 0,
    'contrato' => 0,
    'actividades' => 0,
    'map1' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9e072235e728_58810968')) {function content_5c9e072235e728_58810968($_smarty_tpl) {?><div class="navbar-title hidden-xs">
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
        <input type="hidden" name="com" value="actividad" />
        </fieldset>
        </form>
        </div>
        
        </div>

        <div class="table-responsive">

<?php echo $_smarty_tpl->tpl_vars['actividades']->value;?>


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


        var options1 = {openImg: "template/imagenes/tv-collapsable.gif", shutImg: "template/imagenes/tv-expandable.gif", leafImg: "template/imagenes/tv-item.gif", lastOpenImg: "template/imagenes/tv-collapsable-last.gif", lastShutImg: "template/imagenes/tv-expandable-last.gif", lastLeafImg: "template/imagenes/tv-item-last.gif", vertLineImg: "template/imagenes/vertline.gif", blankImg: "template/imagenes/blank.gif", collapse: false, column: 1, striped: false, highlight: true, state:true};


$('#treeTableActividad').jqTreeTable(map1, options1);


<?php }?>


$('form select[name=contrato]').change(function(e){
    $('form').submit();
});

});
</script><?php }} ?>