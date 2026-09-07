<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('id')) redirect('login');
        $this->load->model('contrato_model', 'contrato');
    }

    // ─── Dashboard de gestión ────────────────────────────────────────────────

    public function index()
    {
        $por_estado_raw = $this->contrato->contar_por_estado();
        $por_estado = array('ING' => 0, 'ALE' => 0, 'VEN' => 0);
        $total = 0;
        foreach ($por_estado_raw as $e)
        {
            if (array_key_exists($e['estado'], $por_estado)) $por_estado[$e['estado']] = (int) $e['total'];
            $total += (int) $e['total'];
        }
        $data['por_estado'] = $por_estado;
        $data['total']      = $total;

        $top = $this->contrato->top_proveedores(10);
        $data['top_proveedores'] = $top;

        $prov_labels = array();
        $prov_data   = array();
        foreach ($top as $p)
        {
            $label = strlen($p['razon']) > 35 ? substr($p['razon'], 0, 32) . '...' : $p['razon'];
            $prov_labels[] = $label;
            $prov_data[]   = (int) $p['total'];
        }
        $data['prov_labels'] = $prov_labels;
        $data['prov_data']   = $prov_data;

        $venc_db  = $this->contrato->vencimientos_por_mes(12);
        $venc_map = array();
        foreach ($venc_db as $v) $venc_map[$v['mes']] = (int) $v['total'];

        $meses_es    = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
        $venc_labels = array();
        $venc_data   = array();
        for ($i = 0; $i < 12; $i++)
        {
            $ts      = mktime(0, 0, 0, date('n') + $i, 1, date('Y'));
            $key     = date('Y-m', $ts);
            $mes_num = (int) date('n', $ts);
            $ano     = date('Y', $ts);
            $venc_labels[] = $meses_es[$mes_num] . ' ' . $ano;
            $venc_data[]   = isset($venc_map[$key]) ? $venc_map[$key] : 0;
        }
        $data['venc_labels'] = $venc_labels;
        $data['venc_data']   = $venc_data;

        $data['montos']     = $this->contrato->monto_total_por_moneda();
        $data['vencen_mes'] = $this->contrato->contar_vencen_este_mes();

        $this->load->view('header');
        $this->load->view('reporte/index', $data);
        $this->load->view('footer');
    }

    // ─── Reportes de listado ─────────────────────────────────────────────────

    public function ingresados($accion = NULL)
    {
        $desde_raw = $this->input->get('desde');
        $hasta_raw  = $this->input->get('hasta');

        $desde = $desde_raw ? $desde_raw : date('Y-m-01');
        $hasta  = $hasta_raw  ? $hasta_raw  : date('Y-m-d');

        $reportes = $this->contrato->listar_por_creado($desde, $hasta);

        if ($accion === 'imprimir')
        {
            $this->_csv($reportes, 'contratos_ingresados');
            return;
        }

        $data['reportes'] = $reportes;
        $data['desde']    = $desde;
        $data['hasta']    = $hasta;

        $this->load->view('header');
        $this->load->view('reporte/ingresados/index', $data);
        $this->load->view('footer');
    }

    public function vigentes($accion = NULL)
    {
        $reportes = $this->contrato->listar(0, FALSE, 'ING');

        if ($accion === 'imprimir')
        {
            $this->_csv($reportes, 'contratos_vigentes');
            return;
        }

        $data['reportes'] = $reportes;

        $this->load->view('header');
        $this->load->view('reporte/vigentes/index', $data);
        $this->load->view('footer');
    }

    public function vencidos($accion = NULL)
    {
        $reportes = $this->contrato->listar(0, FALSE, 'VEN');

        if ($accion === 'imprimir')
        {
            $this->_csv($reportes, 'contratos_vencidos');
            return;
        }

        $data['reportes'] = $reportes;

        $this->load->view('header');
        $this->load->view('reporte/vencidos/index', $data);
        $this->load->view('footer');
    }

    // ─── Descarga CSV ────────────────────────────────────────────────────────

    private function _csv($rows, $nombre)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nombre . '_' . date('Ymd') . '.csv"');

        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF"); // BOM UTF-8 para Excel

        fputcsv($out, array('ID','Ingresado','Estado','Identificación','RUT','Razón Social','Inicio','Término','Moneda','Monto'), ';');

        foreach ($rows as $r)
        {
            fputcsv($out, array(
                $r['id'],
                isset($r['creado'])        ? $r['creado']         : '',
                isset($r['estado_nombre']) ? $r['estado_nombre']  : '',
                $r['identificacion'],
                $r['proveedor'] . '-' . $r['proveedor_dv'],
                $r['proveedor_razon'],
                $r['inicio'],
                $r['termino'],
                $r['moneda'],
                $r['monto'],
            ), ';');
        }

        fclose($out);
    }
}

/* End of file reporte.php */
/* Location: ./application/controllers/reporte.php */
