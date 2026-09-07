<!DOCTYPE html>
<html lang="es">
<head>
    <title>Intranet EPA</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/bootstrap/puertoarica.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/fontawesome/font-awesome.min.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/jqueryui/jquery-ui.min.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/jqueryui/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/animate/animate.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/plugins/datatables/datatables.bootstrap.css">
    <link rel="stylesheet" href="<?= base_url(); ?>css/estilo.css?v=2">

	<script src="https://puertoarica.cl/graficas/js/libs/jquery/jquery.min.js"></script>
	<script src="https://puertoarica.cl/graficas/js/libs/bootstrap/bootstrap.min.js"></script>
	<script src="https://puertoarica.cl/graficas/js/libs/jqueryui/jquery-ui.min.js"></script>
    <script src="https://puertoarica.cl/graficas/js/libs/jqueryui/jquery.ui.datepicker-es.js"></script>
    <script src="https://puertoarica.cl/graficas/js/libs/jqueryui/jquery-ui-timepicker-addon.js"></script>
    <script src="https://puertoarica.cl/graficas/js/plugins/datatables/jquery.dataTables.js"></script>
    <script src="https://puertoarica.cl/graficas/js/plugins/datatables/dataTables.bootstrap.js"></script>
    <script src="https://puertoarica.cl/graficas/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="<?= base_url(); ?>js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url(); ?>js/main.js"></script>
</head>
  
<body class="body-login">
    
    <?php $this->load->view('alertas'); ?>
	
	<div class="container container-primer-inicio">
		
		<div class="panel panel-default no-border">
			<div class="panel-body text-center"> 

				<div class="col-xs-12">
					<form action="<?= site_url('login/primer_cambio_password'); ?>" method="post">
                        
                        <h2>PRIMER INICIO</h2>
                        <h4>Debe ingresar una nueva contraseña</h4>
			        	
                        <div class="alert alert-info ml-20 mr-20">
                            <strong>INFO!</strong> La Contraseña debe tener longitud mínima de 8 caracteres, debe contener al menos 1 letra y 1 número y además debe ser diferente a las utilizadas últimamente.
                        </div>
                        
                        <fieldset>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <div class="input-group pl-20 pr-20">
                                    <input type="password" name="password" maxlength="64" class="form-control" pattern="[a-zA-Z0-9]{8,}" required autofocus/>
                                    <span class="input-group-btn">
                                        <a class="btn btn-default btn-ver-password"><i class="fa fa-eye"></i></a>  
                                    </span>
                                </div>
                            </div>
                            <div class="form-group pl-20 pr-20">
                                <label>Re-ingrese Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password2" maxlength="64" class="form-control" pattern="[a-zA-Z0-9]{8,}" required/>
                                    <span class="input-group-btn">
                                        <a class="btn btn-default btn-ver-password"><i class="fa fa-eye"></i></a>  
                                    </span>
                                </div>
                            </div>
                            <div class="form-group pl-20 pr-20">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="<?= site_url('login/salir'); ?>"  class="btn btn-default btn-block submit"><i class="fa fa-reply"></i> Cancelar y volver</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-check"></i> Cambiar</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
			        </form>
				</div>
			</div>
			<div class="panel-footer text-center small">
                <a href="mailto:hmorales@puertoarica.cl?subject=Problemas%20para%20ingresar%20a%20Intranet">
                    ¿Problemas para ingresar?
                </a>
            </div>
		</div>
	</div>
	
	<div class="container text-center mt-20">
		<p style="color: #FFF;"><small>Empresa Portuaria Arica &copy; <?= Date('Y'); ?> - <a href="https://www.google.cl/maps/place/M%C3%A1ximo+Lira+389,+Arica,+Regi%C3%B3n+de+Arica+y+Parinacota/@-18.476221,-70.3212228,17z/data=!3m1!4b1!4m2!3m1!1s0x915aa9919caea8d3:0x7789ee7a703f8688" target="_blank">Avda. Máximo Lira #389, Arica</a> - Fono: <a href="tel:+56582593400">(+5658) 2593400</a></small></p>
	</div>
</body>
</html>