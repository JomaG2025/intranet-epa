<?php
// /intranet/proyectos/config/conexion.php

$host = 'localhost';
// Según tu archivo, la base de datos principal es 'intranet' 
// o donde tengas las tablas 'pry_'
$db   = 'intranet'; 
$user = 'root';
$pass = 'Tacora.389';

// Usamos utf8mb4 para el soporte total de tildes y caracteres especiales

$dsn = "mysql:host=$host;dbname=$db;charset=latin1"; 

$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1" // Forzamos comunicación en Latin1
]);


try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Forzamos la comunicación en UTF-8 con el servidor
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
} catch (PDOException $e) {
    // Si el error persiste, asegúrate de que el nombre de la DB sea el correcto
    die("Error de conexión en el servidor EPA: " . $e->getMessage());
}
?>