<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notificacion extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('garantia_model', 'garantia');   
        $this->load->model('usuario_model', 'usuario');   
    }
    
    public function estado()
    {
        $alertadas = $this->garantia->seleccionar_por_alertar();
        $vencidas = $this->garantia->seleccionar_por_vencer();
        $this->garantia->actualizar_estado_alertado();
        $this->garantia->actualizar_estado_vencido();
        
        if($alertadas OR $vencidas)
        {
            $this->load->model('usuario_model', 'usuario');
            $this->load->library('email'); 

            $usuarios = $this->usuario->listar();
            
            foreach($usuarios as $usuario)
            {
                if(($usuario['activo'] == 1) AND (($usuario['privilegio'] == 'RES') OR ($usuario['privilegio'] == 'ADM')))
                {
                    if($usuario = $this->usuario->seleccionar_intranet($usuario['usuario']))
                    {
                        $this->email->from('no-responder@puertoarica.com', 'Garantías Proveedores');
                        $this->email->to($usuario['email']);
                        $this->email->subject('Garantías alertadas'); 
                        $this->email->message($this->load->view('notificacion/estado', array('alertadas' => $alertadas, 'vencidas' => $vencidas, 'usuario' => $usuario), TRUE));
                        $this->email->send();
                    }
                }
            }
        }
    }
    
    public function informe()
    {
        $alertadas = $this->garantia->listar(0, FALSE, 'ALE');
        $vencidas = $this->garantia->listar(0, FALSE, 'VEN');
        
        if($alertadas OR $vencidas)
        {
            $this->load->model('usuario_model', 'usuario');
            $this->load->library('email');
            
            $usuarios = $this->usuario->listar();
            
            foreach($usuarios as $usuario)
            {
                if(($usuario['activo'] == 1) AND (($usuario['privilegio'] == 'RES') OR ($usuario['privilegio'] == 'ADM')))
                {
                    if($usuario = $this->usuario->seleccionar_intranet($usuario['usuario']))
                    {
                        $this->email->from('no-responder@puertoarica.com', 'Garantías Proveedores');
                        $this->email->to($usuario['email']);
                        $this->email->subject('Informe semanal de Garantías alertadas'); 
                        $this->email->message($this->load->view('notificacion/informe', array('alertadas' => $alertadas, 'vencidas' => $vencidas, 'usuario' => $usuario), TRUE));
                        $this->email->send();
                    }
                }
            }            
        }
    }
}

/* End of file notificacion.php */
/* Location: ./application/controllers/notificacion.php */