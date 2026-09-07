<?php /* Smarty version Smarty-3.1.8, created on 2023-06-13 15:53:50
         compiled from "template/componentes/com_privilegio/vista/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17708478325ca21ff3e0a309-13983108%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aba9fca7b4b6720f2756ead35ee7050f919ac7f3' => 
    array (
      0 => 'template/componentes/com_privilegio/vista/index.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17708478325ca21ff3e0a309-13983108',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5ca21ff3eb2379_58427810',
  'variables' => 
  array (
    'usuarios' => 0,
    'usuario' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca21ff3eb2379_58427810')) {function content_5ca21ff3eb2379_58427810($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Administración <i class="fa fa-angle-right"></i> Responsabilidades</h4>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-cogs"></i> Responsabilidades</div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">id</th>
<th class="text-center">Usuario</th>
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
                <td style="width: 5%; text-align: center;"><?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
</td>
                <td><a href="index.php?com=privilegio&amp;accion=editar&amp;usuario=<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['usuario']->value['usuario'];?>
</a></td> 
                <td style="width: 10%;"><a href="index.php?com=privilegio&amp;accion=editar&amp;usuario=<?php echo $_smarty_tpl->tpl_vars['usuario']->value['id'];?>
" class="btn btn-sm btn-block btn-warning"><i class="fa fa-key"></i> Editar</a></td>
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
 { "bSortable": false, "aTargets": [ 2 ] },
 { "bSearchable": false, "aTargets": [ 0, 2] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div><?php }} ?>