<?php /* Smarty version Smarty-3.1.8, created on 2023-06-28 17:12:57
         compiled from "template/componentes/com_usuario/vista/agregar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12534026405ca222c3df6881-12620650%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fcc6f69702ba5ff5de705506cb02ebac5bd7bbf2' => 
    array (
      0 => 'template/componentes/com_usuario/vista/agregar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12534026405ca222c3df6881-12620650',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca222c3ea3966_83574806',
  'variables' => 
  array (
    'usuarios' => 0,
    'usuario' => 0,
    'array_usuarios' => 0,
    'key' => 0,
    'tipo' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca222c3ea3966_83574806')) {function content_5ca222c3ea3966_83574806($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
    <form method="post" action="index.php?com=usuario&amp;accion=grabar">
    
    <fieldset>
    
    <legend>Nuevo Usuario</legend>
    
    <div class="row">
    
    <div class="col-md-6">
<div class="form-group">
<label>Usuario</label>
<select name="usuario" class="form-control">
<?php  $_smarty_tpl->tpl_vars['usuario'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['usuario']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['usuarios']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['usuario']->key => $_smarty_tpl->tpl_vars['usuario']->value){
$_smarty_tpl->tpl_vars['usuario']->_loop = true;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['usuario']->value['usuario'];?>
"><?php echo $_smarty_tpl->tpl_vars['usuario']->value['nombre'];?>
</option>
<?php } ?>
</select>
</div>             
        </div>
        
        <div class="col-md-6">
        <div class="form-group">
<label>Privilegio</label>
<select name="tipo" class="form-control">
<?php  $_smarty_tpl->tpl_vars['tipo'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tipo']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['array_usuarios']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tipo']->key => $_smarty_tpl->tpl_vars['tipo']->value){
$_smarty_tpl->tpl_vars['tipo']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['tipo']->key;
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['tipo']->value;?>
</option>
                    <?php } ?>
</select>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=usuario" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
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
</div><?php }} ?>