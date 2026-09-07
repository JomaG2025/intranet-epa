<?php /* Smarty version Smarty-3.1.8, created on 2023-12-07 10:14:10
         compiled from "template/componentes/com_usuario/vista/editar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1710636935ca21feed14a69-42884004%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3b3edc43741cd48730783f41b3b73e876276a5c5' => 
    array (
      0 => 'template/componentes/com_usuario/vista/editar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1710636935ca21feed14a69-42884004',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca21feedd0784_91613755',
  'variables' => 
  array (
    'usuario' => 0,
    'array_usuarios' => 0,
    'key' => 0,
    'tipo' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca21feedd0784_91613755')) {function content_5ca21feedd0784_91613755($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
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
<select name="usuario" class="form-control" disabled>
<option value="<?php echo $_smarty_tpl->tpl_vars['usuario']->value['usuario'];?>
"><?php echo $_smarty_tpl->tpl_vars['usuario']->value['usuario'];?>
</option>
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
" <?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['usuario']->value['tipo']){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['tipo']->value;?>
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
<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
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
</div><?php }} ?>