<?php /* Smarty version Smarty-3.1.8, created on 2023-09-15 09:46:40
         compiled from "template/componentes/com_control/vista/editar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12303329805c9e610db880e9-87761638%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6d35475646e2fc4f1da104c7964660e674b6369' => 
    array (
      0 => 'template/componentes/com_control/vista/editar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12303329805c9e610db880e9-87761638',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5c9e610dc8c835_65125535',
  'variables' => 
  array (
    'actividades' => 0,
    'value' => 0,
    'control' => 0,
    'array_estados' => 0,
    'key' => 0,
    'estado' => 0,
    'fechamin' => 0,
    'fechamax' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9e610dc8c835_65125535')) {function content_5c9e610dc8c835_65125535($_smarty_tpl) {?><div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Punto de Control</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-tasks"></i> Punto de Control</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=control&amp;accion=grabar">
    
    <fieldset>
    
    <legend>Editar Punto de Control</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Actividad</label>
                <select name="actividad" class="form-control" disabled="disabled">
                    <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['actividades']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['control']->value['actividad']==$_smarty_tpl->tpl_vars['value']->value['id']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['value']->value['capitulo'];?>
 - <?php echo $_smarty_tpl->tpl_vars['value']->value['nombre'];?>
</option>
                    <?php } ?>
                </select>
</div>
        </div>
        
        <div class="col-md-6">
        <div class="row">
<div class="col-md-4 form-group">
<label>Fecha</label>
                                                        <input type="text" class="form-control" name="fecha" value="<?php echo $_smarty_tpl->tpl_vars['control']->value['fecha'];?>
" autocomplete="off" required />
</div>
<div class="col-md-4 form-group">
<label>Adjuntos</label>
<select name="adjuntos" class="form-control">
    <option value="1" <?php if ($_smarty_tpl->tpl_vars['control']->value['adjuntos']=='1'){?>selected="selected"<?php }?>>REQUIERE ADJUNTAR ARCHIVOS</option>
                    <option value="0" <?php if ($_smarty_tpl->tpl_vars['control']->value['adjuntos']=='0'){?>selected="selected"<?php }?>>NO REQUIERE ADJUNTAR ARCHIVOS</option>
                </select>
</div>
<div class="col-md-4 form-group">
<label>Días alerta</label>
<input type="number" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['control']->value['alerta'];?>
" name="alerta" autocomplete="off" required/>
</div>
</div>
<div class="row">
<div class="col-md-12 form-group">
<label>Estado</label>
<select name="estado" class="form-control">
                    <?php  $_smarty_tpl->tpl_vars['estado'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['estado']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['array_estados']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['estado']->key => $_smarty_tpl->tpl_vars['estado']->value){
$_smarty_tpl->tpl_vars['estado']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['estado']->key;
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['control']->value['estado']==$_smarty_tpl->tpl_vars['key']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['estado']->value;?>
</option>
                    <?php } ?>                
                </select> 
</div>
</div>    
        </div>
      
    </div>
    
    <div class="row">
    <div class="col-md-offset-6 col-md-6">
    <div class="row">
<div class="col-sm-6">
<a href="index.php?com=control" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['control']->value['id'];?>
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

$('form input[name=fecha]').datepicker({ minDate: new Date('<?php echo $_smarty_tpl->tpl_vars['fechamin']->value;?>
'), maxDate: new Date('<?php echo $_smarty_tpl->tpl_vars['fechamax']->value;?>
'), changeMonth: false });
$('body').on('keypress', 'form input[name=fecha]', function(e){
return false;
});

});
</script><?php }} ?>