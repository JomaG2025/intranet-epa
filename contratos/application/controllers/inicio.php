<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if( ! $this->session->userdata('id')) redirect('login');

        $this->load->model('contrato_model', 'contrato');
        $this->load->model('contraparte_model', 'contraparte');
    }

    public function index()
    {
        $this->session->set_userdata('referrer', 'inicio');

        // KPI summary
        $por_estado_raw = $this->contrato->contar_por_estado();
        $conteo = array('ING' => 0, 'ALE' => 0, 'VEN' => 0, 'total' => 0);
        foreach ($por_estado_raw as $e)
        {
            if (array_key_exists($e['estado'], $conteo)) $conteo[$e['estado']] = (int) $e['total'];
            $conteo['total'] += (int) $e['total'];
        }
        $data['conteo']     = $conteo;
        $data['vencen_mes'] = $this->contrato->contar_vencen_este_mes();
        $data['montos']     = $this->contrato->monto_total_por_moneda();

        $data['ultimos']  = $this->contrato->listar(0, 5);
        $data['vencidos'] = $this->contrato->listar(0, 5, 'VEN');

        $usuarios_api = json_decode(file_get_contents('http://intranet.puertoarica.cl/index.php/api/usuarios'), TRUE);
        $mapa_usuarios = array();

        if(is_array($usuarios_api))
        {
            foreach($usuarios_api as $item)
            {
                $mapa_usuarios[$item['usuario']] = !empty($item['nombre']) ? $item['nombre'] : $item['usuario'];
            }
        }

        $data['ultimos']  = $this->_agregar_responsables($data['ultimos'], $mapa_usuarios);
        $data['vencidos'] = $this->_agregar_responsables($data['vencidos'], $mapa_usuarios);

        $this->load->view('header');
        $this->load->view('inicio/index', $data);
        $this->load->view('footer');
    }

    private function _agregar_responsables($contratos, $mapa_usuarios = array())
    {
        if(empty($contratos)) return $contratos;

        $ids = array_column($contratos, 'id');
        $contrapartes = $this->contraparte->listar_por_contratos($ids);

        $responsables_por_contrato = array();

        foreach($contrapartes as $item)
        {
            $nombre = isset($mapa_usuarios[$item['usuario']]) ? $mapa_usuarios[$item['usuario']] : $item['usuario'];
            $responsables_por_contrato[$item['contrato']][] = $nombre;
        }

        foreach($contratos as &$contrato)
        {
            if(isset($responsables_por_contrato[$contrato['id']]))
            {
                $contrato['responsable'] = implode(', ', $responsables_por_contrato[$contrato['id']]);
            }
            else
            {
                $contrato['responsable'] = '-';
            }
        }

        return $contratos;
    }
}

/* End of file inicio.php */
/* Location: ./application/controllers/inicio.php */