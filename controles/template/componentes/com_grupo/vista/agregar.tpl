<div class="titulo">
    <h1>{$SISTEMA_TITULO} <a href="index.php?com=grupo" title="volver"><img src="template/imagenes/volver.png" alt="volver" id="volver" /></a></h1>
</div>
<div id="content">
        <form name="grupo" action="index.php?com=grupo&amp;accion=grabar" method="post" class="frm" onsubmit="return validarFormulario(this.name);">
<fieldset class="left">
                <span class="legend">Información principal</span>
                <div class="clear"></div>
<label>Nombre: </label>
<input type="text" name="nombre"/>
            </fieldset>
            <fieldset class="right">
                <span class="legend">Información adicional</span>
                <div class="clear"></div>
                <label>Descripción: </label>
<textarea cols="10" rows="10" name="descripcion" class="no-validar"></textarea>
<div class="clear"></div>
</fieldset>
            <div class="clear2"></div>
            <div>
                <input type="submit" class="submit" value="Aceptar" />
                <input type="button" class="cancelar" value="Cancelar" onclick="javascript: window.location='{$REFERER}'" />
                <div class="clear"></div>
            </div>
</form>