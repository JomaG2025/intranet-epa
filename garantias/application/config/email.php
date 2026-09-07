<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'smtp.gmail.com';
$config['smtp_port']   = 587;
$config['smtp_user']   = 'no-responder@puertoarica.com'; // Tu correo de Google Workspace
$config['smtp_pass']   = 'hguy opsr mztr ldlt'; // La contraseña de aplicación de 16 dígitos
$config['smtp_crypto'] = 'tls'; // También puedes usar 'ssl' con puerto 465 si lo prefieres
$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";