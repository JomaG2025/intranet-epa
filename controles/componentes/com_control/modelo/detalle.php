<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO'); 

require("includes/friendly.inc.php");       
    
    #CONTROL   
    $control = Control::detalle($_GET['id']);
    
    if( ! $control){ die('Control no existe!'); }
    
    $control['nombre_estado'] = $array_estados[$control['estado']];
    $control['descripcion_estado'] = $array_glosas[$control['estado']];
    
    switch($control['estado'])
    {
    case 'PLA':
    $control['tipoalerta'] = 'info';
    break;
    
    case 'PEN':
    $control['tipoalerta'] = 'warning';
    break;
     
        case 'ALE':
        $control['tipoalerta'] = 'warning';
        break;
         
        case 'ATR':
        $control['tipoalerta'] = 'danger';
        break;
         
        case 'REC':
        $control['tipoalerta'] = 'danger';
        break;
         
        case 'APR':
        $control['tipoalerta'] = 'success';
        break;
    }
    
    $template->assign('control', $control);
    
    $planificacion = Control::planificacion($control['actividad'], $control['ano']);
    foreach($array_meses as $key => $value)
    {
        foreach($planificacion as $value2)
        {
            if($value2['mes'] == $key)
            {
                $array_meses[$key] = '<a href="index.php?com=control&amp;accion=detalle&amp;id='.  $value2['id']  .'" title="Ver mes '.  $value  .'"><u>'.  $value  .'</u></a>';
            }
        }
        
        if($control['mes'] == $key)
        {
            $array_meses[$key] = '<span class="badge">'.  $value  .'</span>';
        }
    }
    
    
    #ACTIVIDAD
    require("componentes/com_actividad/clase/actividad.class.php");
    $actividad = Actividad::seleccionar($control['actividad']);
    $actividad['descripcion'] = html_entity_decode($actividad['descripcion']);
    $template->assign('actividad', $actividad);
    
    
    #GRUPO
    require("componentes/com_grupo/clase/grupo.class.php");
    $grupo = Grupo::seleccionar($actividad['grupo']);
    $template->assign('grupo', $grupo);
    
    
    #CONTRATO
    require("componentes/com_contrato/clase/contrato.class.php");
    $contrato = Contrato::seleccionar($actividad['contrato']);
    $template->assign('contrato', $contrato);
    
    
    #MANUAL
    require("componentes/com_manual/clase/manual.class.php");
    $manual = Manual::seleccionar($actividad['manual']);
    $template->assign('manual', $manual);
    
    
    #ADJUNTO
    require("componentes/com_adjunto/clase/adjunto.class.php");
    $adjuntos = Adjunto::listar($_GET['id']);  
    
    foreach($adjuntos as $key => $adjunto)
    {
    $adjunto['archivo'] = ($adjunto['archivo']) ? friendly($adjunto['nombre'])  .'.'.  $adjunto['extension'] : NULL;
    $adjuntos[$key]['archivo'] = $adjunto['archivo'];
    }
    
    $template->assign('adjuntos', $adjuntos);
    $otros_adjuntos = Adjunto::listar_otros($control['actividad'], $_GET['id']);
    $template->assign('otros_adjuntos', $otros_adjuntos);
    $ultimo_adjunto = Adjunto::seleccionar_ultimo($_GET['id']);
    $template->assign('ultimo_adjunto', $ultimo_adjunto);
    
    
    #PRIVILEGIO    
    require("componentes/com_privilegio/clase/privilegio.class.php");
    $responsables = Privilegio::seleccionar($actividad['id'], $control['ano']);
    $template->assign('responsables', $responsables);
        
    
    #REGISTRO
    require("componentes/com_registro/clase/registro.class.php");
    $registro = Registro::seleccionar($_GET['id']);
    $registro['estado'] = $array_estados[$registro['estado']];
    $template->assign('registro', $registro);
    
    
    #RESULTADO
    require("componentes/com_resultado/clase/resultado.class.php");    
    $resultado = Resultado::seleccionar($_GET['id']);
    $template->assign('resultado', $resultado);
    
    $template->assign('array_niveles' , $array_niveles);
    $template->assign('array_meses' , $array_meses);
    
?>