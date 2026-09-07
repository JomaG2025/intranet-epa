<?php
// /intranet/proyectos/header_local.php
header('Content-Type: text/html; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'config/conexion.php';

$usuario_sesion = 'Invitado';

// 1. LÓGICA DE SESIÓN Y SEGURIDAD
if (isset($_COOKIE['puertoaricarel'])) {
    $serialized_data = substr($_COOKIE['puertoaricarel'], 0, -32);
    $data = @unserialize($serialized_data);
    if (isset($data['usuario'])) { $usuario_sesion = $data['usuario']; }
} elseif (isset($_COOKIE['puertoaricaintranet'])) {
    $ser_portal = substr($_COOKIE['puertoaricaintranet'], 0, -32);
    $data_p = @unserialize($ser_portal);
    if (isset($data_p['session_id'])) {
        $st = $pdo->prepare("SELECT user_data FROM int_sesion WHERE session_id = ?");
        $st->execute([$data_p['session_id']]);
        $row = $st->fetch();
        if ($row && preg_match('/"usuario";s:\d+:"([^"]+)"/', $row['user_data'], $m)) {
            $usuario_sesion = $m[1];
        }
    }
}

// 2. CARGA DE PERMISOS
$stmt_user = $pdo->prepare("
    SELECT u.usuario, p.* FROM pry_usuario u 
    INNER JOIN pry_privilegio p ON u.id_privilegio = p.id 
    WHERE u.usuario = ? AND u.activo = 1
");
$stmt_user->execute([$usuario_sesion]);
$datos_usuario = $stmt_user->fetch();

if ($datos_usuario) {
    $_SESSION['permisos'] = $datos_usuario;
    $nombre_mostrar = $datos_usuario['usuario'];
    $rango_mostrar = $datos_usuario['abrev'];
} else {
    $_SESSION['permisos'] = null;
    $nombre_mostrar = $usuario_sesion;
    $rango_mostrar = '--';

    // Bloquear acceso si el usuario no está registrado en el módulo de Proyectos
    $pagina_actual = basename($_SERVER['PHP_SELF']);
    if ($pagina_actual !== 'salir.php') {
        http_response_code(403);
        die('<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
<title>Sin acceso</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head><body style="background:#f8f8f8;">
<div style="max-width:480px;margin:80px auto;text-align:center;">
    <div class="panel panel-danger">
        <div class="panel-heading"><h3 class="panel-title">Acceso no autorizado</h3></div>
        <div class="panel-body">
            <p>El usuario <strong>' . htmlspecialchars($usuario_sesion) . '</strong> no está registrado en el módulo de Proyectos.</p>
            <p class="text-muted">Contacte al administrador del sistema para solicitar acceso.</p>
            <a href="salir.php" class="btn btn-default btn-sm"><i class="fa fa-sign-out"></i> Volver al inicio de sesión</a>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</body></html>');
    }
}
?>

<link rel="stylesheet" href="/proyectos/estilo.css">
<style>
    #sidebar-simulado {
        width: 250px !important; position: fixed !important; height: 100% !important;
        z-index: 1000 !important; left: 0 !important; top: 0 !important; overflow-y: auto;
        padding-top: 0 !important;
    }
    #sidebar-simulado i { margin-right: 10px; width: 18px; text-align: center; }
    .user-panel { text-align: center; }
</style>

<div id="sidebar-simulado">
    <div class="user-panel">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="img-circle">
        <br><strong><?php echo htmlspecialchars($nombre_mostrar); ?></strong>
        <br><span class="label label-success" style="font-size: 10px;"><?php echo $rango_mostrar; ?></span>
    </div>
    
    <a href="index.php"><i class="fa fa-home fa-fw"></i> Inicio</a>
    
    <a href="index.php"><i class="fa fa-briefcase fa-fw"></i> Proyectos</a>
    <a href="dashboard.php"><i class="fa fa-bar-chart fa-fw"></i> Dashboard</a>
    <a href="gantt_cartera.php"><i class="fa fa-tasks fa-fw"></i> Carta Gantt</a>

    <?php if ($rango_mostrar === 'ADM'): ?>
        <a href="#adminMenu" data-toggle="collapse" class="collapsed">
            <i class="fa fa-cog fa-fw"></i> Administración <i class="fa fa-caret-down pull-right"></i>
        </a>
        <div id="adminMenu" class="collapse">
            <ul>
                <li><a href="usuarios.php"><i class="fa fa-users fa-fw"></i> Usuarios</a></li>
                <li><a href="privilegios.php"><i class="fa fa-key fa-fw"></i> Privilegios</a></li>
                <li><a href="auditoria.php"><i class="fa fa-history fa-fw"></i> Bitácora</a></li>
            </ul>
        </div>
    <?php endif; ?>

    <a href="salir.php"><i class="fa fa-sign-out fa-fw"></i> Salir</a>
</div>