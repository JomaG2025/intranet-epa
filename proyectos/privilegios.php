<?php
// /intranet/proyectos/privilegios.php
include_once 'header_local.php';

if ($rango_mostrar !== 'ADM') { die("Acceso restringido."); }
$privilegios = $pdo->query("SELECT * FROM pry_privilegio ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Maestro de Privilegios - EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .page-header { border-bottom: 2px solid #eee; margin-bottom: 25px; font-weight: 800; color: #333; }
        .table > thead > tr > th { vertical-align: middle; text-align: center; font-size: 11px; }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">
        <h1 class="page-header"><i class="fa fa-key text-primary"></i> Maestro de Privilegios</h1>
        <div class="panel panel-default">
            <div class="panel-heading">Perfiles Institucionales y Matriz de Permisos</div>
            <div class="table-responsive">
                <table class="table table-hover table-striped" style="font-size: 12px;">
                    <thead>
                        <tr class="info">
                            <th style="text-align: left;">PERFIL</th>
                            <th>CREAR</th>
                            <th>EDITAR</th>
                            <th>APROBAR</th>
                            <th>COSTOS</th>
                            <th>DOCS</th>
                            <th>REPORTES</th>
                            <th>GLOBAL</th>
                            <th style="text-align: right;">ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($privilegios as $p): ?>
                        <tr>
                            <td><strong><?= $p['nombre'] ?></strong> (<?= $p['abrev'] ?>)</td>
                            <td class="text-center"><?= $p['p_crear'] ? '✅' : '❌' ?></td>
                            <td class="text-center"><?= $p['p_editar'] ? '✅' : '❌' ?></td>
                            <td class="text-center"><?= $p['p_aprobar'] ? '✅' : '❌' ?></td>
                            <td class="text-center"><?= $p['p_costos'] ? '✅' : '❌' ?></td>
                            <td class="text-center"><?= $p['p_documentos'] ? '✅' : '❌' ?></td>
                            <td class="text-center"><?= $p['p_reportes'] ? '✅' : '❌' ?></td>
                            <td class="text-center"><?= $p['p_global'] ? '✅' : '❌' ?></td>
                            <td class="text-right">
                                <a href="editar_privilegio.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-xs">Configurar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once 'footer_local.php'; ?>
</body>
</html>