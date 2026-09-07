<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario EPA</title>
</head>
<body style="font-family: Arial, sans-serif; text-align:center; margin-top:100px;">
    <h1>¡Hola Mundo!</h1>
    <p>Si estás viendo esta página, PHP y Apache están funcionando correctamente.</p>

    <hr>

    <p><strong>Servidor:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></p>
    <p><strong>Host solicitado:</strong> <?php echo $_SERVER['HTTP_HOST']; ?></p>
    <p><strong>Fecha y hora:</strong> <?php echo date('d-m-Y H:i:s'); ?></p>
</body>
</html>