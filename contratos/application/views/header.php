<!DOCTYPE html>
<html lang="es">
<head>
    <title>Contratos</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/bootstrap/puertoarica.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/fontawesome/font-awesome.min.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/jqueryui/jquery-ui.min.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/jqueryui/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/libs/animate/animate.css">
    <link rel="stylesheet" href="https://puertoarica.cl/graficas/css/plugins/datatables/datatables.bootstrap.css">
    <link rel="stylesheet" href="<?= base_url(); ?>css/estilo.css">

	<script src="https://puertoarica.cl/graficas/js/libs/jquery/jquery.min.js"></script>
	<script src="https://puertoarica.cl/graficas/js/libs/bootstrap/bootstrap.min.js"></script> 
    <script src="https://puertoarica.cl/graficas/js/libs/jqueryui/jquery-ui.min.js"></script>
    <script src="https://puertoarica.cl/graficas/js/libs/jqueryui/jquery.ui.datepicker-es.js"></script>
    <script src="https://puertoarica.cl/graficas/js/libs/jqueryui/jquery-ui-timepicker-addon.js"></script>
    <script src="https://puertoarica.cl/graficas/js/plugins/datatables/jquery.dataTables.js"></script>
    <script src="https://puertoarica.cl/graficas/js/plugins/datatables/dataTables.bootstrap.js"></script>
    <script src="https://puertoarica.cl/graficas/js/plugins/datatables/dataTables.spanish.lang.js"></script>
    <script src="https://puertoarica.cl/graficas/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="<?= base_url(); ?>js/plugins/jqueryrut/jquery.Rut.min.js"></script>
    <script src="<?= base_url(); ?>js/plugins/accounting/accounting.min.js"></script>
    <script src="<?= base_url(); ?>js/main.js"></script>
    
</head>
<body>
    
    <?php $this->load->view('alertas'); ?>

	<div id="wrapper">

		<nav class="navbar navbar-default navbar-fixed-top">
        
	        <div class="navbar-header">
	        	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
	            	<span class="sr-only">Navegación</span>
	            	<i class="fa fa-bars"></i>
	            </button>
	            <a class="navbar-brand" href="<?= site_url(''); ?>">
			        <i class="fa fa-briefcase"></i> Contratos
			    </a>
	        </div>
	
	        <div class="collapse navbar-collapse navbar-ex1-collapse">
	        	
		    	<ul class="nav navbar-nav side-nav">
			    	
			    	<li class="nav-user hidden-xs">
			    		<div class="row">
				    		<div class="col-sm-4">
					    		<?php if($this->session->userdata('sexo') == 'F'){ ?>
			    				<img src="data:image/png;base64,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" class="img-responsive img-circle" alt=""/>
								<?php }else{ ?>
			    				<img src="data:image/png;base64,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" class="img-responsive img-circle" alt=""/>
								<?php } ?>
			    			</div>
			    			<div class="col-sm-8 nombre">
			    				<span><?= $this->session->userdata('nombre'); ?></span><br />
                                <span><?= $this->session->userdata('privilegio'); ?></span>
			    			</div>
			    		</div>
			    	</li>
			    	
	            	<li class="<?= (( ! $this->uri->segment(1)) OR ($this->uri->segment(1) == 'inicio')) ? 'active': ''; ?>"><a href="<?= site_url(''); ?>"><i class="fa fa-home"></i> Inicio</a></li>
                    <li class="dropdown <?= ($this->uri->segment(1) == 'contratos') ? 'active open': ''; ?>">
		            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-briefcase"></i> Contratos &nbsp;<i class="fa fa-caret-down"></i></a>
		            	<ul class="dropdown-menu">
			            	<li><a href="<?= site_url('contratos'); ?>">Todos los Contratos</a></li>
			            	<li><a href="<?= site_url('contratos/alertados'); ?>">Alertados</a></li>
			            	<li><a href="<?= site_url('contratos/vencidos'); ?>">Vencidos</a></li>
			            </ul>
			        </li>
                    <li class="dropdown <?= ($this->uri->segment(1) == 'reporte') ? 'active open': ''; ?>">
		            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-line-chart"></i> Reportes &nbsp;<i class="fa fa-caret-down"></i></a>
		            	<ul class="dropdown-menu">
			            	<li><a href="<?= site_url('reporte/ingresados'); ?>">Contratos ingresados</a></li>
			            	<li><a href="<?= site_url('reporte/vigentes'); ?>">Vigentes</a></li>
			            	<li><a href="<?= site_url('reporte/vencidos'); ?>">Vencidos</a></li>
			            </ul>
			        </li>
	            	<?php if($this->session->userdata('privilegio') == 'ADM'){ ?>
	            	<li class="dropdown <?= ($this->uri->segment(1) == 'admin') ? 'active open': ''; ?>">
		            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> Administración &nbsp;<i class="fa fa-caret-down"></i></a>
		            	<ul class="dropdown-menu">
			            	<li><a href="<?= site_url('admin/proveedores'); ?>">Proveedores</a></li>
			            	<li><a href="<?= site_url('admin/auditoria'); ?>">Auditoría</a></li>
			            	<li><a href="<?= site_url('admin/usuarios'); ?>">Usuarios</a></li>
			            </ul>
			        </li>
			        <?php } ?>
			        <li><a href="<?= site_url('login/salir'); ?>"><i class="fa fa-power-off"></i> Salir</a></li>
			    </ul>

			</div>
		</nav>

		<div id="page-wrapper">