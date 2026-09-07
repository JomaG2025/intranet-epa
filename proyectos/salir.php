<?php
// /intranet/proyectos/salir.php
session_start();

// 1. Destruimos la sesión local de PHP
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// 2. Limpiamos las cookies de la Intranet para forzar el logout real
// Borramos la tuya (Relacionados)
setcookie('puertoaricarel', '', time() - 3600, '/');

// Borramos la de los Auditores (Portal)
setcookie('puertoaricaintranet', '', time() - 3600, '/');

// 3. Redirigimos a la raíz de la Intranet para que el usuario vuelva a loguearse
header("Location: /");
exit();
?>