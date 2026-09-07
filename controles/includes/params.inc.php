<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');
      
    define('LONGITUD_ACTIVIDAD', 52);
    define('CONTRATO_POR_DEFECTO', 5);
    
    $array_usuarios = array(
        'ADM' => 'Administrador',
        'AUD' => 'Auditor',
        'USR' => 'Usuario',
        'VIS' => 'Visualizador'    
    );
    
    $array_estados = array(
        'PLA' => 'Planificado',
        'PEN' => 'Pendiente',
        'ALE' => 'Alertado',
        'ATR' => 'Atrasado',
        'REC' => 'Rechazado',
        'APR' => 'Aprobado'
    );
    
    $array_glosas = array(
        'PLA' => 'El control se encuentra dentro de la planificación anual, pero aun no se encuentra alertado.',
        'PEN' => 'Se ha ingresado información y se encuentra a la espera de la aprobación o rechazo por parte del usuario responsable.',
        'ALE' => 'El control se encuentra dentro de los plazos definidos para ingresar información, ya ha sido notificado por correo electrónico.',
        'ATR' => 'El control se encuentra fuera de los plazos definidos.',
        'REC' => 'El control ha sido rechazado por el usuario responsable. Se encuentra a la espera de nueva información.',
        'APR' => 'El control ha sido aprobado por el usuario responsable.'
    );
    
    $array_niveles = array(
        'APR' => 'Aprobador',
        'DIG' => 'Digitador',
        'ALE' => 'Alertado'
    );
    
    $array_meses = array(
        '1' => 'ENE',
        '2' => 'FEB',
        '3' => 'MAR',
        '4' => 'ABR',
        '5' => 'MAY',
        '6' => 'JUN',
        '7' => 'JUL',
        '8' => 'AGO',
        '9' => 'SEP',
        '10' => 'OCT',
        '11' => 'NOV',
        '12' => 'DIC'
    );
?>