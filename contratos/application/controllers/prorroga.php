<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prorroga extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if ( ! $this->session->userdata('id'))
        {
            $this->output->set_status_header(401)->set_content_type('application/json');
            echo json_encode(array('error' => 'No autorizado'));
            exit;
        }

        $this->load->model('prorroga_model', 'prorroga');
        $this->load->model('contrato_model', 'contrato');
        $this->load->model('auditoria_model', 'auditoria');
        $this->load->model('historial_estado_model', 'historial_estado');
    }

    public function guardar()
    {
        $this->output->set_content_type('application/json');

        $contrato_id   = (int) $this->input->post('contrato');
        $termino_nuevo = trim($this->input->post('termino_nuevo'));
        $motivo        = trim($this->input->post('motivo'));

        if ( ! $contrato_id || ! $termino_nuevo)
        {
            echo json_encode(array('error' => 'Datos incompletos'));
            return;
        }

        $contrato = $this->contrato->seleccionar($contrato_id);
        if ( ! $contrato)
        {
            echo json_encode(array('error' => 'Contrato no encontrado'));
            return;
        }

        if ( ! $contrato['termino'])
        {
            echo json_encode(array('error' => 'El contrato no tiene fecha de término registrada'));
            return;
        }

        if ($termino_nuevo <= $contrato['termino'])
        {
            echo json_encode(array('error' => 'La nueva fecha debe ser posterior al término actual (' . $contrato['termino'] . ')'));
            return;
        }

        $prorroga_id = $this->prorroga->insertar(array(
            'contrato'         => $contrato_id,
            'termino_anterior' => $contrato['termino'],
            'termino_nuevo'    => $termino_nuevo,
            'motivo'           => $motivo ? $motivo : NULL,
            'usuario'          => $this->session->userdata('usuario'),
        ));

        $nuevo_estado = $this->_calcular_estado($termino_nuevo, $contrato['diasalerta']);

        $this->contrato->actualizar($contrato_id, array(
            'termino' => $termino_nuevo,
            'estado'  => $nuevo_estado,
        ));

        $this->auditoria->insertar(array('evento' => 'PRR', 'recurso' => 'contrato', 'instancia' => $contrato_id));

        if ($nuevo_estado !== $contrato['estado']) {
            $this->historial_estado->insertar(array(
                'contrato'        => $contrato_id,
                'estado_anterior' => $contrato['estado'],
                'estado_nuevo'    => $nuevo_estado,
                'origen'          => 'prorroga',
                'usuario'         => $this->session->userdata('usuario'),
            ));
        }

        echo json_encode(array('ok' => TRUE, 'id' => $prorroga_id, 'termino_nuevo' => $termino_nuevo, 'estado' => $nuevo_estado));
    }

    private function _calcular_estado($termino, $dias_alerta)
    {
        if (date($termino) <= date('Y-m-d')) return 'VEN';
        if ($dias_alerta && date('Y-m-d', strtotime('-' . $dias_alerta . ' day', strtotime($termino))) <= date('Y-m-d')) return 'ALE';
        return 'ING';
    }
}

/* End of file prorroga.php */
/* Location: ./application/controllers/prorroga.php */
