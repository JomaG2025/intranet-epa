<?php /* Smarty version Smarty-3.1.8, created on 2023-06-28 17:12:56
         compiled from "template/componentes/com_usuario/vista/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9251561635ca21fe91b4b54-41519112%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '959fb0d31bb2883b323ecb553fd8861006a5aa0d' => 
    array (
      0 => 'template/componentes/com_usuario/vista/index.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9251561635ca21fe91b4b54-41519112',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca21fe92a4b99_25413000',
  'variables' => 
  array (
    'usuarios' => 0,
    'usuario' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca21fe92a4b99_25413000')) {function content_5ca21fe92a4b99_25413000($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Usuarios</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="index.php?com=usuario&amp;accion=agregar" class="btn btn-info" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Usuario</a>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Usuarios<a href="index.php?com=usuario&amp;accion=agregar" class="btn btn-info btn-xs pull-right" title="Nuevo"><i class="fa fa-plus"></i> Nuevo Usuario</a></div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">id</th>
<th class="text-center">Usuario</th>
<th class="text-center">Privilegio</th>
<th class="text-center">Estado</th>
<th class="text-center"></th>
<th class="text-center"></th>

</tr>

</thead>
<tbody class="text-center">

<?php  $_smarty_tpl->tpl_vars['usuario'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['usuario']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['usuarios']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['usuario']->key => $_smarty_tpl->tpl_vars['usuario']->value){
$_smarty_tpl->tpl_vars['usuario']->_loop = true;
?>
            
            <tr>
<td style="width: 5%;"><?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
</td>
<td><a href="index.php?com=usuario&amp;accion=editar&amp;id=<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['usuario']->value['usuario'];?>
</a></td>
<td><?php echo $_smarty_tpl->tpl_vars['usuario']->value['tipo_nombre'];?>
</td>
<td><a href="index.php?com=usuario&amp;accion=activo&amp;id=<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
" class="btn btn-sm btn-block btn-<?php if ($_smarty_tpl->tpl_vars['usuario']->value['activo']==1){?>success<?php }else{ ?>danger<?php }?>"><?php if ($_smarty_tpl->tpl_vars['usuario']->value['activo']==1){?><i class="fa fa-check"></i> Activo <?php }else{ ?> <i class="fa fa-times"></i> Inactivo<?php }?></a></td>
<td><a href="index.php?com=usuario&amp;accion=editar&amp;id=<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Editar</a></td>
<td><a href="index.php?com=usuario&amp;accion=eliminar&amp;id=<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
" class="btn btn-sm btn-block btn-danger btn-eliminar" title=""><i class="fa fa-trash-o"></i> Eliminar</a></td>
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
 { "bSortable": false, "aTargets": [ 4, 5 ] },
 { "bSearchable": false, "aTargets": [ 0, 3, 4, 5] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div><?php }} ?>