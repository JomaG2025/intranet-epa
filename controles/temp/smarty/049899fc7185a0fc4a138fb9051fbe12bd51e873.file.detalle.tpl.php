<?php /* Smarty version Smarty-3.1.8, created on 2023-05-29 16:56:45
         compiled from "template/componentes/com_control/vista/detalle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14092927935c9e4dffc47548-06783049%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '049899fc7185a0fc4a138fb9051fbe12bd51e873' => 
    array (
      0 => 'template/componentes/com_control/vista/detalle.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14092927935c9e4dffc47548-06783049',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5c9e4e00131095_74390365',
  'variables' => 
  array (
    'actividad' => 0,
    'contrato' => 0,
    'control' => 0,
    'resultado' => 0,
    'responsables' => 0,
    'value' => 0,
    'SESSION_ID' => 0,
    'SESSION_TIPO' => 0,
    'adjuntos' => 0,
    'adjunto' => 0,
    'ultimo_adjunto' => 0,
    'manual' => 0,
    'registro' => 0,
    'array_meses' => 0,
    'mes' => 0,
    'grupo' => 0,
    'otros_adjuntos' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9e4e00131095_74390365')) {function content_5c9e4e00131095_74390365($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Punto de Control</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=control" class="btn btn-info" title="Volver"><i class="fa fa-caret-left"></i> Volver</a>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-tasks"></i> Punto de Control <a href="index.php?com=control" class="pull-right" title="Volver"><i class="fa fa-caret-left"></i> Volver</a></div>

<div class="panel-body">
        
        <div class="row">
        
        <div class="col-md-8">
        
        <h3 class="text-uppercase text-primary"><?php echo $_smarty_tpl->tpl_vars['actividad']->value['nombre'];?>
</h3>
                            
                            <ol class="breadcrumb">
                                <li><a href="index.php?com=control&amp;contrato=<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['contrato']->value['nombre'];?>
</a></li>
     <li>Capítulo <?php echo $_smarty_tpl->tpl_vars['actividad']->value['capitulo'];?>
</li>
     <li class="active"><?php echo $_smarty_tpl->tpl_vars['actividad']->value['nombre'];?>
</li>
</ol>
        
        <div class="alert alert-<?php echo $_smarty_tpl->tpl_vars['control']->value['tipoalerta'];?>
 alert-dismissible" role="alert">
<strong><?php echo $_smarty_tpl->tpl_vars['control']->value['nombre_estado'];?>
</strong> <?php echo $_smarty_tpl->tpl_vars['control']->value['descripcion_estado'];?>

<?php if ($_smarty_tpl->tpl_vars['control']->value['estado']=='APR'||$_smarty_tpl->tpl_vars['control']->value['estado']=='REC'){?>
<?php if ($_smarty_tpl->tpl_vars['resultado']->value['observacion']!=''){?>
                    <br />
                    Observaciones: <?php echo $_smarty_tpl->tpl_vars['resultado']->value['observacion'];?>

<?php }?>
                <?php }?>
</div>
                            
                            <?php if ($_smarty_tpl->tpl_vars['actividad']->value['descripcion']!=''){?>
                                <?php echo nl2br($_smarty_tpl->tpl_vars['actividad']->value['descripcion']);?>

                                <p>&nbsp;</p>
                            <?php }?>

            <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['responsables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
 <?php if ($_smarty_tpl->tpl_vars['value']->value['usuario']==$_smarty_tpl->tpl_vars['SESSION_ID']->value&&$_smarty_tpl->tpl_vars['value']->value['apr']=='1'){?>
                                    <?php if ($_smarty_tpl->tpl_vars['actividad']->value['descripcion']!=''){?>
                                        <a href="#" class="btn btn-info btn-xs btn-editar"><i class="fa fa-edit"></i> Editar observación</a>
                                    <?php }else{ ?>
                                        <a href="#" class="btn btn-info btn-xs btn-editar"><i class="fa fa-edit"></i> Agregar observación</a>
                                    <?php }?>
                                    <p class="clearfix">&nbsp;</p>
 <?php }?>
            <?php } ?>
                            
                            <?php if ($_smarty_tpl->tpl_vars['SESSION_TIPO']->value=='ADM'){?>
                                <?php if ($_smarty_tpl->tpl_vars['actividad']->value['descripcion']!=''){?>
                                    <a href="#" class="btn btn-info btn-xs btn-editar"><i class="fa fa-edit"></i> Editar observación</a>
                                <?php }else{ ?>
                                    <a href="#" class="btn btn-info btn-xs btn-editar"><i class="fa fa-edit"></i> Agregar observación</a>
                                <?php }?>
                                <p class="clearfix">&nbsp;</p>
                            <?php }?>
                            
                            <?php if ($_smarty_tpl->tpl_vars['control']->value['adjuntos']!='0'){?>
            
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <i class="fa fa-folder"></i> Documentación adjunta
                                    </div>

                                    <div class="panel-body">

                                        <ul class="list-group">

                                        <?php  $_smarty_tpl->tpl_vars['adjunto'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adjunto']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['adjuntos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['adjunto']->key => $_smarty_tpl->tpl_vars['adjunto']->value){
$_smarty_tpl->tpl_vars['adjunto']->_loop = true;
?>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-xs-11">
                                                        <?php if ($_smarty_tpl->tpl_vars['adjunto']->value['archivo']){?>
                                                        <a href="index.php?com=adjunto&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['adjunto']->value['id'];?>
">
                                                            <span class="fa-stack fa fa-2x pull-left" style="margin-bottom: 20px;">
                                                                <i class="fa fa-file fa-stack-2x"></i>
                                                                <span class="fa fa-stack-1x fa-inverse">.<?php echo $_smarty_tpl->tpl_vars['adjunto']->value['extension'];?>
</span>
                                                            </span>
                                                        </a>
                                                        <?php }?>

                                                        <h4 style="margin: 0;"><?php echo $_smarty_tpl->tpl_vars['adjunto']->value['nombre'];?>
</h4>
                                                        <?php if ($_smarty_tpl->tpl_vars['adjunto']->value['observacion']){?><p class="small" style="margin: 0;"><?php echo $_smarty_tpl->tpl_vars['adjunto']->value['observacion'];?>
</p><?php }?>
                                                        <?php if ($_smarty_tpl->tpl_vars['adjunto']->value['archivo']){?><a href="index.php?com=adjunto&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['adjunto']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['adjunto']->value['archivo'];?>
</a><?php }?>

                                                        <p><strong><?php echo $_smarty_tpl->tpl_vars['adjunto']->value['nombre_usuario'];?>
</strong> &bull; <?php echo $_smarty_tpl->tpl_vars['adjunto']->value['fecha'];?>
 a las <?php echo $_smarty_tpl->tpl_vars['adjunto']->value['hora'];?>
 hrs.<?php if ($_smarty_tpl->tpl_vars['adjunto']->value['archivo']){?> &bull; <?php echo $_smarty_tpl->tpl_vars['adjunto']->value['size'];?>
<?php }?></p>
                                                    </div>
                                                    <div class="col-xs-1">
                                                        <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['responsables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                                                            <?php if ($_smarty_tpl->tpl_vars['value']->value['usuario']==$_smarty_tpl->tpl_vars['SESSION_ID']->value&&$_smarty_tpl->tpl_vars['value']->value['dig']=='1'&&$_smarty_tpl->tpl_vars['control']->value['estado']!='APR'){?>
                                                                <div class="btn-group pull-right">
                                                                    <i data-toggle="dropdown" class="dropdown-toggle fa fa-cog fa-lg"></i>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="index.php?com=adjunto&amp;accion=eliminar&amp;id=<?php echo $_smarty_tpl->tpl_vars['adjunto']->value['id'];?>
" class="btn-eliminar">Eliminar</a></li>
                                                                    </ul>
                                                                </div>
                                                            <?php }?>
                                                        <?php } ?>  
                                                    </div>
                                                </div>
                                            </li>
                                        <?php }
if (!$_smarty_tpl->tpl_vars['adjunto']->_loop) {
?>

                                            <li class="list-group-item">
                                                <p>Aún no se ha registrado Documentación.</p>
                                            </li>

                                        <?php } ?>

                                        </ul>

                                    </div>
                                </div>
                            <?php }?>
            
            <?php if ($_smarty_tpl->tpl_vars['control']->value['estado']!='APR'){?>
            
                <?php if ($_smarty_tpl->tpl_vars['control']->value['adjuntos']!='0'){?>
                           
                    <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['responsables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                        <?php if ($_smarty_tpl->tpl_vars['value']->value['usuario']==$_smarty_tpl->tpl_vars['SESSION_ID']->value&&$_smarty_tpl->tpl_vars['value']->value['dig']=='1'){?>
                        
<div class="panel panel-default">
<div class="panel-heading">
<i class="fa fa-cloud-upload"></i> Adjuntar Documentación
</div>
<div class="panel-body">
<form action="index.php?com=adjunto&amp;accion=grabar" method="post" enctype="multipart/form-data">
<fieldset>
<div class="row">
<div class="form-group col-md-8">
<label>Nombre</label>
<input type="text" name="nombre" class="form-control" required autocomplete="off" placeholder="Nombre de la Documentación"/>
</div>
<div class="form-group col-md-4">
<label>Archivo</label>
<input type="file" name="adjunto" class="form-control" />
</div>
</div>
<div class="row">
<div class="col-md-8 form-group">
<textarea name="observacion" class="form-control" rows="4" placeholder="Descripción de la Documentación"></textarea>
</div>
<div class="col-md-4 form-group">
<input type="hidden" name="actividad" value="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['id'];?>
" /> 
                                        <input type="hidden" name="control" value="<?php echo $_smarty_tpl->tpl_vars['control']->value['id'];?>
" />
                                        <input type="hidden" name="usuario" value="<?php echo $_smarty_tpl->tpl_vars['SESSION_ID']->value;?>
" />
                                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button>
</div>
</div>
</fieldset>
</form>
</div>
</div>
                    
                        <?php }?>
                        
                    <?php } ?>
                    
                <?php }?>
                            
            <?php }?>
            
            <?php if (($_smarty_tpl->tpl_vars['ultimo_adjunto']->value!=''&&$_smarty_tpl->tpl_vars['control']->value['estado']!='APR')||($_smarty_tpl->tpl_vars['control']->value['adjuntos']=='0'&&$_smarty_tpl->tpl_vars['control']->value['estado']!='APR')){?>
            
                <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['responsables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                    <?php if ($_smarty_tpl->tpl_vars['value']->value['usuario']==$_smarty_tpl->tpl_vars['SESSION_ID']->value&&$_smarty_tpl->tpl_vars['value']->value['apr']=='1'){?>
                        
                    <div class="panel panel-default">
<div class="panel-heading">
<i class="fa fa-check"></i> Ingresar Resultado
</div>

<div class="panel-body">
<form action="index.php?com=resultado&amp;accion=grabar" method="post">
<fieldset>
<div class="row">
<div class="form-group col-md-8">
<label>Observaciones</label>
<textarea class="form-control" name="observacion" rows="4" required></textarea>
</div>
<div class="col-md-4">
<div class="form-group">
<label>Estado</label>
<select class="form-control" name="estado">
                                        <option value="REC">Rechazado</option>
                                        <option value="APR">Aprobado</option>
                                    </select>
</div>
<div class="form-group">
                                    <input type="hidden" name="actividad" value="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['id'];?>
" />  
                                    <input type="hidden" name="control" value="<?php echo $_smarty_tpl->tpl_vars['control']->value['id'];?>
" />
                                    <input type="hidden" name="aprobador" value="<?php echo $_smarty_tpl->tpl_vars['SESSION_ID']->value;?>
" />
                                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aceptar</button> 
</div>
</div>
</div>
</fieldset>
</form>
</div>
                    </div>
                    
                    <?php }?>
                        
                <?php } ?>
                
            <?php }?>

        </div>
        
        <div class="col-md-4">
        <div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-file"></i> Documentación Vigente</div>

<div class="panel-body">
<ul class="list-group">
<li class="list-group-item">
                                            <i class="fa fa-book pull-left fa-3x"></i>
<?php if ($_smarty_tpl->tpl_vars['contrato']->value['archivo']!=''){?>
                <p><strong>Descargar Manual:</strong><br /><a href="index.php?com=contrato&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['contrato']->value['nombre'];?>
</a></p>
                <?php }else{ ?>
                     <p><?php echo $_smarty_tpl->tpl_vars['contrato']->value['nombre'];?>
<br/><?php echo $_smarty_tpl->tpl_vars['contrato']->value['descripcion'];?>
</p>
                <?php }?>
</li>

<?php if ($_smarty_tpl->tpl_vars['manual']->value['nombre']!=''){?>
<li class="list-group-item">
<i class="fa fa-file-text pull-left fa-3x"></i>
<p><strong>Descargar sólo capítulo:</strong><br /><a href="index.php?com=manual&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['manual']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['manual']->value['nombre'];?>
</a></p>                
</li>
            <?php }?>
</ul>
 
</div>
        </div>
        
        <div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-info"></i> Control</div>

<div class="panel-body">
<ul class="list-group">
<li class="list-group-item">
<i class="fa fa-calendar pull-left fa-3x"></i>
                <p><strong>Fecha comprometida</strong><br /><?php echo $_smarty_tpl->tpl_vars['control']->value['fecha'];?>
</p>
</li>
<li class="list-group-item">
<i class="fa fa-history pull-left fa-3x"></i>
                <p><strong>Último evento</strong><br /><?php echo $_smarty_tpl->tpl_vars['registro']->value['estado'];?>
 el <?php echo $_smarty_tpl->tpl_vars['registro']->value['fecha'];?>
</p>
</li>
</ul>
</div>
        </div>
        
        <div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-bell"></i> Actividad</div>

<div class="panel-body">
<ul class="list-group">
<li class="list-group-item">
<i class="fa fa-calendar pull-left fa-3x"></i>
<p><strong>Planificación</strong><br />
<?php  $_smarty_tpl->tpl_vars['mes'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mes']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['array_meses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['mes']->key => $_smarty_tpl->tpl_vars['mes']->value){
$_smarty_tpl->tpl_vars['mes']->_loop = true;
?>
                    <?php echo $_smarty_tpl->tpl_vars['mes']->value;?>

                <?php } ?>
            </p>
</li>
<li class="list-group-item">
<div class="pull-left mr-20">
<i class="fa fa-user fa-3x"></i>
</div>
                                            <div class="pull-left">
                                                <p><strong>Usuarios responsables</strong><br /><small>
<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['responsables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                    
                    <?php if ($_smarty_tpl->tpl_vars['value']->value['apr']==1){?>
                    Aprobador: <?php echo $_smarty_tpl->tpl_vars['value']->value['nombre'];?>
<br />                   
                    <?php }?>
                    
                    <?php if ($_smarty_tpl->tpl_vars['value']->value['dig']==1){?>
                    Digitador: <?php echo $_smarty_tpl->tpl_vars['value']->value['nombre'];?>
<br />                       
                    <?php }?>
                    
                    <?php if ($_smarty_tpl->tpl_vars['value']->value['ale']==1){?>
                    Alertado: <?php echo $_smarty_tpl->tpl_vars['value']->value['nombre'];?>
<br />                       
                    <?php }?>
                    
                <?php }
if (!$_smarty_tpl->tpl_vars['value']->_loop) {
?>
                            
                    No existen usuarios asignados a esta actividad.   
                    
                <?php } ?>
     </small></p>
                                            </div>
<div class="clearfix"></div>
</li>
<?php if ($_smarty_tpl->tpl_vars['grupo']->value['nombre']!=''){?>   
<li class="list-group-item">
<i class="fa fa-users pull-left fa-3x"></i>
<p><strong>Grupo</strong><br />
<?php echo $_smarty_tpl->tpl_vars['grupo']->value['nombre'];?>
<?php if ($_smarty_tpl->tpl_vars['grupo']->value['descripcion']!=''){?>&bulls;<?php echo $_smarty_tpl->tpl_vars['grupo']->value['descripcion'];?>
<?php }?>
            </p>
</li>
<?php }?>
</ul>
</div>
        </div>
        
        <div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-folder"></i> Últimos documentos de la Actividad</div>

<div class="panel-body">
<ul class="list-group">
<?php  $_smarty_tpl->tpl_vars['adjunto'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adjunto']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['otros_adjuntos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['adjunto']->key => $_smarty_tpl->tpl_vars['adjunto']->value){
$_smarty_tpl->tpl_vars['adjunto']->_loop = true;
?>
<li class="list-group-item">
<i class="fa fa-file"></i> <?php echo $_smarty_tpl->tpl_vars['adjunto']->value['fecha'];?>
 <a href="index.php?com=adjunto&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['adjunto']->value['id'];?>
"><small><?php echo $_smarty_tpl->tpl_vars['adjunto']->value['nombre'];?>
 &bull; <?php echo $_smarty_tpl->tpl_vars['adjunto']->value['size'];?>
 &bull; <?php echo $_smarty_tpl->tpl_vars['adjunto']->value['extension'];?>
</small></a>
</li>
<?php }
if (!$_smarty_tpl->tpl_vars['adjunto']->_loop) {
?>
                       <li class="list-group-item">No se han registrado archivos adjuntos en otros controles.</li>               
                    <?php } ?>
</ul>
</div>
        </div>
        
        <?php if ($_smarty_tpl->tpl_vars['SESSION_TIPO']->value=='ADM'){?>
        
        <div class="panel panel-default">
<div class="panel-heading"><i class="fa fa-cogs"></i> Administrador</div>

<div class="panel-body">
<div class="row">
<div class="col-md-6">
<a href="index.php?com=control&amp;accion=editar&amp;id=<?php echo $_smarty_tpl->tpl_vars['control']->value['id'];?>
" class="btn btn-block btn-warning"><i class="fa fa-cogs"></i> Editar</a>
</div>
<p class="clearfix visible-xs visible-sm"></p>
<div class="col-md-6">
<a href="index.php?com=control&amp;accion=eliminar&amp;id=<?php echo $_smarty_tpl->tpl_vars['control']->value['id'];?>
" class="btn btn-block btn-danger btn-eliminar"><i class="fa fa-times"></i> Eliminar</a>
</div>
</div>
</div>
        </div>
        
        <?php }?>

        </div>
        
        </div>
        
    </div>
</div>
<div class="modal fade" id="modal-descripcion" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog">
   <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Editar descripción</h4>
    </div>
    <div class="modal-body">
    
    <div class="row">
    
    <div class="col-xs-12">
    
    <form id="form-descripcion" action="index.php?com=control&amp;accion=grabar_descripcion" method="post">
    
    <div class="form-group">
    
    <textarea name="descripcion" class="form-control" rows="5"><?php echo $_smarty_tpl->tpl_vars['actividad']->value['descripcion'];?>
</textarea>
    <input type="hidden" name="actividad" value="<?php echo $_smarty_tpl->tpl_vars['actividad']->value['id'];?>
 " />
    <input type="hidden" name="control" value="<?php echo $_smarty_tpl->tpl_vars['control']->value['id'];?>
 " />
    
    </div>

    </form>
     
    </div>
    
    </div>
       
</div>
<div class="modal-footer">
<div class="row">
<div class="col-xs-6 col-md-3 col-md-push-6">
<button type="button" class="btn btn-success btn-block btn-grabar-descripcion"><i class="fa fa-check"></i> Aceptar</button>
</div>
<div class="col-xs-6 col-md-3 col-md-push-6">
<button type="button" class="btn btn-primary btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
$(function(){
$('.btn-editar').click(function(e){
e.preventDefault();
$('#modal-descripcion').modal('show');
});

$('.btn-grabar-descripcion').click(function(e){
$('#form-descripcion').submit();
});
});
</script><?php }} ?>