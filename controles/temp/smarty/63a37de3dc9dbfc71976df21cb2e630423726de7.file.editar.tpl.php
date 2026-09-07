<?php /* Smarty version Smarty-3.1.8, created on 2024-03-27 10:40:36
         compiled from "template/componentes/com_manual/vista/editar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:582022746660421d4412497-78968151%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '63a37de3dc9dbfc71976df21cb2e630423726de7' => 
    array (
      0 => 'template/componentes/com_manual/vista/editar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '582022746660421d4412497-78968151',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'manual' => 0,
    'contratos' => 0,
    'contrato' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_660421d44466b8_21630763',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_660421d44466b8_21630763')) {function content_660421d44466b8_21630763($_smarty_tpl) {?><div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Manuales</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Manuales</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=manual&amp;accion=grabar" enctype="multipart/form-data">
    
    <fieldset>
    
    <legend>Editar Manual</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Nombre</label>
<input type="text" name="nombre" maxlength="255" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['manual']->value['nombre'];?>
" autocomplete="off" required/>
</div>

<div class="row">
<div class="form-group col-md-6">
<input type="file" name="archivo" class="form-control"/>
</div>
<div class="form-group col-md-6">
<a href="index.php?com=manual&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['manual']->value['id'];?>
" class="btn btn-block btn-default" title="Descargar archivo"><i class="fa fa-file"></i> Descargar</a>
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Fecha</label>
<input type="text" name="fecha" class="form-control" autocomplete="off" value="<?php echo $_smarty_tpl->tpl_vars['manual']->value['fecha'];?>
" required/>
</div>
<div class="form-group col-md-6">
<label>Versión</label>
<input type="text" name="version" maxlength="255" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['manual']->value['version'];?>
" autocomplete="off" required/>
</div>
</div>

        </div>
        
        <div class="col-md-6">
        <div class="form-group">
<label>Planificación</label>
<select name="contrato" class="form-control">
<option value="" <?php if (''==$_smarty_tpl->tpl_vars['manual']->value['contrato']){?>selected="selected"<?php }?>>Sin planificación</option>
<?php  $_smarty_tpl->tpl_vars['contrato'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['contrato']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contratos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['contrato']->key => $_smarty_tpl->tpl_vars['contrato']->value){
$_smarty_tpl->tpl_vars['contrato']->_loop = true;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['contrato']->value['id']==$_smarty_tpl->tpl_vars['manual']->value['contrato']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['contrato']->value['nombre'];?>
</option>
<?php } ?>
</select>
</div>
        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion"><?php echo $_smarty_tpl->tpl_vars['manual']->value['descripcion'];?>
</textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=manual" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['manual']->value['id'];?>
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
$('form input[name=fecha]').datepicker();
$('body').on('keypress', 'form input[name=fecha]', function(e){
return false;
});
});
</script><?php }} ?>