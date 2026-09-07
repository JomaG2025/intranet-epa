<div class="navbar-title hidden-xs">
    <h4><i class="fa fa-angle-right"></i> Inventario <i class="fa fa-angle-right"></i> Importar Excel</h4>
</div>
<div class="navbar-action hidden-xs">
    <a href="<?= site_url('inventario/importaciones'); ?>" class="btn btn-default"><i class="fa fa-history"></i> Historial de importaciones</a>
</div>

<div class="panel panel-default no-border">
    <div class="panel-heading visible-xs"><i class="fa fa-file-excel-o"></i> Importar Excel</div>
    <div class="panel-body">

        <?php if ( ! $lib_disponible) { ?>
        <div class="alert alert-danger">
            <strong>La importacion no esta disponible.</strong> Falta instalar la libreria PHPExcel 1.8.1 en el servidor,
            en <code>application/third_party/PHPExcel/PHPExcel.php</code>. Ver <code>docs/IMPORTACION_EXCEL.md</code> para instalarla.
            El resto del modulo de Inventario sigue funcionando con normalidad.
        </div>
        <?php } ?>

        <div class="alert alert-info">
            Sube el archivo Excel (.xlsx) con la hoja <strong>"Cruce Activos"</strong>. En el siguiente paso se muestra
            una <strong>vista previa</strong> de todas las filas (sin guardar nada todavia); recien al confirmar se
            insertan los activos nuevos. Los activos que ya existan (mismo Codigo Unico) no se duplican ni se
            modifican.
        </div>

        <form method="post" action="<?= site_url('inventario/importar/subir'); ?>" enctype="multipart/form-data">
            <?= inv_csrf_campo(); ?>
            <div class="row">
                <div class="form-group col-sm-8">
                    <label>Archivo Excel (.xlsx, maximo 10 MB)</label>
                    <input type="file" name="archivo" accept=".xlsx" required <?= ( ! $lib_disponible) ? 'disabled' : ''; ?>>
                </div>
                <div class="form-group col-sm-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-info btn-block" <?= ( ! $lib_disponible) ? 'disabled' : ''; ?>><i class="fa fa-upload"></i> Subir y Ver Vista Previa</button>
                </div>
            </div>
        </form>

    </div>
</div>
