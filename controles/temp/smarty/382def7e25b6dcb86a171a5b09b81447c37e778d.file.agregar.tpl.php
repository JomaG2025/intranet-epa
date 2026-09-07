<?php /* Smarty version Smarty-3.1.8, created on 2024-12-11 10:55:32
         compiled from "template/componentes/com_manual/vista/agregar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1541192691675999d46372f9-64737379%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '382def7e25b6dcb86a171a5b09b81447c37e778d' => 
    array (
      0 => 'template/componentes/com_manual/vista/agregar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1541192691675999d46372f9-64737379',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'contratos' => 0,
    'contrato' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_675999d465cc66_13127623',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_675999d465cc66_13127623')) {function content_675999d465cc66_13127623($_smarty_tpl) {?><div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Manuales</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Manuales</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=manual&amp;accion=grabar" enctype="multipart/form-data">
    
    <fieldset>
    
    <legend>Nuevo Manual</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Nombre</label>
<input type="text" name="nombre" maxlength="255" class="form-control" autocomplete="off" required/>
</div>

<div class="form-group">
<label>Archivo</label>
<input type="file" name="archivo" class="form-control" required />
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Fecha</label>
<input type="text" name="fecha" class="form-control" autocomplete="off" required/>
</div>
<div class="form-group col-md-6">
<label>Versión</label>
<input type="text" name="version" maxlength="255" class="form-control" autocomplete="off" required/>
</div>
</div>

        </div>
        
        <div class="col-md-6">
        <div class="form-group">
<label>Planificación</label>
<select name="contrato" class="form-control">
<option value="">Sin planificación</option>
<?php  $_smarty_tpl->tpl_vars['contrato'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['contrato']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contratos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['contrato']->key => $_smarty_tpl->tpl_vars['contrato']->value){
$_smarty_tpl->tpl_vars['contrato']->_loop = true;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['contrato']->value['nombre'];?>
</option>
<?php } ?>
</select>
</div>
        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion"></textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=manual" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
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