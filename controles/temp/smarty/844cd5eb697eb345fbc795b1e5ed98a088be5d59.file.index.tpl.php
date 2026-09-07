<?php /* Smarty version Smarty-3.1.8, created on 2023-05-29 17:33:14
         compiled from "template/componentes/com_tarea/vista/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16635480365c9e7af04b5d48-02439874%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '844cd5eb697eb345fbc795b1e5ed98a088be5d59' => 
    array (
      0 => 'template/componentes/com_tarea/vista/index.tpl',
      1 => 1685393259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16635480365c9e7af04b5d48-02439874',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_5c9e7af059b551_40317214',
  'variables' => 
  array (
    'tareas' => 0,
    'key' => 0,
    'tarea' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9e7af059b551_40317214')) {function content_5c9e7af059b551_40317214($_smarty_tpl) {?>        <div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Mis Controles</h4>
</div>
        <div class="panel panel-default no-border">
<div class="panel-heading visible-xs"><i class="fa fa-list"></i> Mis Controles</div>
<div class="panel-body">
        
        <div class="row">
        
        <div class="col-lg-12">
        
        <div class="table-responsive">

<table class="table table-hover table-condensed table-striped">

<thead>
<tr>
<th class="text-center">#</th>
                <th class="text-center">item</th>
                <th class="text-center">capítulo</th>
                <th class="text-center">actividad</th>
                <th class="text-center">fecha comprometida</th>
                <th class="text-center">estado</th>
</tr>

</thead>
<tbody class="text-center">

<?php  $_smarty_tpl->tpl_vars['tarea'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tarea']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tareas']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tarea']->key => $_smarty_tpl->tpl_vars['tarea']->value){
$_smarty_tpl->tpl_vars['tarea']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['tarea']->key;
?>
            
            <tr>
                <td style="width: 5%; text-align: center;"><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['tarea']->value['capitulo'];?>
</td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['tarea']->value['capitulo_padre'];?>
</strong></td>
                <td><a href="index.php?com=control&amp;accion=detalle&amp;id=<?php echo $_smarty_tpl->tpl_vars['tarea']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['tarea']->value['nombre'];?>
</a></td> 
                <td><?php echo $_smarty_tpl->tpl_vars['tarea']->value['fecha'];?>
</td>
                <td><a href="index.php?com=control&amp;accion=detalle&amp;id=<?php echo $_smarty_tpl->tpl_vars['tarea']->value['id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['tarea']->value['estado_glosa'];?>
" data-toggle="tooltip" class="capitulo"><img src="template/imagenes/estado_<?php echo $_smarty_tpl->tpl_vars['tarea']->value['estado'];?>
.png" alt="<?php echo $_smarty_tpl->tpl_vars['tarea']->value['estado_glosa'];?>
" /></a></td>
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
 { "bSearchable": false, "aTargets": [ 5 ] }
 ]
        });
    });
</script>

</div>

        </div>

</div>
    </div>
</div><?php }} ?>