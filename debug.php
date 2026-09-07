<?php
session_start();
// Forzamos un dato para ver si el servidor lo guarda
$_SESSION['prueba_local'] = 'Funciona';

echo "<h1>Datos de Sesión:</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>