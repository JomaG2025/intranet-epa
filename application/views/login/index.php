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
	
	<div class="container container-login">
		
		<div class="panel panel-default no-border">
			<div class="panel-body text-center"> 
			    
			    <div class="col-xs-8 col-xs-offset-2">
				    <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjQxNC42NjRweCIgaGVpZ2h0PSIyNTAuMjI3cHgiIHZpZXdCb3g9IjAgMCA0MTQuNjY0IDI1MC4yMjciIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDQxNC42NjQgMjUwLjIyNyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggZmlsbD0iIzI1MzM2RCIgZD0iTTIyNi41NzMsMTg4LjU5N2wxNC42NTMtNDEuNTIxaDE2LjM4NWwxNC41MDcsNDEuNTIxaC0xNS44MzlsLTEuMzU5LTUuMjg5aC0xMS4yM2wtMS4zMjgsNS4yODlIMjI2LjU3M3ogTTI0Ni4xODQsMTczLjQ5M2g2LjI2N2wtMy4wODktMTIuMTU4TDI0Ni4xODQsMTczLjQ5M3oiLz48cGF0aCBmaWxsPSIjMjUzMzZEIiBkPSJNMjczLjMzNCwxODguNTk3di00MS41MjFoMTUuOTkxYzMuNjA0LDAsNi40MjgsMC4yMjUsOC40NTksMC42ODdjMi4wMjYsMC40NDQsMy44MzksMS4yNzgsNS40MTUsMi40NzVjMS41NzQsMS4yMTIsMi44NjMsMi43MiwzLjg1NSw0LjU1YzAuOTg5LDEuODM4LDEuNDgxLDMuOTc5LDEuNDgxLDYuNDMyYzAsMS42MTUtMC4yMjIsMy4wODItMC42ODEsNC40MjdjLTAuNDQ4LDEuMzQ1LTEuMDIxLDIuNDQyLTEuNzI2LDMuMjk5Yy0wLjY5OCwwLjg2NS0xLjQzOCwxLjUzMy0yLjIxMywyLjAwNWMtMC43NzIsMC40NjUtMS43MzUsMC44NzUtMi44OTYsMS4xODhsMTAuOTM5LDE2LjQ2MmgtMTcuMmwtNi43NjktMTMuODI0djEzLjgyNEwyNzMuMzM0LDE4OC41OTdMMjczLjMzNCwxODguNTk3eiBNMjg3LjkwNiwxNjUuNjE2aDAuOTljMS43NiwwLDIuOTE4LTAuMTcxLDMuNDkyLTAuNTEzYzEuMDk0LTAuNjgzLDEuNjQ0LTEuNzE1LDEuNjQ0LTMuMTEyYzAtMS4xNTYtMC4zNTQtMi4wMjUtMS4wNDgtMi42MTNjLTAuNy0wLjU4LTIuMDQxLTAuODc5LTMuOTk4LTAuODc5aC0xLjA4VjE2NS42MTZMMjg3LjkwNiwxNjUuNjE2eiIvPjxyZWN0IHg9IjMxMy41NjEiIHk9IjE0Ny4wNzciIGZpbGw9IiMyNTMzNkQiIHdpZHRoPSIxNC45MSIgaGVpZ2h0PSI0MS41MjEiLz48cGF0aCBmaWxsPSIjMjUzMzZEIiBkPSJNMzY0LjQ2OSwxNDguMTU5bDAuMTkzLDE1LjcyM2MtMi42ODItMi43OTMtNS41MDMtNC4xODgtOC40NzUtNC4xODhjLTIuMzc3LDAtNC4zNTksMC43NzQtNS45MzgsMi4zMzRjLTEuNTc4LDEuNTYyLTIuMzY2LDMuNDk0LTIuMzY2LDUuOGMwLDIuMjUsMC43Nyw0LjEzNywyLjMwNyw1LjY2NmMxLjU0MSwxLjUzLDMuNTAyLDIuMjk5LDUuODgxLDIuMjk5YzEuNTksMCwzLjE3Mi0wLjM1NCw0Ljc0Mi0xLjA1NGMxLjU2NS0wLjY5LDIuOS0xLjU2MywzLjk5OC0yLjYwNGwtMC40MDQsMTUuNDQ5Yy0zLjU5MiwxLjIyNC03LjA5OCwxLjg0LTEwLjUyLDEuODRjLTMuNjgzLDAtNi45NjItMC43MTctOS44MzQtMi4xNDljLTIuMzM3LTEuMTk2LTQuMzM2LTIuNzQ0LTUuOTc1LTQuNjUxYy0xLjY2My0xLjkyLTIuOTM4LTMuOTItMy44MzEtNS45NzljLTEuMTU0LTIuNzIyLTEuNzI5LTUuNTQzLTEuNzI5LTguNDc4YzAtMy44MTMsMC45NjMtNy40NDcsMi44ODEtMTAuOTA4YzEuOTEtMy40NTcsNC41MDYtNi4xNTMsNy43NjQtOC4wNzNjMy4yNjYtMS45MzEsNy4wMjUtMi44OTYsMTEuMjk3LTIuODk2YzEuNTY0LDAsMy4wNTUsMC4xMjEsNC40NDUsMC4zNjdDMzYwLjMzNCwxNDYuODk0LDM2Mi4xOCwxNDcuNDAzLDM2NC40NjksMTQ4LjE1OSIvPjxwYXRoIGZpbGw9IiMyNTMzNkQiIGQ9Ik0zNjUuMjU1LDE4OC41OTdsMTQuNjYyLTQxLjUyMWgxNi4zODFsMTQuNTEsNDEuNTIxaC0xNS44NDNsLTEuMzU5LTUuMjg5aC0xMS4yMTlsLTEuMzMsNS4yODlIMzY1LjI1NXogTTM4NC44OCwxNzMuNDkzaDYuMjY3bC0zLjA5LTEyLjE1OEwzODQuODgsMTczLjQ5M3oiLz48cGF0aCBmaWxsPSIjMjUzMzZEIiBkPSJNMy44NTYsMTg4LjMxNXYtNDAuNjQ3aDE3LjI1NWMzLjM0MywwLDUuODY4LDAuMjYzLDcuNTUxLDAuNzcxYzEuNzAzLDAuNTA4LDMuMTAyLDEuMTYyLDQuMjI2LDEuOTYxYzEuMTE1LDAuNzk1LDIuMTEyLDEuNzA5LDIuOTc5LDIuNzc0YzAuODc1LDEuMDQ1LDEuNTgyLDIuMzI4LDIuMTM4LDMuODE4YzAuNTU2LDEuNTIsMC44MzUsMy4zMTcsMC44MzUsNS4zODFjMCwyLjY2Ni0wLjQ5Miw0Ljk5Mi0xLjQ3MSw2Ljk4MWMtMC45ODQsMS45ODUtMi4yMDcsMy41MjgtMy42NjUsNC42MTRjLTAuNzg4LDAuNTk3LTEuODgsMS4wODktMy4yNDMsMS40NzhjLTEuODI5LDAuNTEzLTQuMTk3LDAuNzcxLTcuMTAzLDAuNzcxaC01LjE1NHYxMi4wOTlIMy44NTZ6IE0xOC4yMDMsMTY1LjgxOWgxLjU0YzEuNzQ3LDAsMi44ODYtMC4xNzIsMy40NDUtMC41YzEuMDc1LTAuNjcyLDEuNjE5LTEuNjgzLDEuNjE5LTMuMDUxYzAtMS4xMjctMC4zNTQtMS45NzgtMS4wNC0yLjU1Yy0wLjY3NC0wLjU3My0xLjk4Mi0wLjg2NC0zLjkxLTAuODY0aC0xLjY1NFYxNjUuODE5eiIvPjxwYXRoIGZpbGw9IiMyNTMzNkQiIGQ9Ik00MS40NDMsMTQ3LjY2N2gxNC40NDh2MjMuMjAxYzAsMS4zNjYsMC4wOTEsMi4zNSwwLjI1MiwyLjkzOGMwLjI0MSwwLjgyMSwwLjY4NCwxLjQ2NSwxLjMyMSwxLjk0YzAuNjM1LDAuNDc5LDEuNDg1LDAuNzI4LDIuNTQyLDAuNzI4YzEuMzEyLDAsMi4zMTMtMC40MDYsMy4wMDctMS4yMjZjMC42OS0wLjgxMiwxLjA0NC0yLjMwOCwxLjA0NC00LjQ2N3YtMjMuMTE3aDE0LjUzNnYyNC4yNzhjMCwzLjQ4NC0wLjUwOSw2LjMxLTEuNTIxLDguNDkyYy0xLjI5OCwyLjc3NC0zLjM4OCw0LjkyOS02LjI3NCw2LjQ3OGMtMi44ODUsMS41NDUtNi40NjgsMi4zMTktMTAuNzM2LDIuMzE5Yy0zLjAzMywwLTUuODEzLTAuNDItOC4zNTQtMS4yNDhjLTEuNTUzLTAuNTAyLTIuOTYtMS4xNzgtNC4yMjYtMi4wMjhjLTEuMjczLTAuODUxLTIuMjk3LTEuNzc0LTMuMDc4LTIuNzkxYy0wLjc5Mi0xLjAwNi0xLjQ3OS0yLjM0LTIuMDY4LTQuMDA0Yy0wLjU5Ni0xLjY2OC0wLjg5Mi0zLjUwNC0wLjg5Mi01LjUyMUw0MS40NDMsMTQ3LjY2N0w0MS40NDMsMTQ3LjY2N3oiLz48cG9seWdvbiBmaWxsPSIjMjUzMzZEIiBwb2ludHM9IjgyLjc1MiwxODguMzE1IDgyLjc1MiwxNDcuNjY3IDEwOS41ODMsMTQ3LjY2NyAxMDkuNTgzLDE1OC42NTcgOTcuMjM3LDE1OC42NTcgOTcuMjM3LDE2Mi42MDEgMTA4LjUyOCwxNjIuNjAxIDEwOC41MjgsMTczLjA1OCA5Ny4yMzcsMTczLjA1OCA5Ny4yMzcsMTc3LjI3OCAxMTAuMDU2LDE3Ny4yNzggMTEwLjA1NiwxODguMzE1ICIvPjxwYXRoIGZpbGw9IiMyNTMzNkQiIGQ9Ik0xMTMuNDEyLDE4OC4zMTV2LTQwLjY0N2gxNS42NDNjMy41MzYsMCw2LjI5NSwwLjIyOCw4LjI3OCwwLjY3NmMxLjk4NCwwLjQ0NSwzLjc1OSwxLjI1LDUuMzA1LDIuNDI5YzEuNTQzLDEuMTcsMi43OTksMi42NTUsMy43NzUsNC40NTNjMC45NjgsMS43ODksMS40NTUsMy44OSwxLjQ1NSw2LjI5NWMwLDEuNTc1LTAuMjIzLDMuMDE3LTAuNjc0LDQuMzI4Yy0wLjQ0MSwxLjMxMi0xLjAwMSwyLjM5Mi0xLjY4NCwzLjIzM2MtMC42ODksMC44NDItMS40MDQsMS40ODctMi4xNjEsMS45NTNjLTAuNzY5LDAuNDY1LTEuNzA5LDAuODUyLTIuODM4LDEuMTY0bDEwLjcxMSwxNi4xMTZoLTE2LjgzN2wtNi42My0xMy41MzR2MTMuNTM0SDExMy40MTJ6IE0xMjcuNjY5LDE2NS44MTloMC45NzJjMS43MjUsMCwyLjg1NC0wLjE3MiwzLjQxMi0wLjVjMS4wNzUtMC42NzIsMS42MDctMS42ODMsMS42MDctMy4wNTFjMC0xLjEyNy0wLjM0My0xLjk3OC0xLjAyNC0yLjU1Yy0wLjY4Mi0wLjU3My0xLjk4NC0wLjg2NC0zLjkxMi0wLjg2NGgtMS4wNTVWMTY1LjgxOUwxMjcuNjY5LDE2NS44MTl6Ii8+PHBvbHlnb24gZmlsbD0iIzI1MzM2RCIgcG9pbnRzPSIxNzIuNDQ2LDE4OC4zMTUgMTU3Ljg1NiwxODguMzE1IDE1Ny44NTYsMTYwLjkwNyAxNDkuNzQ4LDE2MC45MDcgMTQ5Ljc0OCwxNDcuNjY3IDE4MC41MiwxNDcuNjY3IDE4MC41MiwxNjAuOTA3IDE3Mi40NDYsMTYwLjkwNyAiLz48cGF0aCBmaWxsPSIjMjUzMzZEIiBkPSJNMTgxLjI3MywxNjguMjAyYzAtMi41MTcsMC40ODktNS4xNDcsMS40NzktNy45MDRjMC45OTEtMi43NTIsMi41MDgtNS4xOTIsNC41NTItNy4yODljMi4wNTEtMi4xMTYsNC40NDktMy42ODQsNy4yMzYtNC43MDljMi43ODktMS4wMjYsNS44NTEtMS41MzksOS4yMDQtMS41MzljNi42LDAsMTEuOTM4LDIuMDA1LDE2LjAwMyw2LjAwM2M0LjA2Nyw0LjAwOCw2LjEwNiw5LjEzOSw2LjEwNiwxNS4zOTRjMCw2LjIwNy0yLjA1NiwxMS4zMTItNi4xNTgsMTUuMjkxYy00LjEsMy45OTEtOS40OTcsNS45NzktMTYuMTc0LDUuOTc5Yy00LjAyNywwLTcuNjIxLTAuNzY2LTEwLjc2My0yLjI5M2MtMi4zMzQtMS4xMzctNC4zMzItMi41ODYtNS45OTctNC4zNjJjLTEuNjY4LTEuNzc4LTIuOTAzLTMuNjUxLTMuNzE5LTUuNjI5QzE4MS44NiwxNzQuMzA0LDE4MS4yNzMsMTcxLjMzMSwxODEuMjczLDE2OC4yMDIgTTE5Ni4yMjcsMTY4LjM5YzAsMi4yNDUsMC42ODEsNC4wNjcsMi4wNjIsNS40NTljMS4zODEsMS4zOTcsMy4xMSwyLjA5OSw1LjIwMiwyLjA5OWMyLjExOSwwLDMuODkxLTAuNzE4LDUuMzAxLTIuMTVjMS40MDUtMS40MzUsMi4xMDQtMy4yNzYsMi4xMDQtNS41MzdjMC0yLjQyMi0wLjY5OS00LjM3Ny0yLjEwNC01Ljg2OGMtMS40MS0xLjQ4MS0zLjE0Ny0yLjIzMy01LjIxLTIuMjMzYy0yLjA4NSwwLTMuODI1LDAuNzUyLTUuMjM2LDIuMjVDMTk2LjkzNCwxNjMuOTAzLDE5Ni4yMjcsMTY1LjkxMSwxOTYuMjI3LDE2OC4zOSIvPjxwYXRoIGZpbGw9IiNGMjhCMkYiIGQ9Ik0zMTIuMTQ4LDEyNy45MjljLTEuODk5LDUuMTUxLDAuNjY2LDEwLjg3LDUuNzQ4LDEyLjc2MmM1LjA3NiwxLjg4OCwxMC43NDItMC43NiwxMi42NTEtNS45MThjMS45MDctNS4xNDctMC42NjYtMTAuODY2LTUuNzU2LTEyLjc1OEMzMTkuNzI1LDEyMC4xMjYsMzE0LjA1LDEyMi43NzIsMzEyLjE0OCwxMjcuOTI5Ii8+PHBhdGggZmlsbD0iIzEzQThFMiIgZD0iTTIxOS40OTEsNTkuNDQ4YzEzLjc1OCwwLjA0NywyNS45NTIsMi43MzksMzYuNjY5LDcuMDQ1YzMzLjU2OCwxMy40ODUsNTIuNTgxLDQyLjY5LDU5LjQ1OSw1NS4xODNjLTAuNDYzLDAuMjgzLTAuOTAzLDAuNjAxLTEuMzI4LDAuOTU1Yy0xNy4zNDItMTkuMjY4LTMzLjcxMy0yOS43NDQtNDguNy0zNC4zMjRjLTkuNzQzLTIuOTc5LTE4LjkwNy0zLjQ2NC0yNy4zOTYtMi4yNDJjLTM5LjMzNyw1LjYzNy02NC4wOTcsNDcuNzktNjQuMDk3LDQ3Ljc5QzE2OC4xNCw5NC40NzksMTI4Ljk2LDkxLjYxLDEyOC45Niw5MS42MUMxNjQuNTYzLDY4LjAwMSwxOTQuNTg3LDU5LjM2OSwyMTkuNDkxLDU5LjQ0OCIvPjxwYXRoIGZpbGw9IiMyNTMzNkQiIGQ9Ik0yNTYuNjU4LDYxLjE1MmM4LjM2MS0xMC45ODQtMy44OTctMjguNTI1LTMuODk3LTI4LjUyNWM2MS42NSwxNS45NCw2Ny44MDYsNjguNzIxLDY4LjAwMyw4Ny40MjRjLTAuNjUsMC4wMzktMS4yODcsMC4xMzQtMS45MTYsMC4yODVDMzAyLjI3LDY0LjM5NCwyNTYuNjU4LDYxLjE1MiwyNTYuNjU4LDYxLjE1MiIvPjxwYXRoIGZpbGw9IiMxNTdCMzciIGQ9Ik0zMTAuOTUyLDEyNy4yNmMwLDAtNzAuNTE1LTIzLjMzOS02Mi4zNDctMTEyLjYxOWMwLDAtMjQuNTI2LDEzLjA1OC00OS4wNjQtMTAuNTI2YzAsMCwxMi4xODksMTAxLjA4NywxMTAuOTU5LDEyNC41NzhMMzEwLjk1MiwxMjcuMjZ6Ii8+PGc+PHJlY3QgeD0iMy44NTYiIHk9IjIwMS44NzIiIGZpbGw9IiNGMjhCMkYiIHdpZHRoPSI0MDYuOTQ5IiBoZWlnaHQ9IjIuMDE5Ii8+PC9nPjwvZz48Zz48cGF0aCBmaWxsPSIjMDAxNjg5IiBkPSJNMTM3LjAwNywyMTkuMzY3djIzLjMyOGgtMi42MTV2LTIzLjMyOEgxMzcuMDA3eiIvPjxwYXRoIGZpbGw9IiMwMDE2ODkiIGQ9Ik0xNDMuNTk5LDI0Mi42OTZ2LTI0LjMwMmwxOC4xODQsMTkuMDM4di0xOC4wNjRoMi42MTV2MjQuMzg4bC0xOC4xODQtMTkuMDM4djE3Ljk3OUgxNDMuNTk5eiIvPjxwYXRoIGZpbGw9IiMwMDE2ODkiIGQ9Ik0xNzYuNjYzLDIyMS43OTV2MjAuOWgtMi42MTV2LTIwLjloLTUuNjR2LTIuNDI4aDEzLjkyOHYyLjQyOEgxNzYuNjYzeiIvPjxwYXRoIGZpbGw9IiMwMDE2ODkiIGQ9Ik0xOTIuNDUsMjMyLjI1NGw3LjQxNywxMC40NDFoLTMuMTc5bC03LjA0MS0xMC4yMzZoLTAuNjY3djEwLjIzNmgtMi42MTV2LTIzLjMyOGgzLjc3N2MxLjMzMywwLDIuNDI5LDAuMDk4LDMuMjksMC4yOTFjMC44NiwwLjE5NCwxLjYyNiwwLjUxOSwyLjI5OCwwLjk3NWMwLjg0MywwLjU2OSwxLjQ4NywxLjMzMiwxLjkzMSwyLjI4OWMwLjQzMywwLjkzNSwwLjY0OSwxLjkzMiwwLjY0OSwyLjk5MWMwLDEuNzY3LTAuNTMzLDMuMjI1LTEuNTk4LDQuMzc1QzE5NS42NDgsMjMxLjQ0LDE5NC4yMjcsMjMyLjA5NCwxOTIuNDUsMjMyLjI1NHogTTE4OC45OCwyMzAuMTY4aDEuNjI0YzEuNTgzLDAsMi43OTEtMC4zMTIsMy42MjMtMC45MzljMS4wMTQtMC43NzQsMS41MjEtMS44OCwxLjUyMS0zLjMxNWMwLTEuNTI2LTAuNTY0LTIuNjQ5LTEuNjkyLTMuMzY2Yy0wLjgwOS0wLjUwMi0xLjk4Mi0wLjc1Mi0zLjUyMS0wLjc1MmgtMS41NTVWMjMwLjE2OHoiLz48cGF0aCBmaWxsPSIjMDAxNjg5IiBkPSJNMjE3LjMxMSwyMzYuMDk5aC05Ljk2M2wtMi44Miw2LjU5N2gtMi44MzdsMTAuNzQ5LTI0LjQzOGwxMC40NzcsMjQuNDM4aC0yLjg1NEwyMTcuMzExLDIzNi4wOTl6IE0yMTYuMjg1LDIzMy42NzJsLTMuODk2LTkuMzQ4bC0zLjk4Miw5LjM0OEgyMTYuMjg1eiIvPjxwYXRoIGZpbGw9IiMwMDE2ODkiIGQ9Ik0yMjYuNjg5LDI0Mi42OTZ2LTI0LjMwMmwxOC4xODQsMTkuMDM4di0xOC4wNjRoMi42MTN2MjQuMzg4bC0xOC4xODQtMTkuMDM4djE3Ljk3OUgyMjYuNjg5eiIvPjxwYXRoIGZpbGw9IiMwMDE2ODkiIGQ9Ik0yNjYuMTQ1LDIyMS43OTVoLTkuNDY5djYuOTIxaDkuMjEzdjIuNDI3aC05LjIxM3Y5LjEyNmg5LjQ2OXYyLjQyN2gtMTIuMDgydi0yMy4zMjhoMTIuMDgyVjIyMS43OTV6Ii8+PHBhdGggZmlsbD0iIzAwMTY4OSIgZD0iTTI3Ny4xNDUsMjIxLjc5NXYyMC45aC0yLjYxNXYtMjAuOWgtNS42NDF2LTIuNDI4aDEzLjkzdjIuNDI4SDI3Ny4xNDV6Ii8+PC9nPjwvc3ZnPg==" alt="" class="img-responsive" />
				</div>
                
                <div class="clearfix"></div>
                
                <div class="col-xs-12">
                    <form action="<?= site_url('login/autentificar'); ?>" method="post">
                        <fieldset>
                            <div class="form-group">
                                <label class="small">Usuario</label>
                                <input name="usuario" type="text" class="form-control" required autocomplete="off" autofocus />
                            </div>
                            <div class="form-group">
                                <label class="small">Contraseña</label>
                                <div class="input-group">
                                    <input name="password" type="password" class="form-control" required autocomplete="off" />
                                    <span class="input-group-btn">
                                        <a class="btn btn-default btn-ver-password"><i class="fa fa-eye"></i></a>  
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                              <button class="btn btn-info btn-block" type="submit">Ingresar</button>
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