<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte_model extends CI_Model {
		
	public function listar_ingresadas($desde, $hasta)
	{        		
		$query = $this->db->select('garantia.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, institucion.nombre AS institucion_nombre, moneda.simbolo AS moneda_simbolo');
        $query = $this->db->join('estado', 'garantia.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'garantia.proveedor = proveedor.rut');
        $query = $this->db->join('institucion', 'garantia.institucion = institucion.cod');
        $query = $this->db->join('moneda', 'garantia.moneda = moneda.cod');
		$query = $this->db->where('garantia.creado >=', $desde);
        $query = $this->db->where('garantia.creado <=', $hasta);
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('garantia');
		
		return $query->result_array();
	}
    
    public function listar_vencidas()
	{        		
		$query = $this->db->select('garantia.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, institucion.nombre AS institucion_nombre, moneda.simbolo AS moneda_simbolo');
        $query = $this->db->join('estado', 'garantia.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'garantia.proveedor = proveedor.rut');
        $query = $this->db->join('institucion', 'garantia.institucion = institucion.cod');
        $query = $this->db->join('moneda', 'garantia.moneda = moneda.cod');
        $query = $this->db->where('estado', 'VEN');
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('garantia');
		
		return $query->result_array();
	}
    
    public function listar_devueltas()
	{        		
		$query = $this->db->select('garantia.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, institucion.nombre AS institucion_nombre, moneda.simbolo AS moneda_simbolo');
        $query = $this->db->join('estado', 'garantia.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'garantia.proveedor = proveedor.rut');
        $query = $this->db->join('institucion', 'garantia.institucion = institucion.cod');
        $query = $this->db->join('moneda', 'garantia.moneda = moneda.cod');
        $query = $this->db->where('estado', 'DEV');
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('garantia');
		
		return $query->result_array();
	}
    
    public function listar_cobradas()
	{        		
		$query = $this->db->select('garantia.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, institucion.nombre AS institucion_nombre, moneda.simbolo AS moneda_simbolo');
        $query = $this->db->join('estado', 'garantia.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'garantia.proveedor = proveedor.rut');
        $query = $this->db->join('institucion', 'garantia.institucion = institucion.cod');
        $query = $this->db->join('moneda', 'garantia.moneda = moneda.cod');
        $query = $this->db->where('estado', 'COB');
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('garantia');
		
		return $query->result_array();
	}
    
    public function listar_vigentes()
	{        		
		$query = $this->db->select('garantia.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, institucion.nombre AS institucion_nombre, moneda.simbolo AS moneda_simbolo');
        $query = $this->db->join('estado', 'garantia.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'garantia.proveedor = proveedor.rut');
        $query = $this->db->join('institucion', 'garantia.institucion = institucion.cod');
        $query = $this->db->join('moneda', 'garantia.moneda = moneda.cod');
        $query = $this->db->where('estado', 'ING');
        $query = $this->db->or_where('estado', 'ALE');
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('garantia');
		
		return $query->result_array();
	}
}