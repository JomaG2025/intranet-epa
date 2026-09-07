<?php /* Smarty version Smarty-3.1.8, created on 2024-02-05 17:08:48
         compiled from "template/componentes/com_contrato/vista/agregar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21457998785cdb354f1bd566-81383050%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3bd7a155b47f995c38f8823271c4536ec7219eca' => 
    array (
      0 => 'template/componentes/com_contrato/vista/agregar.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21457998785cdb354f1bd566-81383050',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5cdb354f20a883_07495045',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cdb354f20a883_07495045')) {function content_5cdb354f20a883_07495045($_smarty_tpl) {?><div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Planificaciones</h4>
</div>
<div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Planificaciones</div>
<div class="panel-body">

        <div class="row">

        <div class="col-lg-12">
        
  <form method="post" action="index.php?com=contrato&amp;accion=grabar" enctype="multipart/form-data">
    
    <fieldset>
    
    <legend>Nueva Planificación</legend>
    
    <div class="row">
    
    <div class="col-md-6">

<div class="form-group">
<label>Nombre</label>
<input type="text" name="nombre" maxlength="255" class="form-control" autocomplete="off" required/>
</div>

<div class="form-group">
<label>Archivo</label>
<input type="file" name="archivo" class="form-control"/>
</div>
      
        </div>
        
        <div class="col-md-6">
        <div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="5" name="descripcion"></textarea>
</div>
<div class="row">
<div class="col-sm-6">
<a href="index.php?com=contrato" class="btn btn-default btn-block btn-cancelar" title="Cancelar"><i class="fa fa-reply"></i> Cancelar</a>
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