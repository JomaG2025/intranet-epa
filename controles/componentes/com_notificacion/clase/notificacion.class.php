<?php

class Notificacion{
       
        public static function atrasados()
        {
            $atrasados = array();
            
            $result=mysql_query("SELECT id FROM intranet_controles.scc_control
                                    WHERE fecha<CURDATE() AND estado!='APR' AND estado!='PEN' AND estado!='REC' AND estado!='ATR'");
            
            while($row=mysql_fetch_assoc($result))
            {
array_push($atrasados, $row);
}
            
            mysql_unbuffered_query("UPDATE intranet_controles.scc_control SET estado='ATR'
                                    WHERE fecha<CURDATE() AND estado!='APR' AND estado!='PEN' AND estado!='REC' AND estado!='ATR'");
            
            return $atrasados;
        }
        
        public static function alertados()
        {
            $alertados = array();
            
            $result = mysql_query("SELECT id FROM intranet_controles.scc_control 
                                    WHERE DATE_SUB(fecha, INTERVAL (alerta) DAY)<=(CURDATE()) AND estado!='APR' AND estado!='PEN' AND estado!='ATR' AND estado!='REC' AND estado!='ALE'");
            
            while($row=mysql_fetch_assoc($result))
            {
array_push($alertados, $row);
}  
            
            mysql_unbuffered_query("UPDATE intranet_controles.scc_control SET estado='ALE'
                                    WHERE DATE_SUB(fecha, INTERVAL (alerta) DAY)<=(CURDATE()) AND estado!='APR' AND estado!='PEN' AND estado!='ATR' AND estado!='REC' AND estado!='ALE'");
            
            return $alertados;
                     
        }          
                        
        public static function informe(){            
            
            $informe =  array();
            
            $result = mysql_query("SELECT C.id, C.actividad, C.fecha, C.estado, P.usuario, U.usuario AS nombre_usuario, A.nombre AS nombre_actividad, A.capitulo, N.nombre AS nombre_contrato 
                                    FROM intranet_controles.scc_control C 
                                    INNER JOIN intranet_controles.scc_privilegio P
                                    ON C.actividad = P.actividad
                                    INNER JOIN intranet_controles.scc_usuario U
                                    ON P.usuario = U.id
                                    INNER JOIN intranet_controles.scc_actividad A
                                    ON C.actividad = A.id
                                    INNER JOIN intranet_controles.scc_contrato N
                                    ON N.id = A.contrato
                                    WHERE A.activo = 1 AND DATE_SUB(C.fecha, INTERVAL (C.alerta) DAY)<=(CURDATE()) AND C.estado!='APR' AND C.estado!='PEN' AND P.ano = YEAR(C.fecha) AND U.activo = 1 AND N.activo = 1
                                    GROUP BY P.usuario, C.id
                                    ORDER BY A.capitulo");
                                    
            while($row=mysql_fetch_assoc($result))
            {
array_push($informe, $row);
}
               
            return $informe;            
        }
        
        public static function alerta()
        {                
            $alerta =  array();
            
            $result = mysql_query("SELECT C.id, C.actividad, C.fecha, C.estado, P.usuario, U.usuario AS nombre_usuario, A.nombre AS nombre_actividad, A.capitulo, N.nombre AS nombre_contrato
                                    FROM intranet_controles.scc_control C 
                                    INNER JOIN intranet_controles.scc_privilegio P
                                    ON C.actividad = P.actividad
                                    INNER JOIN intranet_controles.scc_usuario U
                                    ON P.usuario = U.id
                                    INNER JOIN intranet_controles.scc_actividad A
                                    ON C.actividad = A.id
                                    INNER JOIN intranet_controles.scc_contrato N
                                    ON N.id = A.contrato
                                    WHERE A.activo = 1 AND DATE_SUB(C.fecha, INTERVAL (C.alerta) DAY)<=(CURDATE()) AND C.estado!='APR' AND C.estado!='PEN' AND C.estado!='ATR' AND C.estado!='REC' AND C.estado!='ALE' AND P.ano = YEAR(C.fecha) AND U.activo = 1 AND N.activo = 1
                                    GROUP BY P.usuario, C.id 
                                    ORDER BY A.capitulo");
                                    
            while($row=mysql_fetch_assoc($result))
            {
array_push($alerta, $row);
}
               
            return $alerta; 
                 
        } 
        public static function notificar_aprobadores($id_control)
        {
            $result = mysql_query("SELECT C.id, C.actividad, C.fecha,
                                          A.nombre AS nombre_actividad, A.capitulo,
                                          N.nombre AS nombre_contrato,
                                          U.usuario AS nombre_usuario
                                   FROM ".DB_PREFIX."control C
                                   INNER JOIN ".DB_PREFIX."actividad A ON C.actividad = A.id
                                   INNER JOIN ".DB_PREFIX."contrato N ON N.id = A.contrato
                                   INNER JOIN ".DB_PREFIX."privilegio P ON P.actividad = C.actividad AND P.apr = 1 AND P.ano = YEAR(C.fecha)
                                   INNER JOIN ".DB_PREFIX."usuario U ON P.usuario = U.id
                                   WHERE C.id = '".(int)$id_control."' AND U.eliminado = 0 AND U.activo = 1");

            $aprobadores = array();
            $control_info = null;

            while ($row = mysql_fetch_assoc($result)) {
                if (!$control_info) {
                    $control_info = array(
                        'id'               => $row['id'],
                        'fecha'            => $row['fecha'],
                        'nombre_actividad' => $row['nombre_actividad'],
                        'capitulo'         => $row['capitulo'],
                        'nombre_contrato'  => $row['nombre_contrato']
                    );
                }
                array_push($aprobadores, $row['nombre_usuario']);
            }

            if (!$control_info || empty($aprobadores)) return;

            if (!class_exists('PHPMailer')) {
                require('includes/class.phpmailer.php');
                require('includes/class.smtp.php');
            }

            if (!class_exists('Usuario')) {
                require('componentes/com_usuario/clase/usuario.class.php');
            }

            foreach ($aprobadores as $nombre_usuario) {
                $usuario = Usuario::seleccionar_intranet($nombre_usuario);

                if (!$usuario || $usuario['activo'] != 1 || $usuario['eliminado'] != 0) continue;
                if (empty($usuario['email'])) continue;

                $nombre  = utf8_encode($usuario['nombre']);
                $sexo    = $usuario['sexo'];
                $tratamiento = ($sexo == 'M') ? 'Estimado' : 'Estimada';

                $asunto = 'Evidencia disponible para revisión – '.$control_info['capitulo'].' '.$control_info['nombre_actividad'];

                $cuerpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width">
</head>
<body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;box-sizing:border-box;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:normal;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100% !important;">
  <table class="body" style="Margin:0;background:#f1f4f8;border-collapse:collapse;border-spacing:0;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:normal;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%;">
    <tbody>
      <tr>
        <td align="center" valign="top" style="Margin:0 auto;border-collapse:collapse !important;color:#333333;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:normal;line-height:1.3;margin:0 auto;padding:0;text-align:center;vertical-align:top;word-wrap:break-word;">
          <center style="min-width:580px;width:100%;">

            <table align="center" style="Margin:0 auto;background:#25336d;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%;">
              <tbody><tr><td style="padding:10px;text-align:center;vertical-align:top;">
                <table style="Margin:0 auto;background:transparent;border-collapse:collapse;border-spacing:0;margin:0 auto;padding:0;text-align:inherit;vertical-align:top;width:580px;">
                  <tbody><tr><td style="padding:0;text-align:left;vertical-align:top;">
                    <table style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%;">
                      <tbody><tr>
                        <th style="width:298px;padding:0;text-align:left;">
                          <img src="https://puertoarica.cl/graficas/imagenes/logo-inverted.png" alt="" style="clear:both;display:block;max-width:100%;outline:none;text-decoration:none;width:auto;">
                        </th>
                        <th style="width:298px;padding:0;text-align:right;">
                          <p style="color:#fff;font-family:Helvetica,Arial,sans-serif;font-size:16px;margin:0;padding-top:15px;text-align:right;">CONTROL Y ALERTAS</p>
                        </th>
                      </tr></tbody>
                    </table>
                  </td></tr></tbody>
                </table>
              </td></tr></tbody>
            </table>

            <table style="Margin:0 auto;background:#fefefe;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580px;">
              <tbody><tr><td style="padding:0;text-align:left;vertical-align:top;word-wrap:break-word;">
                <br>
                <table style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%;">
                  <tbody><tr>
                    <th style="Margin:0 auto;padding:0 16px 16px 16px;text-align:left;width:564px;">
                      <h1 style="color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:28px;font-weight:normal;line-height:1.3;margin:0 0 10px 0;word-wrap:normal;">Evidencia disponible para revisión</h1>
                      <p style="color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:normal;line-height:1.3;margin:0 0 10px 0;">'.$tratamiento.' <strong>'.utf8_decode($nombre).'</strong>, se ha subido nueva evidencia en el Sistema de Control y Alertas y está disponible para su revisión y aprobación.</p>
                      <br>
                      <p style="color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:normal;line-height:1.3;margin:0 0 10px 0;">Para ingresar al sistema haga click <a href="https://intranet.puertoarica.cl/">aquí</a>.</p>
                      <br>
                      <table style="border-collapse:collapse;width:100%;">
                        <thead style="color:#FFF;background-color:#2980b9;">
                          <tr>
                            <th style="font-family:Helvetica,Arial,sans-serif;font-size:12px;border-right:solid 1px #FFF;padding:5px;text-align:center;vertical-align:middle;font-weight:bold;">PLANIFICACIÓN</th>
                            <th style="font-family:Helvetica,Arial,sans-serif;font-size:12px;border-right:solid 1px #FFF;padding:5px;text-align:center;vertical-align:middle;font-weight:bold;">ACTIVIDAD</th>
                            <th style="font-family:Helvetica,Arial,sans-serif;font-size:12px;border-right:solid 1px #FFF;padding:5px;text-align:center;vertical-align:middle;font-weight:bold;">FECHA COMPROMISO</th>
                            <th style="font-family:Helvetica,Arial,sans-serif;font-size:12px;padding:5px;text-align:center;vertical-align:middle;font-weight:bold;">ESTADO</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr style="background:#B2DFF5;">
                            <td style="font-family:Helvetica,Arial,sans-serif;font-size:12px;color:#333333;vertical-align:middle;padding:5px;border-right:solid 1px #FFF;border-bottom:solid 1px #FFF;">'.$control_info['nombre_contrato'].'</td>
                            <td style="font-family:Helvetica,Arial,sans-serif;font-size:12px;color:#333333;vertical-align:middle;padding:5px;border-right:solid 1px #FFF;border-bottom:solid 1px #FFF;"><a href="https://intranet.puertoarica.cl/controles/index.php?com=control&accion=detalle&id='.$control_info['id'].'">'.$control_info['capitulo'].' '.$control_info['nombre_actividad'].'</a></td>
                            <td style="font-family:Helvetica,Arial,sans-serif;font-size:12px;text-align:center;color:#333333;vertical-align:middle;padding:5px;border-right:solid 1px #FFF;border-bottom:solid 1px #FFF;">'.$control_info['fecha'].'</td>
                            <td style="font-family:Helvetica,Arial,sans-serif;font-size:12px;text-align:center;color:#333333;vertical-align:middle;padding:5px;border-bottom:solid 1px #FFF;">Pendiente</td>
                          </tr>
                        </tbody>
                      </table>
                    </th>
                  </tr></tbody>
                </table>

                <table align="center" style="background:#f1f4f8;border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%;">
                  <tbody><tr><td style="padding:0;text-align:left;vertical-align:top;word-wrap:break-word;">
                    <br>
                    <table style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%;">
                      <tbody><tr>
                        <th style="Margin:0 auto;padding:0 8px 16px 16px;text-align:left;width:225px;">
                          <img src="https://puertoarica.cl/graficas/imagenes/logo-default.png" alt="" style="clear:both;display:block;max-width:100%;outline:none;text-decoration:none;width:auto;">
                        </th>
                        <th style="Margin:0 auto;padding:0 16px 16px 8px;text-align:left;width:322px;">
                          <h5 style="color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;line-height:1.3;margin:0 0 10px 0;">Empresa Portuaria Arica</h5>
                          <p style="color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:normal;line-height:1.3;margin:0 0 10px 0;">
                            Fono: <a href="tel:+56582593400" style="color:#25336d;text-decoration:none;">(+5658) 2593400</a><br>
                            E-mail: <a href="mailto:puertoarica@puertoarica.cl" style="color:#25336d;text-decoration:none;">puertoarica@puertoarica.cl</a><br>
                            Sitio Web: <a href="https://puertoarica.cl" style="color:#25336d;text-decoration:none;">https://puertoarica.cl</a>
                          </p>
                        </th>
                      </tr></tbody>
                    </table>
                  </td></tr></tbody>
                </table>

              </td></tr></tbody>
            </table>

          </center>
        </td>
      </tr>
    </tbody>
  </table>
</body>
</html>';

                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'no-responder@puertoarica.com';
                $mail->Password   = 'wluc gnyv hbok xqks';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('no-responder@puertoarica.com', 'Control y alertas');
                $mail->addAddress($usuario['email']);
                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body    = $cuerpo;

                $mail->send();
            }
        }
}

?>