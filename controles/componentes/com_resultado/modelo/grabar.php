<?php defined('DIRECT_ACCESS') or die('ACCESO RESTRINGIDO');        
            
    if($id = Resultado::insertar($_POST))
    {
        require("componentes/com_control/clase/control.class.php");
        require("componentes/com_registro/clase/registro.class.php");
        require("componentes/com_auditoria/clase/auditoria.class.php");
        require("componentes/com_actividad/clase/actividad.class.php");
        require("componentes/com_privilegio/clase/privilegio.class.php");
        require("includes/class.phpmailer.php");
        require("includes/class.smtp.php");
        
        Registro::actualizar($_POST['control'] , $_POST['estado']);
        Control::cambiar_estado($_POST['control'] , $_POST['estado']); 
        Auditoria::insertar($_GET['com'], 'estado '.  $_POST['estado'], $id );
        Auditoria::insertar('control', 'estado '.  $_POST['estado'], $_POST['control']);
        
        $control = Control::detalle($_POST['control']);
        $control['nombre_estado'] = $array_estados[$control['estado']];
        $actividad = Actividad::seleccionar($control['actividad']);
        $responsables = Privilegio::seleccionar($actividad['id'], $control['ano']);
        
        foreach($responsables as $key => $responsable)
        {
            $usuario = json_decode(file_get_contents(HTTP_INTRANET . 'index.php/api/usuario/'. $responsable['nombre']), TRUE);
        
            if($usuario['activo'] == 1)
            {
                $responsables[$key]['nombre'] = utf8_encode($usuario['nombre']);
                $responsables[$key]['email'] = $usuario['email'];
                $responsables[$key]['sexo'] = $usuario['sexo'];
            } 
        }
        
        foreach($responsables as $responsable){
                        
            $destinatario = $responsable['email'];
            $asunto = 'Control '. strtolower($control['nombre_estado']);        
            $cuerpo = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">

                <head>
                  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                  <meta name="viewport" content="width=device-width">

                </head>

                <body style="-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; Margin: 0; box-sizing: border-box; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; min-width: 100%; padding: 0; text-align: left; width: 100% !important;">
                  <style>
                    @media only screen {
                      html {
                        min-height: 100%;
                        background: #f3f3f3;
                      }
                    }

                    @media only screen and (max-width: 596px) {
                      .small-float-center {
                        margin: 0 auto !important;
                        float: none !important;
                        text-align: center !important;
                      }
                      .small-text-center {
                        text-align: center !important;
                      }
                      .small-text-left {
                        text-align: left !important;
                      }
                      .small-text-right {
                        text-align: right !important;
                      }
                    }

                    @media only screen and (max-width: 596px) {
                      table.body table.container .hide-for-large {
                        display: block !important;
                        width: auto !important;
                        overflow: visible !important;
                      }
                    }

                    @media only screen and (max-width: 596px) {
                      table.body table.container .row.hide-for-large,
                      table.body table.container .row.hide-for-large {
                        display: table !important;
                        width: 100% !important;
                      }
                    }

                    @media only screen and (max-width: 596px) {
                      table.body table.container .show-for-large {
                        display: none !important;
                        width: 0;
                        mso-hide: all;
                        overflow: hidden;
                      }
                    }

                    @media only screen and (max-width: 596px) {
                      table.body img {
                        width: auto !important;
                        height: auto !important;
                      }
                      table.body center {
                        min-width: 0 !important;
                      }
                      table.body .container {
                        width: 95% !important;
                      }
                      table.body .columns,
                      table.body .column {
                        height: auto !important;
                        -moz-box-sizing: border-box;
                        -webkit-box-sizing: border-box;
                        box-sizing: border-box;
                        padding-left: 16px !important;
                        padding-right: 16px !important;
                      }
                      table.body .columns .column,
                      table.body .columns .columns,
                      table.body .column .column,
                      table.body .column .columns {
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                      }
                      table.body .collapse .columns,
                      table.body .collapse .column {
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                      }
                      td.small-1,
                      th.small-1 {
                        display: inline-block !important;
                        width: 8.33333% !important;
                      }
                      td.small-2,
                      th.small-2 {
                        display: inline-block !important;
                        width: 16.66667% !important;
                      }
                      td.small-3,
                      th.small-3 {
                        display: inline-block !important;
                        width: 25% !important;
                      }
                      td.small-4,
                      th.small-4 {
                        display: inline-block !important;
                        width: 33.33333% !important;
                      }
                      td.small-5,
                      th.small-5 {
                        display: inline-block !important;
                        width: 41.66667% !important;
                      }
                      td.small-6,
                      th.small-6 {
                        display: inline-block !important;
                        width: 50% !important;
                      }
                      td.small-7,
                      th.small-7 {
                        display: inline-block !important;
                        width: 58.33333% !important;
                      }
                      td.small-8,
                      th.small-8 {
                        display: inline-block !important;
                        width: 66.66667% !important;
                      }
                      td.small-9,
                      th.small-9 {
                        display: inline-block !important;
                        width: 75% !important;
                      }
                      td.small-10,
                      th.small-10 {
                        display: inline-block !important;
                        width: 83.33333% !important;
                      }
                      td.small-11,
                      th.small-11 {
                        display: inline-block !important;
                        width: 91.66667% !important;
                      }
                      td.small-12,
                      th.small-12 {
                        display: inline-block !important;
                        width: 100% !important;
                      }
                      .columns td.small-12,
                      .column td.small-12,
                      .columns th.small-12,
                      .column th.small-12 {
                        display: block !important;
                        width: 100% !important;
                      }
                      table.body td.small-offset-1,
                      table.body th.small-offset-1 {
                        margin-left: 8.33333% !important;
                        Margin-left: 8.33333% !important;
                      }
                      table.body td.small-offset-2,
                      table.body th.small-offset-2 {
                        margin-left: 16.66667% !important;
                        Margin-left: 16.66667% !important;
                      }
                      table.body td.small-offset-3,
                      table.body th.small-offset-3 {
                        margin-left: 25% !important;
                        Margin-left: 25% !important;
                      }
                      table.body td.small-offset-4,
                      table.body th.small-offset-4 {
                        margin-left: 33.33333% !important;
                        Margin-left: 33.33333% !important;
                      }
                      table.body td.small-offset-5,
                      table.body th.small-offset-5 {
                        margin-left: 41.66667% !important;
                        Margin-left: 41.66667% !important;
                      }
                      table.body td.small-offset-6,
                      table.body th.small-offset-6 {
                        margin-left: 50% !important;
                        Margin-left: 50% !important;
                      }
                      table.body td.small-offset-7,
                      table.body th.small-offset-7 {
                        margin-left: 58.33333% !important;
                        Margin-left: 58.33333% !important;
                      }
                      table.body td.small-offset-8,
                      table.body th.small-offset-8 {
                        margin-left: 66.66667% !important;
                        Margin-left: 66.66667% !important;
                      }
                      table.body td.small-offset-9,
                      table.body th.small-offset-9 {
                        margin-left: 75% !important;
                        Margin-left: 75% !important;
                      }
                      table.body td.small-offset-10,
                      table.body th.small-offset-10 {
                        margin-left: 83.33333% !important;
                        Margin-left: 83.33333% !important;
                      }
                      table.body td.small-offset-11,
                      table.body th.small-offset-11 {
                        margin-left: 91.66667% !important;
                        Margin-left: 91.66667% !important;
                      }
                      table.body table.columns td.expander,
                      table.body table.columns th.expander {
                        display: none !important;
                      }
                      table.body .right-text-pad,
                      table.body .text-pad-right {
                        padding-left: 10px !important;
                      }
                      table.body .left-text-pad,
                      table.body .text-pad-left {
                        padding-right: 10px !important;
                      }
                      table.menu {
                        width: 100% !important;
                      }
                      table.menu td,
                      table.menu th {
                        width: auto !important;
                        display: inline-block !important;
                      }
                      table.menu.vertical td,
                      table.menu.vertical th,
                      table.menu.small-vertical td,
                      table.menu.small-vertical th {
                        display: block !important;
                      }
                      table.menu[align="center"] {
                        width: auto !important;
                      }
                    }

                    @media only screen {
                      html {
                        min-height: 100%;
                        background: #f1f4f8 !important;
                      }
                    }
                  </style>
                  <table class="body" style="Margin: 0; background: #f1f4f8; border-collapse: collapse; border-spacing: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; height: 100%; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                    <tbody>
                      <tr style="padding: 0; text-align: left; vertical-align: top;">
                        <td class="float-center" align="center" valign="top" style="-moz-hyphens: auto; -webkit-hyphens: auto; Margin: 0 auto; border-collapse: collapse !important; color: #333333; float: none; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0 auto; padding: 0; text-align: center; vertical-align: top; word-wrap: break-word;">
                          <center style="min-width: 580px; width: 100%;">
                            <table class="wrapper header float-center" align="center" style="Margin: 0 auto; background: #25336d; border-collapse: collapse; border-spacing: 0; float: none; margin: 0 auto; padding: 0; text-align: center; vertical-align: top; width: 100%;">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top;">
                                  <td class="wrapper-inner" style="-moz-hyphens: auto; -webkit-hyphens: auto; Margin: 0; border-collapse: collapse !important; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 10px; text-align: center; vertical-align: top; word-wrap: break-word;">
                                    <table class="container" style="Margin: 0 auto; background: transparent; border-collapse: collapse; border-spacing: 0; margin: 0 auto; padding: 0; text-align: inherit; vertical-align: top; width: 580px;">
                                      <tbody>
                                        <tr style="padding: 0; text-align: left; vertical-align: top;">
                                          <td style="-moz-hyphens: auto; -webkit-hyphens: auto; Margin: 0; border-collapse: collapse !important; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word;">
                                            <table class="row collapse" style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%;">
                                              <tbody>
                                                <tr style="padding: 0; text-align: left; vertical-align: top;">
                                                  <th class="small-6 large-6 columns first" style="Margin: 0 auto; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; padding-bottom: 0; padding-left: 0; padding-right: 0; text-align: left; width: 298px;">
                                                    <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                                                      <tbody>
                                                        <tr style="padding: 0; text-align: left; vertical-align: top;">
                                                          <th style="Margin: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left;"> <img src="https://puertoarica.cl/graficas/imagenes/logo-inverted.png" alt="" style="-ms-interpolation-mode: bicubic; clear: both; display: block; max-width: 100%; outline: none; text-decoration: none; width: auto;">                                            </th>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                  </th>
                                                  <th class="small-6 large-6 columns last" style="Margin: 0 auto; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; padding-bottom: 0; padding-left: 0; padding-right: 0; text-align: left; width: 298px;">
                                                    <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                                                      <tbody>
                                                        <tr style="padding: 0; text-align: left; vertical-align: top;">
                                                          <th style="Margin: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left;">
                                                            <p class="text-right" style="Margin: 0; Margin-bottom: 10px; color: #fff; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; padding-top: 15px; text-align: right;">CONTROL ALERTAS</p>
                                                          </th>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                  </th>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="container float-center" style="Margin: 0 auto; background: #fefefe; border-collapse: collapse; border-spacing: 0; float: none; margin: 0 auto; padding: 0; text-align: center; vertical-align: top; width: 580px;">
                              <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top;">
                                  <td style="-moz-hyphens: auto; -webkit-hyphens: auto; Margin: 0; border-collapse: collapse !important; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word;">
                                    <br>
                                    <table class="row" style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%;">
                                      <tbody>
                                        <tr style="padding: 0; text-align: left; vertical-align: top;">
                                          <th class="small-12 large-12 columns first last" style="Margin: 0 auto; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; padding-bottom: 16px; padding-left: 16px; padding-right: 16px; text-align: left; width: 564px;">
                                            <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                                              <tbody>
                                                <tr style="padding: 0; text-align: left; vertical-align: top;">
                                                  <th style="Margin: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left;">
                                                    <h1 style="Margin: 0; Margin-bottom: 10px; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 34px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: left; word-wrap: normal;">Notificación de Control '. strtolower($control['nombre_estado']) .'</h1>
                                                    <p style="Margin: 0; Margin-bottom: 10px; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: left;">'. (($responsable['sexo'] == 'M') ? 'Estimado' : 'Estimada').' <strong>' . utf8_decode($responsable['nombre'])  .'</strong>, el punto de control <u>'. $actividad['capitulo'] .' '. $actividad['nombre'] .'</u> con fecha comprometida '. $control['fecha'] .' ha sido <strong>'. strtolower($control['nombre_estado']) .'</strong> en el Sistema de Control y alertas por el usuario responsable.</p>
                                                    <br>
                                                    <p style="Margin: 0; Margin-bottom: 10px; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: left;">Para autentificarse en el Sistema de Control y alertas debe hacerlo a través de la Intranet de Empresa Portuaria Arica haciendo click <a href="https://intranet.puertoarica.cl/">aquí</a>.</p>

                                                    </th>
                                              <th class="expander" style="Margin: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0 !important; text-align: left; visibility: hidden; width: 0;"></th>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </th>
                                    </tr>
                                  </tbody>
                                </table>
                                <table class="wrapper secondary" align="center" style="background: #f1f4f8; border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                                  <tbody>
                                    <tr style="padding: 0; text-align: left; vertical-align: top;">
                                      <td class="wrapper-inner" style="-moz-hyphens: auto; -webkit-hyphens: auto; Margin: 0; border-collapse: collapse !important; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word;">
                                        <br>
                                        <table class="row" style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%;">
                                          <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top;">
                                              <th class="small-5 large-5 columns first" style="Margin: 0 auto; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; padding-bottom: 16px; padding-left: 16px; padding-right: 8px; text-align: left; width: 225.66667px;">
                                                <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                                                  <tbody>
                                                    <tr style="padding: 0; text-align: left; vertical-align: top;">
                                                      <th style="Margin: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left;">
                                                        <img src="https://puertoarica.cl/graficas/imagenes/logo-default.png" alt="" style="-ms-interpolation-mode: bicubic; clear: both; display: block; max-width: 100%; outline: none; text-decoration: none; width: auto;">
                                                      </th>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </th>
                                              <th class="small-7 large-7 columns last" style="Margin: 0 auto; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; padding-bottom: 16px; padding-left: 8px; padding-right: 16px; text-align: left; width: 322.33333px;">
                                                <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                                                  <tbody>
                                                    <tr style="padding: 0; text-align: left; vertical-align: top;">
                                                      <th style="Margin: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left;">
                                                        <h5 style="Margin: 0; Margin-bottom: 10px; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 20px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: left; word-wrap: normal;">Empresa Portuaria Arica</h5>
                                                        <p style="Margin: 0; Margin-bottom: 10px; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: left;">Fono : <a href="tel:+56582593400" style="Margin: 0; color: #25336d; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left; text-decoration: none;">(+5658) 2593400</a><br>                                              E-mail: <a href="mailto:puertoarica@puertoarica.cl" style="Margin: 0; color: #25336d; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left; text-decoration: none;">puertoarica@puertoarica.cl</a><br>                                              Sitio Web: <a href="https://puertoarica.cl" style="Margin: 0; color: #25336d; font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left; text-decoration: none;">https://puertoarica.cl</a></p>
                                                      </th>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </th>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <table class="wrapper secondary" align="center" style="background: #f1f4f8; border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                                  <tbody>
                                    <tr style="padding: 0; text-align: left; vertical-align: top;">
                                      <td class="wrapper-inner" style="-moz-hyphens: auto; -webkit-hyphens: auto; Margin: 0; border-collapse: collapse !important; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word;">
                                        <table class="row" style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%;">
                                          <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top;">
                                              <th class="small-12 large-12 columns first last" style="Margin: 0 auto; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; padding-bottom: 16px; padding-left: 16px; padding-right: 16px; text-align: left; width: 564px;">
                                                <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
                                                  <tbody>
                                                    <tr style="padding: 0; text-align: left; vertical-align: top;">
                                                      <th style="Margin: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left;">
                                                        <p style="Margin: 0; Margin-bottom: 10px; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: justify;"><small style="color: #cacaca; font-size: 80%;">La información contenida en esta transmisión es confidencial y no puede ser usada o difundida por personas distintas a su(s) destinatario (s). El uso no autorizado de la información contenida en esta transmisión puede ser sancionado criminalmente de conformidad con la ley chilena. Si ha recibido esta transmisión por error por favor destrúyala y notifique al remitente. Atendiendo que no existe certidumbre que el presente mensaje no será modificado como resultado de su transmisión por correo electrónico, nuestra Empresa, no será responsable si el contenido del mismo ha sido modificado.</small></p>
                                                        <p style="Margin: 0; Margin-bottom: 10px; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; margin-bottom: 10px; padding: 0; text-align: justify;"><small style="color: #cacaca; font-size: 80%;">Cualquier opinión expresada en este mensaje pertenece únicamente al autor remitente y no representa necesariamente la opinión de nuestra Empresa, a no ser que expresamente se diga y el remitente esté autorizado para hacerlo. Nuestra Empresa, no se hace responsable de las alteraciones que pudieran hacerse al mensaje una vez enviado.</small></p>
                                                      </th>
                                                      <th class="expander" style="Margin: 0; color: #333333; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0 !important; text-align: left; visibility: hidden; width: 0;"></th>
                                                    </tr>
                                                  </tbody>
                                                </table>
                                              </th>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </center>
                    </td>
                  </tr>
                </tbody>
              </table>

            </body>

            </html>';
            
            $mail = new PHPMailer;
            $mail->isSendmail();
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('no-responder@puertoarica.com', 'Control y alertas');
            $mail->addAddress($destinatario);
            //$mail->addCC('hmorales@puertoarica.cl');
            $mail->isHTML(true);

            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;

            $mail->send();
            
        }
    }
              
    header("Location: index.php?com=control&accion=detalle&id=".  $_POST['control']  ."");
  
?>