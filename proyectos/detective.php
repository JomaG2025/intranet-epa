<?php
session_start();
echo "<h3>Sesión Estándar:</h3><pre>"; print_r($_SESSION); echo "</pre>";
echo "<h3>Todas las Cookies:</h3><pre>"; print_r($_COOKIE); echo "</pre>";
?>