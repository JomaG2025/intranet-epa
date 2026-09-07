<?php /* Smarty version Smarty-3.1.8, created on 2024-01-16 11:07:55
         compiled from "template/componentes/com_actividad/vista/editar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6312609545c9e0714348660-00501184%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '491b45bc15d764d1174ecc706861c99f082352a2' => 
    array (
      0 => 'template/componentes/com_actividad/vista/editar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6312609545c9e0714348660-00501184',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5c9e071446cd91_48967273',
  'variables' => 
  array (
    'actividad' => 0,
    'contratos' => 0,
    'contrato' => 0,
    'grupos' => 0,
    'value' => 0,
    'manuales' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9e071446cd91_48967273')) {function content_5c9e071446cd91_48967273($_smarty_tpl) {?><div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Actividades</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Actividades</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=actividad&amp;accion=grabar">
    
    <fieldset>
    
    <legend>Editar Actividad</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Estado</label>
<select name="activo" class="form-control">
                    <option value="1" <?php if ($_smarty_tpl->tpl_vars['actividad']->value['activo']==1){?>selected="selected"<?php }?>>Activo</option>
                    <option value="0" <?php if ($_smarty_tpl->tpl_vars['actividad']->value['activo']==0){?>selected="selected"<?php }?>>Inactivo</option>
                </select>
</div>

<div class="row">
<div class="col-md-6 form-group">
<label>Capítulo</label>
<input type="text" class="form-control" name="capitulo" pattern="[a-zA-Z0-9.-*]+" autocomplete="off" value="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['capitulo'];?>
" required/>
</div>
<div class="col-md-6 form-group">
<br />
<div class="checkbox">
<label>
    <input type="checkbox" name="padre" value="1" <?php if ($_smarty_tpl->tpl_vars['actividad']->value['padre']==1){?>checked="checked"<?php }?>  /> Capítulo Padre
    </label>
</div>
</div>
</div>

<div class="row">
<div class="col-md-6 form-group">
<label>Días alerta</label>
<input type="number" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['alerta'];?>
" name="alerta" autocomplete="off" required/>
</div>

<div class="col-md-6 form-group">
<label>Planificación</label>
<select name="contrato" class="form-control" disabled="disabled">
                <?php  $_smarty_tpl->tpl_vars['contrato'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['contrato']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contratos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['contrato']->key => $_smarty_tpl->tpl_vars['contrato']->value){
$_smarty_tpl->tpl_vars['contrato']->_loop = true;
?>
                
                <option value="<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['actividad']->value['contrato']==$_smarty_tpl->tpl_vars['contrato']->value['id']){?>selected="selected"<?php }?> ><?php echo $_smarty_tpl->tpl_vars['contrato']->value['nombre'];?>
</option>
                
                <?php } ?>
                
</select>
</div>
</div>
        </div>
        
        <div class="col-md-6">
        <div class="form-group">
        <label>Nombre</label>
        <input type="text" class="form-control" name="nombre" maxlength="255" autocomplete="off" value="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['nombre'];?>
" required/>
        </div>
        <div class="row">
<div class="col-md-6 form-group">
<label>Grupo</label>
<select name="grupo" class="form-control">
                    <option value="" <?php if (''==$_smarty_tpl->tpl_vars['actividad']->value['grupo']){?> selected="selected" <?php }?>>Sin grupo</option>
                    <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['grupos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['value']->value['id']==$_smarty_tpl->tpl_vars['actividad']->value['grupo']){?> selected="selected" <?php }?>><?php echo $_smarty_tpl->tpl_vars['value']->value['nombre'];?>
</option>
                    <?php } ?>
                </select>
</div>

<div class="col-md-6 form-group">
<label>Manual</label>
<select name="manual" class="form-control">
                    <option value="" <?php if (''==$_smarty_tpl->tpl_vars['actividad']->value['manual']){?> selected="selected" <?php }?>>Sin manual</option>
                    <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['manuales']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['value']->value['id']==$_smarty_tpl->tpl_vars['actividad']->value['manual']){?> selected="selected" <?php }?>><?php echo $_smarty_tpl->tpl_vars['value']->value['nombre'];?>
 - <?php echo $_smarty_tpl->tpl_vars['value']->value['version'];?>
</option>
                    <?php } ?>
                </select>   
</div>
</div>

        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion"><?php echo $_smarty_tpl->tpl_vars['actividad']->value['descripcion'];?>
</textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=actividad" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['id'];?>
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