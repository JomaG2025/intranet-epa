<?php
// /intranet/proyectos/gantt_prueba.php
include_once 'header_local.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carta Gantt - Gestión EPA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">
    
    <style>
        body { background-color: #f8f8f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        
        /* Estilos del Sidebar */
        #sidebar-simulado { 
            width: 250px; position: fixed; height: 100%; background: #2c3e50; color: white; padding-top: 20px; z-index: 100; left: 0; top: 0;
        }
        #sidebar-simulado a { color: #d1d1d1; padding: 15px 20px; display: block; text-decoration: none; border-bottom: 1px solid #34495e; font-size: 14px; }
        #sidebar-simulado a:hover { background: #1a252f; color: white; text-decoration: none; }
        .user-panel { padding: 20px; text-align: center; border-bottom: 1px solid #3e4f5f; margin-bottom: 10px; }
        .user-panel img { border: 2px solid #34495e; width: 60px !important; height: 60px !important; margin-bottom: 10px; }
        
        /* Contenedor del Contenido */
        #page-wrapper { margin-left: 250px; padding: 25px; background: white; min-height: 100vh; }
        .page-header { border-bottom: 2px solid #eee; margin-bottom: 25px; font-weight: 800; color: #333; }
        
        /* Contenedor del Gantt */
        .gantt-container { overflow: auto; border: 1px solid #ddd; border-radius: 4px; padding: 10px; background: white; }
    </style>
</head>
<body>

<div id="wrapper">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-tasks text-primary"></i> Planificación Temporal (Gantt)</h1>
            </div>
        </div>

        <div class="row" style="margin-bottom: 20px;">
            <div class="col-lg-12">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" onclick="change_view('Day')">Día</button>
                    <button type="button" class="btn btn-default" onclick="change_view('Week')">Semana</button>
                    <button type="button" class="btn btn-default" onclick="change_view('Month')">Mes</button>
                </div>
                <span class="text-muted pull-right" style="margin-top: 10px;">Vista: Proyecto Demo EPA</span>
            </div>
        </div>

        <div class="gantt-container">
            <div id="gantt"></div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.umd.js"></script>

<script>
    // 1. Datos de prueba que cumplen con tus requisitos
    const tasks = [
        {
            id: 'T-1',
            name: 'Análisis de Requerimientos',
            start: '2026-04-20',
            end: '2026-04-25',
            progress: 100,
            dependencies: ''
        },
        {
            id: 'T-2',
            name: 'Diseño de Infraestructura',
            start: '2026-04-26',
            end: '2026-05-05',
            progress: 45,
            dependencies: 'T-1' // Dependencia definida
        },
        {
            id: 'H-1',
            name: 'HITO: Aprobación Técnica',
            start: '2026-05-06',
            end: '2026-05-06',
            progress: 0,
            dependencies: 'T-2',
            custom_class: 'gantt-milestone' // Clase para diferenciar hitos
        },
        {
            id: 'T-3',
            name: 'Ejecución de Obra Civiles',
            start: '2026-05-07',
            end: '2026-05-25',
            progress: 10,
            dependencies: 'H-1'
        }
    ];

    // 2. Inicialización del Gantt
    const gantt = new Gantt("#gantt", tasks, {
        language: 'es',
        view_mode: 'Day',
        on_click: function (task) {
            console.log("Clic en:", task.name);
        },
        on_date_change: function(task, start, end) {
            console.log(task.name + " cambió a: " + start + " - " + end);
        },
        on_progress_change: function(task, progress) {
            console.log(task.name + " nuevo progreso: " + progress + "%");
        }
    });

    // Función para cambiar el modo de vista
    function change_view(mode) {
        gantt.change_view_mode(mode);
    }
</script>

</body>
</html>