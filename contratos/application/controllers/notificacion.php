<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notificacion extends CI_Controller {

    // Usuarios que reciben notificaciones independiente de su privilegio
    private $destinatarios_fijos_estado  = array('jbernal');
    private $destinatarios_fijos_informe = array('hmorales');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('contrato_model', 'contrato');
        $this->load->model('contraparte_model', 'contraparte');
        $this->load->model('usuario_model', 'usuario');
        $this->load->model('historial_estado_model', 'historial_estado');
        $this->load->library('email');
    }

    public function estado()
    {
        $por_alertar = $this->contrato->seleccionar_por_alertar();
        $por_vencer  = $this->contrato->seleccionar_por_vencer();

        $this->contrato->actualizar_estado_alertado();
        $this->contrato->actualizar_estado_vencido();

        foreach ($por_alertar as $c)
            $this->historial_estado->insertar(array('contrato' => $c['id'], 'estado_anterior' => 'ING', 'estado_nuevo' => 'ALE', 'origen' => 'notificacion', 'usuario' => NULL));

        foreach ($por_vencer as $c)
            $this->historial_estado->insertar(array('contrato' => $c['id'], 'estado_anterior' => $c['estado'], 'estado_nuevo' => 'VEN', 'origen' => 'notificacion', 'usuario' => NULL));

        $contratos = array_merge($por_alertar, $por_vencer);
        if (!$contratos) return;

        foreach ($this->usuario->listar() as $usuario)
        {
            $es_activo = ($usuario['privilegio'] == 'USR' && $usuario['activo'] == 1);
            $es_fijo   = in_array($usuario['usuario'], $this->destinatarios_fijos_estado);

            if ($es_activo || $es_fijo)
            {
                if ($datos = $this->_usuario_intranet($usuario['usuario']))
                {
                    $this->_enviar('Contratos con cambios de estado', 'notificacion/estado', $datos, $contratos);
                }
            }
        }

        foreach ($this->_agrupar_por_contraparte($contratos) as $usuario => $mis_contratos)
        {
            if ($datos = $this->_usuario_intranet($usuario))
            {
                $this->_enviar('Contratos con cambios de estado', 'notificacion/estado', $datos, $mis_contratos);
            }
        }
    }

    public function informe()
    {
        $contratos = array_merge(
            $this->contrato->listar(0, FALSE, 'VEN', NULL, TRUE),
            $this->contrato->listar(0, FALSE, 'ALE')
        );

        if (!$contratos) return;

        foreach ($this->usuario->listar() as $usuario)
        {
            $es_activo = ($usuario['privilegio'] == 'USR' && $usuario['activo'] == 1);
            $es_fijo   = in_array($usuario['usuario'], $this->destinatarios_fijos_informe);

            if ($es_activo || $es_fijo)
            {
                if ($datos = $this->_usuario_intranet($usuario['usuario']))
                {
                    $this->_enviar('Informe semanal de Contratos alertados y vencidos', 'notificacion/informe', $datos, $contratos);
                }
            }
        }

        foreach ($this->_agrupar_por_contraparte($contratos) as $usuario => $mis_contratos)
        {
            if ($datos = $this->_usuario_intranet($usuario))
            {
                $this->_enviar('Informe semanal de Contratos alertados y vencidos', 'notificacion/informe', $datos, $mis_contratos);
            }
        }
    }

    private function _enviar($asunto, $vista, $usuario, $contratos)
    {
        $this->email->clear();
        $this->email->from('no-responder@puertoarica.com', 'Módulo de Control de Contratos con terceros');
        $this->email->to($usuario['email']);
        $this->email->subject($asunto);
        $this->email->message($this->load->view($vista, array('contratos' => $contratos, 'usuario' => $usuario), TRUE));

        if (!$this->email->send())
        {
            log_message('error', 'Notificacion: fallo al enviar a ' . $usuario['email'] . ' — ' . $this->email->print_debugger(array('headers')));
        }
        else
        {
            log_message('info', 'Notificacion: enviado a ' . $usuario['email'] . ' (' . count($contratos) . ' contrato(s)) — ' . $asunto);
        }
    }

    private function _usuario_intranet($usuario)
    {
        $ctx  = stream_context_create(array('http' => array('timeout' => 5)));
        $json = @file_get_contents('http://intranet.puertoarica.cl/index.php/api/usuario/' . $usuario, FALSE, $ctx);

        if ($json === FALSE)
        {
            log_message('error', 'Notificacion: sin respuesta de API intranet para usuario ' . $usuario);
            return FALSE;
        }

        return json_decode($json, TRUE);
    }

    private function _agrupar_por_contraparte($contratos)
    {
        $grupos = array();

        foreach ($contratos as $contrato)
        {
            if ($contrapartes = $this->contraparte->listar($contrato['id']))
            {
                foreach ($contrapartes as $cp)
                {
                    $grupos[$cp['usuario']][] = $contrato;
                }
            }
        }

        return $grupos;
    }
}

/* End of file notificacion.php */
/* Location: ./application/controllers/notificacion.php */
