<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hito extends CI_Controller {

    private $estados_validos = array('PEN', 'REV', 'PAG', 'REC');

    public function __construct()
    {
        parent::__construct();

        if ( ! $this->session->userdata('id'))
        {
            $this->output->set_status_header(401)->set_content_type('application/json');
            echo json_encode(array('error' => 'No autorizado'));
            exit;
        }

        $this->load->model('hito_model', 'hito');
        $this->load->model('contrato_model', 'contrato');
        $this->load->model('auditoria_model', 'auditoria');
    }

    public function guardar()
    {
        $this->output->set_content_type('application/json');

        $contrato    = (int) $this->input->post('contrato');
        $descripcion = trim($this->input->post('descripcion'));
        $id          = (int) $this->input->post('id');

        if ( ! $contrato || ! $descripcion)
        {
            echo json_encode(array('error' => 'Datos incompletos'));
            return;
        }

        $monto_raw = $this->input->post('monto');

        $data = array(
            'contrato'           => $contrato,
            'descripcion'        => $descripcion,
            'fecha_comprometida' => $this->input->post('fecha_comprometida') ? $this->input->post('fecha_comprometida') : NULL,
            'monto'              => ($monto_raw !== '' && $monto_raw !== FALSE && $monto_raw !== NULL) ? str_replace(',', '.', $monto_raw) : NULL,
            'observacion'        => $this->input->post('observacion') ? trim($this->input->post('observacion')) : NULL,
        );

        $monto_valor = $data['monto'] !== NULL ? floatval($data['monto']) : NULL;
        if ($monto_valor !== NULL && $monto_valor > 0)
        {
            $contrato_data = $this->contrato->seleccionar($contrato);
            if ($contrato_data && floatval($contrato_data['monto']) > 0)
            {
                $suma_actual = $this->hito->suma_montos($contrato, $id ? $id : NULL);
                $disponible  = floatval($contrato_data['monto']) - $suma_actual;
                if ($monto_valor > $disponible)
                {
                    echo json_encode(array(
                        'error' => 'El monto supera el disponible en el contrato (' . $contrato_data['moneda'] . ' ' . number_format($disponible, 0, ',', '.') . ')'
                    ));
                    return;
                }
            }
        }

        if ($id)
        {
            $this->hito->actualizar($id, $data);
            $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'hito', 'instancia' => $id));
            echo json_encode(array('ok' => TRUE, 'id' => $id));
        }
        else
        {
            $data['estado'] = 'PEN';

            if ($nuevo_id = $this->hito->insertar($data))
            {
                $this->auditoria->insertar(array('evento' => 'NEW', 'recurso' => 'hito', 'instancia' => $nuevo_id));
                echo json_encode(array('ok' => TRUE, 'id' => $nuevo_id));
            }
            else
            {
                echo json_encode(array('error' => 'Error al guardar hito'));
            }
        }
    }

    public function eliminar($id = NULL)
    {
        $this->output->set_content_type('application/json');

        if ( ! $id || ! $this->hito->seleccionar($id))
        {
            echo json_encode(array('error' => 'Hito no encontrado'));
            return;
        }

        $this->hito->eliminar($id);
        $this->auditoria->insertar(array('evento' => 'DEL', 'recurso' => 'hito', 'instancia' => $id));
        echo json_encode(array('ok' => TRUE));
    }

    public function estado($id = NULL, $nuevo_estado = NULL)
    {
        $this->output->set_content_type('application/json');

        if ( ! $id || ! $nuevo_estado || ! in_array($nuevo_estado, $this->estados_validos))
        {
            echo json_encode(array('error' => 'Parámetros inválidos'));
            return;
        }

        if ( ! $this->hito->seleccionar($id))
        {
            echo json_encode(array('error' => 'Hito no encontrado'));
            return;
        }

        $data = array('estado' => $nuevo_estado);
        $fecha_cumplimiento = NULL;

        if ($nuevo_estado === 'PAG')
        {
            $data['fecha_cumplimiento'] = date('Y-m-d');
            $fecha_cumplimiento = $data['fecha_cumplimiento'];
        }

        $this->hito->actualizar($id, $data);
        $this->auditoria->insertar(array('evento' => 'UPD', 'recurso' => 'hito', 'instancia' => $id));

        echo json_encode(array('ok' => TRUE, 'estado' => $nuevo_estado, 'fecha_cumplimiento' => $fecha_cumplimiento));
    }
}

/* End of file hito.php */
/* Location: ./application/controllers/hito.php */
