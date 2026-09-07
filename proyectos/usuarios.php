<?php 
// 1. Incluimos el header dinámico
include_once 'header_local.php'; 

// 2. SEGURIDAD: Si el usuario no es ADM, lo sacamos de aquí de inmediato
if ($rango_mostrar !== 'ADM') {
    header("Location: index.php");
    exit();
}

require_once 'config/conexion.php';

// 3. Consulta de usuarios del sistema de proyectos
$sql = "SELECT u.id, u.creado, u.modificado, u.usuario, p.nombre AS privilegio_nombre, u.activo 
        FROM pry_usuario u 
        INNER JOIN pry_privilegio p ON u.id_privilegio = p.id 
        WHERE u.eliminado = 0 
        ORDER BY u.id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Usuarios - Proyectos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        
        /* Estilos del Sidebar (mantenemos para el layout) */
        #sidebar-simulado { width: 250px; position: fixed; height: 100%; background: #0d2244; color: white; padding-top: 20px; z-index: 100; left: 0; top: 0; }
        #sidebar-simulado a { color: #d1d1d1; padding: 15px 20px; display: block; text-decoration: none; border-bottom: 1px solid #34495e; font-size: 14px; }
        #sidebar-simulado a:hover { background: #1a252f; color: white; }
        .user-panel { padding: 20px; text-align: center; border-bottom: 1px solid #3e4f5f; margin-bottom: 10px; }
        .user-panel img { border: 2px solid #34495e; width: 60px; height: 60px; margin-bottom: 10px; }
        
        #page-wrapper { margin-left: 250px; padding: 25px; background: white; min-height: 100vh; }
        .breadcrumb { background: transparent; padding: 0; margin-bottom: 20px; font-size: 16px; color: #777; }
        .table thead tr { background-color: #f9f9f9; font-size: 11px; text-transform: uppercase; color: #666; }
        .btn-status { width: 90px; font-weight: bold; }
    </style>
</head>
<body>

<div id="wrapper">
    <div id="page-wrapper">
        <div class="breadcrumb">
            <i class="fa fa-angle-right"></i> Administración > <strong>Usuarios</strong>
        </div>

        <div class="row" style="margin-bottom: 20px;">
            <div class="col-lg-12 text-right">
                <a href="nuevo_usuario.php" class="btn btn-info btn-sm">
                    <i class="fa fa-plus"></i> Nuevo Usuario
                </a>
            </div>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'success'): ?>
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> Usuario registrado correctamente.</div>
            <?php elseif ($_GET['msg'] == 'updated'): ?>
                <div class="alert alert-info"><i class="fa fa-check-circle"></i> Usuario actualizado correctamente.</div>
            <?php elseif ($_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-warning"><i class="fa fa-trash"></i> Usuario eliminado.</div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CREADO</th>
                                <th>MODIFICADO</th>
                                <th>USUARIO</th>
                                <th>PRIVILEGIO</th>
                                <th class="text-center">ESTADO</th>
                                <th class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= $u['id'] ?></td>
                                <td><?= $u['creado'] ?></td>
                                <td><?= $u['modificado'] ?: '---' ?></td>
                                <td><strong><?= htmlspecialchars($u['usuario']) ?></strong></td>
                                <td><?= $u['privilegio_nombre'] ?></td>
                                <td class="text-center">
                                    <span class="label <?= $u['activo'] ? 'label-success' : 'label-danger' ?> btn-status">
                                        <?= $u['activo'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-xs" onclick="return confirm('¿Eliminar?');"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
