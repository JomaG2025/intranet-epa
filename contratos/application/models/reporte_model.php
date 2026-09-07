<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte_model extends CI_Model {
	
    

    public function __construct()
    {
        parent::__construct();
        // Cargamos el modelo de contrato para poder llamar a actualizar_estado_vencido()
        $this->load->model('contrato_model');
    }

	public function listar_ingresados($desde, $hasta)
	{        		
		$query = $this->db->select('contrato.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, moneda.simbolo AS moneda_simbolo, tipoplazo.nombre AS tipoplazo_nombre, tipopago.nombre AS tipopago_nombre');
        $query = $this->db->join('estado', 'contrato.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'contrato.proveedor = proveedor.rut');
        $query = $this->db->join('moneda', 'contrato.moneda = moneda.cod');
        $query = $this->db->join('tipoplazo', 'contrato.tipoplazo = tipoplazo.abrev');
        $query = $this->db->join('tipopago', 'contrato.tipopago = tipopago.abrev');
		$query = $this->db->where('contrato.creado >=', $desde);
        $query = $this->db->where('contrato.creado <=', $hasta);
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('contrato');
		
		return $query->result_array();
	}
    
    public function listar_vencidos()
	{        		
        // 1) Sincronizar estados: marcar como VEN los contratos cuyo término ya pasó
        $this->contrato_model->actualizar_estado_vencido();

        // 2) Luego procedemos a la consulta habitual
        $query = $this->db->select('contrato.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, moneda.simbolo AS moneda_simbolo, tipoplazo.nombre AS tipoplazo_nombre, tipopago.nombre AS tipopago_nombre');
        $query = $this->db->join('estado', 'contrato.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'contrato.proveedor = proveedor.rut');
        $query = $this->db->join('moneda', 'contrato.moneda = moneda.cod');
        $query = $this->db->join('tipoplazo', 'contrato.tipoplazo = tipoplazo.abrev');
        $query = $this->db->join('tipopago', 'contrato.tipopago = tipopago.abrev');
        $query = $this->db->where('estado', 'VEN');
        $query = $this->db->order_by('id', 'DESC');
        $query = $this->db->get('contrato');
        
        return $query->result_array();
	}
    
    public function listar_vigentes()
	{        		
		$query = $this->db->select('contrato.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, moneda.simbolo AS moneda_simbolo, tipoplazo.nombre AS tipoplazo_nombre, tipopago.nombre AS tipopago_nombre');
        $query = $this->db->join('estado', 'contrato.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'contrato.proveedor = proveedor.rut');
        $query = $this->db->join('moneda', 'contrato.moneda = moneda.cod');
        $query = $this->db->join('tipoplazo', 'contrato.tipoplazo = tipoplazo.abrev');
        $query = $this->db->join('tipopago', 'contrato.tipopago = tipopago.abrev');
        $query = $this->db->where('estado !=', 'VEN');
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('contrato');
		
		return $query->result_array();
	}
}