<?php /* Smarty version Smarty-3.1.8, created on 2024-01-10 16:54:05
         compiled from "template/componentes/com_contrato/vista/editar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8295037885cdb358d8acf42-78873477%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa6c3b5aa8f5cc4598f234c04e95a7e26205739d' => 
    array (
      0 => 'template/componentes/com_contrato/vista/editar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8295037885cdb358d8acf42-78873477',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5cdb358d93cc69_45776918',
  'variables' => 
  array (
    'contrato' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cdb358d93cc69_45776918')) {function content_5cdb358d93cc69_45776918($_smarty_tpl) {?><div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Planificaciones</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Planificaciones</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=contrato&amp;accion=grabar" enctype="multipart/form-data">
    
    <fieldset>
    
    <legend>Editar Planificación</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Nombre</label>
<input type="text" name="nombre" maxlength="255" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['contrato']->value['nombre'];?>
" autocomplete="off" required/>
</div>

<div class="form-group">
<label>Archivo</label>
<?php if ($_smarty_tpl->tpl_vars['contrato']->value['archivo']){?>
<div class="row">
<div class="col-md-6">
<input type="file" name="archivo" class="form-control"/>
</div>
<div class="col-md-6">
<a href="index.php?com=contrato&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
" class="btn btn-block btn-default" title="Descargar archivo"><i class="fa fa-file"></i> Descargar</a>
</div>
</div>
<?php }else{ ?>
<input type="file" name="archivo" class="form-control"/>
<?php }?>
</div>
      
        </div>
        
        <div class="col-md-6">
        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion"><?php echo $_smarty_tpl->tpl_vars['contrato']->value['descripcion'];?>
</textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=contrato" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
</div>
<p class="visible-xs"></p>
<div class="col-sm-6">
<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
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