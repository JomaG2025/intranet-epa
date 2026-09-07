<?php
echo "<h1>Analizador de Sesión Relacionados</h1>";
if(isset($_COOKIE['puertoaricarel'])) {
    $serialized = substr($_COOKIE['puertoaricarel'], 0, -32);
    $data = unserialize($serialized);
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} else {
    echo "No se detecta la cookie. Loguéate en la Intranet primero.";
}
?>