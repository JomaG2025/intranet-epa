<div class="titulo">
    <h1>{$SISTEMA_TITULO}</h1>
</div>
<div id="content">
    <div class="menu-componente">
        <a href="index.php?com=grupo&amp;accion=agregar" title="Nuevo Grupo"><img src="template/imagenes/group_add.png" alt="" /></a>
    </div>
    <div class="clear"></div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>nombre</th>
                <th>descripción</th>
                <th>id</th>
                <th>editar</th>
                <th>eliminar</th>
            </tr>
        </thead>
        <tbody>
            
            {foreach item=grupo from=$grupos}
            
            <tr>
                <td style="width: 40px; text-align: center;">{$inicio++}</td>
                <td style="width: 300px;"><a href="index.php?com=grupo&amp;accion=editar&amp;id={$grupo.id}" title="Editar {$grupo.nombre}">{$grupo.nombre}</a></td>
                <td>{$grupo.descripcion}</td>
                <td style="width: 40px; text-align: center;">{$grupo.id}</td>
                <td style="width: 40px; text-align: center;"><a href="index.php?com=grupo&amp;accion=editar&amp;id={$grupo.id}" title="Editar {$grupo.nombre}"><img src="template/imagenes/group_edit.png" alt="" /></a></td>
                <td style="width: 40px; text-align: center;"><a href="index.php?com=grupo&amp;accion=eliminar&amp;id={$grupo.id}" title="Eliminar {$grupo.nombre}" onclick="return confirmarEliminar();"><img src="template/imagenes/group_delete.png" alt="" /></a></td>
            </tr>
            
            {/foreach}
                
        </tbody>
    </table>
    <div class="clear"></div>
    {$filtro_pagina}
    {$filtro_resultado}