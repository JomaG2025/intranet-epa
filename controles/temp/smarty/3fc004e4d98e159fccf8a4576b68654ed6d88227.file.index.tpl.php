<?php /* Smarty version Smarty-3.1.8, created on 2023-09-07 12:40:31
         compiled from "template/componentes/com_contrato/vista/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18076478465cdb354617d2c7-54145477%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3fc004e4d98e159fccf8a4576b68654ed6d88227' => 
    array (
      0 => 'template/componentes/com_contrato/vista/index.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18076478465cdb354617d2c7-54145477',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5cdb35462a86b2_92018030',
  'variables' => 
  array (
    'contratos' => 0,
    'contrato' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cdb35462a86b2_92018030')) {function content_5cdb35462a86b2_92018030($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Planificaciones</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=contrato&amp;accion=agregar" class="btn btn-info" title="Nuevo"><i class="fa fa-plus"></i> Nueva planificación</a>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Contratos<a href="index.php?com=contrato&amp;accion=agregar" class="btn btn-info btn-xs pull-right" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Contrato</a></div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">id</th>
<th class="text-center">Nombre</th>
<th class="text-center">Descripción</th>
<th class="text-center">Estado</th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
</tr>

</thead>
<tbody class="text-center">

<?php  $_smarty_tpl->tpl_vars['contrato'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['contrato']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contratos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['contrato']->key => $_smarty_tpl->tpl_vars['contrato']->value){
$_smarty_tpl->tpl_vars['contrato']->_loop = true;
?>

<tr>
<td style="width: 5%;"><?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
</td>
<td><a href="index.php?com=contrato&amp;accion=editar&amp;id=<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['contrato']->value['nombre'];?>
</a></td>
<td><?php echo $_smarty_tpl->tpl_vars['contrato']->value['descripcion'];?>
</td>
<td><a href="index.php?com=contrato&amp;accion=activo&amp;id=<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
" class="btn btn-sm btn-block btn-<?php if ($_smarty_tpl->tpl_vars['contrato']->value['activo']==1){?>success<?php }else{ ?>danger<?php }?>"><?php if ($_smarty_tpl->tpl_vars['contrato']->value['activo']==1){?><i class="fa fa-check"></i> Activo <?php }else{ ?> <i class="fa fa-times"></i> Inactivo<?php }?></a></td>
<td>
<?php if ($_smarty_tpl->tpl_vars['contrato']->value['archivo']){?>
                <a href="index.php?com=contrato&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
" class="btn btn-sm btn-block btn-default"><i class="fa fa-file"></i> Descargar</a>
                <?php }?>
</td>
<td><a href="index.php?com=contrato&amp;accion=editar&amp;id=<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
<td><a href="index.php?com=contrato&amp;accion=eliminar&amp;id=<?php echo $_smarty_tpl->tpl_vars['contrato']->value['id'];?>
" class="btn btn-sm btn-block btn-danger btn-eliminar"><i class="fa fa-trash-o"></i> Eliminar</a></td>

</tr>
<?php } ?>

</tbody>

</table>

<script>
    $(function(){
        $('.table').dataTable({
         bStateSave : true,      
      fnStateSave :function(settings,data){
        localStorage.setItem("dataTables_state", JSON.stringify(data));
      },
      fnStateLoad: function(settings) {
        return JSON.parse(localStorage.getItem("dataTables_state"));
      },
     'aoColumnDefs': [
 { "bSortable": false, "aTargets": [ 4, 5, 6 ] },
 { "bSearchable": false, "aTargets": [ 0, 3, 4, 5, 6 ] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div><?php }} ?>