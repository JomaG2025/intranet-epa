<?php /* Smarty version Smarty-3.1.8, created on 2024-01-16 10:43:51
         compiled from "template/componentes/com_manual/vista/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:740325215d23b4d54948c1-59652861%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ac1506264cce8b7abe51893e8a642af7d7b9c0e5' => 
    array (
      0 => 'template/componentes/com_manual/vista/index.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '740325215d23b4d54948c1-59652861',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5d23b4d55b0f52_24140848',
  'variables' => 
  array (
    'manuales' => 0,
    'manual' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d23b4d55b0f52_24140848')) {function content_5d23b4d55b0f52_24140848($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Manuales</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=manual&amp;accion=agregar" class="btn btn-info"><i class="fa fa-plus"></i> Nuevo Manual</a>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Manuales<a href="index.php?com=manual&amp;accion=agregar" class="btn btn-info btn-xs pull-right"><i class="fa fa-plus"></i> Nuevo Manual</a></div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">id</th>
<th class="text-center">Nombre</th>
<th class="text-center">Contrato</th>
<th class="text-center">Fecha</th>
<th class="text-center">Versión</th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
</tr>

</thead>
<tbody class="text-center">

<?php  $_smarty_tpl->tpl_vars['manual'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['manual']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['manuales']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['manual']->key => $_smarty_tpl->tpl_vars['manual']->value){
$_smarty_tpl->tpl_vars['manual']->_loop = true;
?>

<tr>
<td style="width: 5%;"><?php echo $_smarty_tpl->tpl_vars['manual']->value['id'];?>
</td>
<td><a href="index.php?com=manual&amp;accion=editar&amp;id=<?php echo $_smarty_tpl->tpl_vars['manual']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['manual']->value['nombre'];?>
</a></td>
<td><?php echo $_smarty_tpl->tpl_vars['manual']->value['nombre_contrato'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['manual']->value['fecha'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['manual']->value['version'];?>
</td>
<td><a href="index.php?com=manual&amp;accion=descargar&amp;id=<?php echo $_smarty_tpl->tpl_vars['manual']->value['id'];?>
" class="btn btn-sm btn-block btn-default"><i class="fa fa-file"> </i> Descargar</a></td>
<td><a href="index.php?com=manual&amp;accion=editar&amp;id=<?php echo $_smarty_tpl->tpl_vars['manual']->value['id'];?>
" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
<td><a href="index.php?com=manual&amp;accion=eliminar&amp;id=<?php echo $_smarty_tpl->tpl_vars['manual']->value['id'];?>
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
 { "bSortable": false, "aTargets": [ 5, 6, 7 ] },
 { "bSearchable": false, "aTargets": [ 0, 5, 6, 7] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div><?php }} ?>