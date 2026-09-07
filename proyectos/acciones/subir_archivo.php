<?php
// /intranet/proyectos/acciones/subir_archivo.php
// 1. Cargamos la lógica de seguridad (ajustamos ruta al estar en subcarpeta)
$path_header = dirname(__DIR__) . '/header_local.php';
if (file_exists($path_header)) {
    include_once $path_header;
} else {
    die("Error de sistema: No se pudo cargar la seguridad.");
}

// 2. Blindaje: Si es Invitado, bloqueamos la acción inmediatamente
if ($usuario_sesion === 'Invitado') {
    die("Acceso denegado: Debe estar logueado para subir archivos.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pry = $_POST['id_proyecto'];
    $nombre_vis = $_POST['nombre_visible'];
    $archivo = $_FILES['archivo'];

    if ($archivo['error'] === 0) {
        // Validación de seguridad de archivo (Solo PDFs)
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if ($extension !== 'pdf') {
            die("Error: Solo se permiten archivos PDF por seguridad institucional.");
        }

        // Nombre único para evitar duplicados en el puerto
        $nombre_final = "PRY_" . $id_pry . "_" . time() . "." . $extension;
        $ruta_destino = "../documentos/" . $nombre_final;

        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            $sql = "INSERT INTO pry_documentos (id_proyecto, nombre_visible, nombre_archivo) VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([$id_pry, $nombre_vis, $nombre_final]);
            
            header("Location: ../ficha.php?id=$id_pry&upload=ok");
            exit();
        } else {
            echo "Error de permisos: La carpeta 'documentos' no tiene permisos de escritura.";
        }
    }
}
?>